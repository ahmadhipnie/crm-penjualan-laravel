<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatUser extends Model
{
    use HasFactory;

    protected $table = 'alamat_users';

    protected $fillable = [
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kode_pos',
        'id_user'
    ];

    protected $casts = [
        'kode_pos' => 'integer',
        'id_user' => 'integer'
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
