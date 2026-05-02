<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'panti_asuhan_id',
        'jenis',
        'kategori',
        'keterangan',
        'nominal',
        'tanggal',
        'donasi_id',
        'bukti',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
        'jenis'   => 'string',
    ];

    public function pantiAsuhan(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_asuhan_id');
    }

    // Donasi asal transaksi ini (nullable — tidak semua transaksi dari donasi)
    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class, 'donasi_id');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    // ── Kalkulasi saldo ──────────────────────────────────────

    public static function totalPemasukan(int $pantiId): float
    {
        return static::where('panti_asuhan_id', $pantiId)
            ->where('jenis', 'pemasukan')
            ->sum('nominal');
    }

    public static function totalPengeluaran(int $pantiId): float
    {
        return static::where('panti_asuhan_id', $pantiId)
            ->where('jenis', 'pengeluaran')
            ->sum('nominal');
    }

    public static function saldo(int $pantiId): float
    {
        return static::totalPemasukan($pantiId) - static::totalPengeluaran($pantiId);
    }
}
