<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'donatur_id',
        'panti_asuhan_id',
        'jenis_donasi',
        'metode',
        'nominal',
        'bukti_transfer',
        'deskripsi_barang',
        'nama_barang',
        'jumlah_barang',
        'satuan_barang',
        'foto_barang',
        'tanggal_kunjungan',
        'tanggal_donasi',
        'catatan',
        'status',
        'alasan_tolak',
        'dikonfirmasi_oleh',
        'dikonfirmasi_at',
    ];

    protected $casts = [
        'nominal'           => 'decimal:2',
        'jumlah_barang'     => 'integer',
        'tanggal_kunjungan' => 'date',
        'tanggal_donasi'    => 'date',
        'dikonfirmasi_at'   => 'datetime',
        'jenis_donasi'      => 'string',
        'metode'            => 'string',
        'status'            => 'string',
    ];

    // Donatur yang melakukan donasi ini
    public function donatur(): BelongsTo
    {
        return $this->belongsTo(Donatur::class, 'donatur_id');
    }

    // Panti penerima donasi
    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }

    // User (pengurus/admin) yang mengkonfirmasi donasi ini
    public function dikonfirmasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    // Pencatatan keuangan dari donasi ini
    public function keuangan(): HasOne
    {
        return $this->hasOne(Keuangan::class, 'donasi_id');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeUang($query)
    {
        return $query->where('jenis_donasi', 'uang');
    }

    public function scopeBarang($query)
    {
        return $query->where('jenis_donasi', 'barang');
    }

    public function sudahDikonfirmasi(): bool
    {
        return $this->status !== 'pending';
    }
}
