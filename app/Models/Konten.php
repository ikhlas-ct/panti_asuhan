<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    protected $table = 'konten';
    protected $primaryKey = 'id_konten';

    protected $fillable = [
        'judul',
        'ringkasan',
        'isi',
        'duration',
        'price',
        'badge',
        'id_user',
        'slug',
        'tanggal_publikasi',
        'jenis_konten',
        'gambar',
        'viewer',
        'id_kategori',
        'status',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
