<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $table = 'emails';

    protected $fillable = [
        'subject',
        'from',
        'body',
        'attachments',
        'status'
    ];

    // Relationship
    public function replies()
    {
        return $this->hasMany(Reply::class, 'id_email');
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
