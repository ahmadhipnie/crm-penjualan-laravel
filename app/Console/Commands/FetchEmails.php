<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Email;
use Illuminate\Support\Facades\Log;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch {--all : Fetch all emails including read ones} {--days=7 : Number of days to fetch (when using --all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch emails from inbox and store to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting to fetch emails...');

            // Email configuration from .env
            $imapHost = config('mail.imap_host', 'imap.gmail.com');
            $imapPort = config('mail.imap_port', 993);

            // Get credentials from mailer config or direct env
            $username = config('mail.mailers.smtp.username') ?? env('MAIL_USERNAME');
            $password = config('mail.mailers.smtp.password') ?? env('MAIL_PASSWORD');

            // Gmail IMAP connection string with additional flags
            $hostname = '{' . $imapHost . ':' . $imapPort . '/imap/ssl/novalidate-cert}INBOX';

            $this->info('Connecting to: ' . $imapHost . ':' . $imapPort);
            $this->info('Username: ' . $username);

            // Connect to mailbox with retry
            $inbox = @imap_open($hostname, $username, $password, 0, 1);

            if (!$inbox) {
                $error = imap_last_error();
                $this->error('Cannot connect to mailbox: ' . $error);

                // Try alternative connection string
                $this->info('Trying alternative connection method...');
                $hostname2 = '{' . $imapHost . ':' . $imapPort . '/imap/ssl}INBOX';
                $inbox = @imap_open($hostname2, $username, $password, 0, 1);

                if (!$inbox) {
                    $error = imap_last_error();
                    $this->error('Second attempt failed: ' . $error);
                    Log::error('IMAP connection failed', [
                        'error' => $error,
                        'host' => $imapHost,
                        'port' => $imapPort,
                        'username' => $username
                    ]);
                    return 1;
                }
            }

            $this->info('Connected to mailbox successfully');

            // Get emails based on options
            if ($this->option('all')) {
                $days = (int) $this->option('days');
                $since = date('d-M-Y', strtotime("-{$days} days"));
                $this->info("Fetching all emails from last {$days} days (since {$since})...");
                $emails = imap_search($inbox, "SINCE \"{$since}\"");
            } else {
                $this->info('Fetching unread emails only...');
                $emails = imap_search($inbox, 'UNSEEN');
            }

            if (!$emails) {
                $this->info('No new emails found');
                imap_close($inbox);
                return 0;
            }

            $this->info('Found ' . count($emails) . ' new email(s)');

            foreach ($emails as $emailNumber) {
                try {
                    // Get email overview
                    $overview = imap_fetch_overview($inbox, $emailNumber, 0);

                    // Get email structure for attachments and body
                    $structure = imap_fetchstructure($inbox, $emailNumber);

                    // Get message body
                    $message = $this->getEmailBody($inbox, $emailNumber, $structure);
                    $attachments = [];

                    // Process attachments if any
                    if (isset($structure->parts) && count($structure->parts)) {
                        for ($i = 0; $i < count($structure->parts); $i++) {
                            $part = $structure->parts[$i];

                            if ($part->ifdparameters) {
                                foreach ($part->dparameters as $object) {
                                    if (strtolower($object->attribute) == 'filename') {
                                        $attachment = $this->saveAttachment($inbox, $emailNumber, $i + 1, $object->value);
                                        if ($attachment) {
                                            $attachments[] = $attachment;
                                        }
                                    }
                                }
                            }

                            if ($part->ifparameters) {
                                foreach ($part->parameters as $object) {
                                    if (strtolower($object->attribute) == 'name') {
                                        $attachment = $this->saveAttachment($inbox, $emailNumber, $i + 1, $object->value);
                                        if ($attachment) {
                                            $attachments[] = $attachment;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Save to database only if not already exists
                    $existingEmail = Email::where('subject', isset($overview[0]->subject) ? $this->decodeEmailText($overview[0]->subject) : 'No Subject')
                        ->where('from', isset($overview[0]->from) ? $overview[0]->from : 'Unknown')
                        ->whereDate('created_at', date('Y-m-d', $overview[0]->udate ?? time()))
                        ->first();

                    if (!$existingEmail) {
                        Email::create([
                            'subject' => isset($overview[0]->subject) ? $this->decodeEmailText($overview[0]->subject) : 'No Subject',
                            'from' => isset($overview[0]->from) ? $overview[0]->from : 'Unknown',
                            'body' => $message,
                            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
                            'status' => 'unread'
                        ]);

                        $this->info('Saved email: ' . ($overview[0]->subject ?? 'No Subject'));
                    } else {
                        $this->line('Skipped duplicate: ' . ($overview[0]->subject ?? 'No Subject'));
                    }

                    // Mark as seen (optional - comment this line if you want to keep as unread in inbox)
                    // imap_setflag_full($inbox, $emailNumber, "\\Seen");

                } catch (\Exception $e) {
                    $this->error('Error processing email #' . $emailNumber . ': ' . $e->getMessage());
                    Log::error('Error processing email', [
                        'email_number' => $emailNumber,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            imap_close($inbox);
            $this->info('Email fetch completed successfully');

            Log::info('Emails fetched successfully', ['count' => count($emails)]);
            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Email fetch failed', ['error' => $e->getMessage()]);
            return 1;
        }
    }

    /**
     * Save attachment to public folder
     */
    private function saveAttachment($inbox, $emailNumber, $partNumber, $filename)
    {
        try {
            $attachment = imap_fetchbody($inbox, $emailNumber, $partNumber);

            // Decode based on encoding
            $structure = imap_bodystruct($inbox, $emailNumber, $partNumber);
            if ($structure->encoding == 3) { // Base64
                $attachment = base64_decode($attachment);
            } elseif ($structure->encoding == 4) { // Quoted-printable
                $attachment = quoted_printable_decode($attachment);
            }

            // Create directory if not exists
            $attachmentDir = public_path('attachments');
            if (!file_exists($attachmentDir)) {
                mkdir($attachmentDir, 0777, true);
            }

            // Generate unique filename
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            $filepath = $attachmentDir . '/' . $filename;

            // Save file
            file_put_contents($filepath, $attachment);

            return 'attachments/' . $filename;

        } catch (\Exception $e) {
            Log::error('Error saving attachment', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Decode email text (subject, from, etc)
     */
    private function decodeEmailText($text)
    {
        $elements = imap_mime_header_decode($text);
        $decoded = '';

        foreach ($elements as $element) {
            $decoded .= $element->text;
        }

        return $decoded;
    }

    /**
     * Get email body (HTML or plain text)
     */
    private function getEmailBody($inbox, $emailNumber, $structure)
    {
        $htmlBody = '';
        $plainBody = '';

        if (!isset($structure->parts)) {
            // Simple email - not multipart
            $body = imap_body($inbox, $emailNumber);

            // Decode based on encoding
            if ($structure->encoding == 3) { // Base64
                $body = base64_decode($body);
            } elseif ($structure->encoding == 4) { // Quoted-printable
                $body = quoted_printable_decode($body);
            }

            return $body;
        }

        // Multipart email - recursively parse parts
        $htmlBody = $this->getPart($inbox, $emailNumber, $structure, 'HTML');
        $plainBody = $this->getPart($inbox, $emailNumber, $structure, 'PLAIN');

        // Prioritize HTML over plain text
        return !empty($htmlBody) ? $htmlBody : $plainBody;
    }

    /**
     * Recursively get specific part type from email
     */
    private function getPart($inbox, $emailNumber, $structure, $partType, $partNumber = '')
    {
        if (!isset($structure->parts) || !is_array($structure->parts)) {
            return '';
        }

        foreach ($structure->parts as $index => $part) {
            $currentPartNumber = $partNumber ? "$partNumber." . ($index + 1) : ($index + 1);

            // Check if this part matches the type we're looking for
            if ($part->type == 0 && isset($part->subtype)) { // Type 0 = TEXT
                if (strtoupper($part->subtype) == strtoupper($partType)) {
                    $body = imap_fetchbody($inbox, $emailNumber, $currentPartNumber);

                    // Decode based on encoding
                    if ($part->encoding == 3) { // Base64
                        $body = base64_decode($body);
                    } elseif ($part->encoding == 4) { // Quoted-printable
                        $body = quoted_printable_decode($body);
                    }

                    return $body;
                }
            }

            // Recursively check nested parts (for multipart/alternative, etc)
            if (isset($part->parts) && is_array($part->parts)) {
                $result = $this->getPart($inbox, $emailNumber, $part, $partType, $currentPartNumber);
                if (!empty($result)) {
                    return $result;
                }
            }
        }

        return '';
    }
}

