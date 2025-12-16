<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailSent;

class EmailSentAdminController extends Controller
{
    /**
     * Display a listing of sent emails.
     */
    public function index(Request $request)
    {
        $query = EmailSent::query()->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('to', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $emailsSent = $query->paginate(15);

        return view('admin.email_sent.index', compact('emailsSent'));
    }

    /**
     * Display the specified sent email.
     */
    public function show($id)
    {
        $emailSent = EmailSent::findOrFail($id);
        return view('admin.email_sent.show', compact('emailSent'));
    }

    /**
     * Remove the specified sent email from storage.
     */
    public function destroy($id)
    {
        try {
            $emailSent = EmailSent::findOrFail($id);

            // Hapus file attachments jika ada
            $attachments = $emailSent->getAttachmentDetails();
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                }
            }

            $emailSent->delete();

            return redirect()->route('admin.email-sent.index')
                ->with('success', 'Email berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.email-sent.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
