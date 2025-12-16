<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $messageContent;
    public $products;
    public $emailSubject;
    public $attachmentPath;

    /**
     * Create a new message instance.
     */
    public function __construct($customerName, $messageContent, $products, $subject, $attachmentPath = null)
    {
        $this->customerName = $customerName;
        $this->messageContent = $messageContent;
        $this->products = $products;
        $this->emailSubject = $subject;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.broadcast.email_template',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->attachmentPath) {
            return [
                Attachment::fromPath(public_path($this->attachmentPath))
            ];
        }
        return [];
    }
}
