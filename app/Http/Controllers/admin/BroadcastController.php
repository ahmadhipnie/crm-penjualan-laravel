<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\EmailSent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BroadcastMail;

class BroadcastController extends Controller
{
    /**
     * Display broadcast form
     */
    public function index()
    {
        // Get all customers (non-admin users)
        $customers = User::where('role', 'customer')->get();

        // Get all active products for promotion
        $barangs = Barang::where('stok', '>', 0)->with('gambarBarangs')->get();

        return view('admin.broadcast.index', compact('customers', 'barangs'));
    }

    /**
     * Send broadcast email
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|in:all,selected',
            'selected_customers' => 'required_if:recipients,selected|array',
            'selected_customers.*' => 'exists:users,id',
            'selected_products' => 'nullable|array',
            'selected_products.*' => 'exists:barangs,id',
            'attachment' => 'nullable|file|max:10240', // Max 10MB
        ]);

        try {
            // Get recipients
            if ($request->recipients == 'all') {
                $recipients = User::where('role', 'customer')->get();
            } else {
                $recipients = User::whereIn('id', $request->selected_customers)->get();
            }

            if ($recipients->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada penerima yang dipilih.');
            }

            // Handle attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('broadcast_attachments'), $filename);
                $attachmentPath = 'broadcast_attachments/' . $filename;
            }

            // Get selected products for promotion
            $products = [];
            if ($request->selected_products) {
                $products = Barang::whereIn('id', $request->selected_products)->with('gambarBarangs')->get();
            }

            // Send emails
            $successCount = 0;
            $failCount = 0;

            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient->email)->send(
                        new BroadcastMail(
                            $recipient->nama,
                            $request->message,
                            $products,
                            $request->subject,
                            $attachmentPath
                        )
                    );

                    // Log sent email - SATU RECORD PER RECIPIENT
                    EmailSent::create([
                        'to' => $recipient->email,
                        'subject' => $request->subject,
                        'body' => $request->message,
                        'attachments' => $attachmentPath ? json_encode([['path' => $attachmentPath, 'filename' => basename($attachmentPath)]]) : null,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send broadcast email to ' . $recipient->email, [
                        'error' => $e->getMessage()
                    ]);
                    $failCount++;
                }
            }

            $message = "Broadcast berhasil dikirim ke {$successCount} pelanggan.";
            if ($failCount > 0) {
                $message .= " {$failCount} email gagal dikirim.";
            }

            return redirect()->route('admin.broadcast.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Broadcast email failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal mengirim broadcast: ' . $e->getMessage());
        }
    }

    /**
     * Get customer emails for preview
     */
    public function getCustomerEmails(Request $request)
    {
        if ($request->type == 'all') {
            $customers = User::where('role', 'customer')->select('id', 'nama', 'email')->get();
        } else {
            $customers = User::whereIn('id', $request->ids ?? [])->select('id', 'nama', 'email')->get();
        }

        return response()->json([
            'customers' => $customers,
            'count' => $customers->count()
        ]);
    }
}
