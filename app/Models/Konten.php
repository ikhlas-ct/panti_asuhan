<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konten extends Model
{
    protected $table = 'konten';
    protected $primaryKey = 'id_konten';

    protected $fillable = [
        'judul',
        'isi',
        'ringkasan',
        'id_user',
        'slug',
        'tanggal_publikasi',
        'jenis_konten',
        'id_kategori',
        'gambar',
        'viewer',
        'panti_asuhan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
        'jumlah_peserta',
        'penanggung_jawab',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'tanggal_mulai'     => 'date',
        'tanggal_selesai'   => 'date',
        'jenis_konten'      => 'string',
        'status'            => 'string',
        'viewer'            => 'integer',
        'jumlah_peserta'    => 'integer',
    ];

    // User yang membuat konten ini
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Kategori konten
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Panti terkait (hanya untuk jenis_konten = kegiatan)
    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeArtikel($query)
    {
        return $query->where('jenis_konten', 'artikel');
    }

    public function scopeKegiatan($query)
    {
        return $query->where('jenis_konten', 'kegiatan');
    }

    public function scopeBerita($query)
    {
        return $query->where('jenis_konten', 'berita');
    }

    public function incrementViewer(): void
    {
        $this->increment('viewer');
    }
}
