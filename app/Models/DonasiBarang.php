<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonasiBarang extends Model
{
    protected $table = 'donasi_barang';

    protected $fillable = [
        'donasi_id',
        'nama_barang',
        'jumlah_barang',
        'satuan_barang',
        'foto_barang',
        'keterangan',
    ];

    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class, 'donasi_id');
    }
}
