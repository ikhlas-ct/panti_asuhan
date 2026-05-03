<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengurus extends Model
{
    protected $table = 'pengurus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'panti_asuhan_id',
        'user_id',
        'nama',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telp',
        'alamat',
        'jabatan',
        'pendidikan_terakhir',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_kelamin' => 'string',
        'status'        => 'string',
    ];

    // Panti tempat pengurus ini bertugas
    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }

    // Akun user pengurus ini (admin_panti), nullable
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUsiaAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
