<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSent extends Model
{
    use HasFactory;

    protected $table = 'email_sents';

    protected $fillable = [
        'to',
        'subject',
        'body',
        'attachments'
    ];

    // Explicitly cast attachments as string to prevent Laravel from treating it as array
    protected $casts = [
        'attachments' => 'string',
    ];

    // Mutator untuk attachments - convert array ke JSON
    public function setAttachmentsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['attachments'] = json_encode($value);
        } else {
            $this->attributes['attachments'] = $value;
        }
    }

    // Helper method untuk mendapatkan attachment details
    public function getAttachmentDetails()
    {
        if (empty($this->attributes['attachments'])) {
            return [];
        }

        $attachments = json_decode($this->attributes['attachments'], true);
        if (!is_array($attachments)) {
            return [];
        }

        return collect($attachments)->map(function($item) {
            // Handle if item is already an array (with path, url, filename keys)
            if (is_array($item)) {
                $path = $item['path'] ?? $item['url'] ?? null;
                if (!$path || !is_string($path)) {
                    return null;
                }
            } else {
                // Handle if item is a string (just the path)
                $path = $item;
                if (!is_string($path)) {
                    return null;
                }
            }

            // Check if file exists
            if (file_exists(public_path($path))) {
                return [
                    'path' => $path,
                    'url' => asset($path),
                    'filename' => basename($path)
                ];
            }
            return null;
        })->filter()->values()->toArray();
    }

    // Helper method untuk mendapatkan jumlah attachments
    public function getAttachmentCount()
    {
        if (empty($this->attributes['attachments'])) {
            return 0;
        }

        $attachments = json_decode($this->attributes['attachments'], true);
        return is_array($attachments) ? count($attachments) : 0;
    }
}
