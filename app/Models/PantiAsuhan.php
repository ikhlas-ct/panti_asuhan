<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PantiAsuhan extends Model
{
    protected $table = 'panti_asuhan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_panti',
        'alamat',
        'kelurahan',
        'kecamatan',
        'no_telp',
        'nama_kontak',
        'email',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Pengurus panti (admin_panti) — bisa lebih dari satu
    public function pengurus(): HasMany
    {
        return $this->hasMany(Pengurus::class, 'panti_asuhan_id');
    }

    // Foto-foto panti
    public function fotoPanti(): HasMany
    {
        return $this->hasMany(FotoPanti::class, 'panti_asuhan_id');
    }

    // Anak asuh di panti ini
    public function anakAsuh(): HasMany
    {
        return $this->hasMany(AnakAsuh::class, 'panti_asuhan_id');
    }

    // Donasi yang diterima panti ini
    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'panti_asuhan_id');
    }

    // Catatan keuangan panti ini
    public function keuangan(): HasMany
    {
        return $this->hasMany(Keuangan::class, 'panti_asuhan_id');
    }

    // Konten/kegiatan milik panti ini
    public function konten(): HasMany
    {
        return $this->hasMany(Konten::class, 'panti_asuhan_id');
    }



    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function getJumlahAnakAktifAttribute(): int
    {
        return $this->anakAsuh()->where('status', 'aktif')->count();
    }
}
