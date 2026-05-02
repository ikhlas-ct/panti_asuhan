<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role'   => 'string',
        'status' => 'string',
    ];

    // ── Profile per role ─────────────────────────────────────

    // admin_dinsos → pegawai (FK: pegawai.id_user)
    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'id_user');
    }

    // admin_panti → pengurus (FK: pengurus.user_id)
    public function pengurus(): HasOne
    {
        return $this->hasOne(Pengurus::class, 'user_id');
    }

    // donatur → donatur (FK: donatur.user_id)
    public function donatur(): HasOne
    {
        return $this->hasOne(Donatur::class, 'user_id');
    }

    // ── Aktivitas ─────────────────────────────────────────────

    // Konten yang dibuat user ini
    public function konten(): HasMany
    {
        return $this->hasMany(Konten::class, 'id_user');
    }

    // Donasi yang dikonfirmasi oleh user ini
    public function donasiDikonfirmasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'dikonfirmasi_oleh');
    }

    // ── Helper role ───────────────────────────────────────────

    public function isAdminDinsos(): bool
    {
        return $this->role === 'admin_dinsos';
    }

    public function isAdminPanti(): bool
    {
        return $this->role === 'admin_panti';
    }

    public function isDonatur(): bool
    {
        return $this->role === 'donatur';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
