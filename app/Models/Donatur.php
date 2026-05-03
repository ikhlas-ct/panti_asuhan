<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donatur extends Model
{
    protected $table = 'donatur';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'nama',
        'jenis_donatur',
        'no_telp',
        'alamat',
        'foto',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jenis_donatur' => 'string',
        'status'        => 'string',
    ];

    // Akun user yang dimiliki donatur ini (nullable)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Semua donasi yang pernah dilakukan donatur ini
    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'donatur_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Donatur yang sudah punya akun
    public function scopeMemilikiAkun($query)
    {
        return $query->whereNotNull('user_id');
    }

    // Donatur tanpa akun (diinput manual oleh admin)
    public function scopeTanpaAkun($query)
    {
        return $query->whereNull('user_id');
    }
}
