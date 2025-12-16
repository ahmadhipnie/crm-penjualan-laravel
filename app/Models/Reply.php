<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $table = 'replies';

    protected $fillable = [
        'id_email',
        'to',
        'subject',
        'body',
        'attachments'
    ];

    protected $casts = [
        'id_email' => 'integer'
    ];

    // Relationship
    public function email()
    {
        return $this->belongsTo(Email::class, 'id_email');
    }

    // Accessor untuk attachments - convert JSON ke array
    public function getAttachmentsAttribute($value)
    {
        if ($value) {
            $attachments = json_decode($value, true);
            if (is_array($attachments)) {
                // Validate file existence and return full URLs
                return collect($attachments)->map(function($path) {
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
        }
        return [];
    }

    // Mutator untuk attachments - convert array ke JSON
    public function setAttachmentsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['attachments'] = json_encode($value);
        } else {
            $this->attributes['attachments'] = $value;
        }
    }
}
