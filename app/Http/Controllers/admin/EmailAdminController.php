<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class EmailAdminController extends Controller
{
    /**
     * Display a listing of emails
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Email::orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $emails = $query->paginate(20);

        // Count by status
        $statusCounts = [
            'all' => Email::count(),
            'unread' => Email::where('status', 'unread')->count(),
            'read' => Email::where('status', 'read')->count(),
            'replied' => Email::where('status', 'replied')->count(),
        ];

        return view('admin.emails.index', compact('emails', 'status', 'statusCounts'));
    }

    /**
     * Display the specified email
     */
    public function show($id)
    {
        $email = Email::with('replies')->findOrFail($id);

        // Mark as read if unread
        if ($email->status === 'unread') {
            $email->update(['status' => 'read']);
        }

        return view('admin.emails.show', compact('email'));
    }

    /**
     * Show the form for replying to an email
     */
    public function reply($id)
    {
        $email = Email::findOrFail($id);
        return view('admin.emails.reply', compact('email'));
    }

    /**
     * Send reply to an email
     */
    public function sendReply(Request $request, $id)
    {
        try {
            $email = Email::findOrFail($id);

            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'body' => 'required|string',
                'attachments.*' => 'nullable|file|max:10240' // 10MB max
            ]);

            // Process attachments
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('attachments'), $filename);
                    $attachmentPaths[] = 'attachments/' . $filename;
                }
            }

            // Extract email address from 'from' field
            preg_match('/<(.+?)>/', $email->from, $matches);
            $toEmail = $matches[1] ?? $email->from;

            // Send email
            Mail::raw($validated['body'], function ($message) use ($toEmail, $validated, $attachmentPaths) {
                $message->to($toEmail)
                    ->subject($validated['subject']);

                // Attach files
                foreach ($attachmentPaths as $path) {
                    $message->attach(public_path($path));
                }
            });

            // Save reply to database
            Reply::create([
                'id_email' => $email->id,
                'to' => $toEmail,
                'subject' => $validated['subject'],
                'body' => $validated['body'],
                'attachments' => !empty($attachmentPaths) ? json_encode($attachmentPaths) : null,
            ]);

            // Update email status to replied
            $email->update(['status' => 'replied']);

            Log::info('Email reply sent successfully', [
                'email_id' => $email->id,
                'to' => $toEmail
            ]);

            Alert::success('Berhasil', 'Balasan email berhasil dikirim');
            return redirect()->route('admin.emails.show', $id);

        } catch (\Exception $e) {
            Log::error('Error sending email reply', [
                'email_id' => $id,
                'error' => $e->getMessage()
            ]);

            Alert::error('Gagal', 'Terjadi kesalahan saat mengirim balasan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete an email
     */
    public function destroy($id)
    {
        try {
            $email = Email::findOrFail($id);

            // Delete attachments if any
            if ($email->attachments) {
                foreach ($email->attachments as $attachment) {
                    $filePath = public_path($attachment['path']);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Delete replies and their attachments
            foreach ($email->replies as $reply) {
                if ($reply->attachments) {
                    foreach ($reply->attachments as $attachment) {
                        $filePath = public_path($attachment['path']);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }

            $email->delete();

            Alert::success('Berhasil', 'Email berhasil dihapus');
            return redirect()->route('admin.emails.index');

        } catch (\Exception $e) {
            Log::error('Error deleting email', [
                'email_id' => $id,
                'error' => $e->getMessage()
            ]);

            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus email');
            return redirect()->back();
        }
    }

    /**
     * Mark email as unread
     */
    public function markAsUnread($id)
    {
        try {
            $email = Email::findOrFail($id);
            $email->update(['status' => 'unread']);

            Alert::success('Berhasil', 'Email ditandai sebagai belum dibaca');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan');
            return redirect()->back();
        }
    }
}

