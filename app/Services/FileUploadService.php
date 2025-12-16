<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload gambar barang
     */
    public static function uploadGambarBarang(UploadedFile $file, $barangId = null)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'gambar_barang/' . $filename;

        $file->move(public_path('gambar_barang'), $filename);

        return $path;
    }

    /**
     * Upload gambar bukti sampai
     */
    public static function uploadGambarBuktiSampai(UploadedFile $file, $penjualanId = null)
    {
        $filename = 'bukti_' . $penjualanId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'gambar_bukti_sampai/' . $filename;

        $file->move(public_path('gambar_bukti_sampai'), $filename);

        return $path;
    }

    /**
     * Upload attachment email
     */
    public static function uploadAttachmentEmail(UploadedFile $file, $emailId = null)
    {
        $filename = 'email_' . $emailId . '_' . time() . '_' . $file->getClientOriginalName();
        $path = 'attachments/email/' . $filename;

        $file->move(public_path('attachments/email'), $filename);

        return $path;
    }

    /**
     * Upload attachment reply
     */
    public static function uploadAttachmentReply(UploadedFile $file, $replyId = null)
    {
        $filename = 'reply_' . $replyId . '_' . time() . '_' . $file->getClientOriginalName();
        $path = 'attachments/reply/' . $filename;

        $file->move(public_path('attachments/reply'), $filename);

        return $path;
    }

    /**
     * Delete file
     */
    public static function deleteFile($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
            return true;
        }
        return false;
    }

    /**
     * Get placeholder image for furniture
     */
    public static function getPlaceholderFurniture()
    {
        return 'img/placeholder-furniture.jpg';
    }

    /**
     * Check if file exists, if not return placeholder
     */
    public static function getImageUrl($path, $type = 'furniture')
    {
        if ($path && file_exists(public_path($path))) {
            return asset($path);
        }

        switch ($type) {
            case 'furniture':
                return asset('img/placeholder-furniture.jpg');
            default:
                return asset('img/placeholder.jpg');
        }
    }
}
