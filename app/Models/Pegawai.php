<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'nohp',
        'deskripsi',
        'instagram',
        'twitter',
        'facebook',
        'posisi',
        'foto_profil',
    ];

    // Akun user pegawai ini (admin_dinsos)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
