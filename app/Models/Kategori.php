<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function konten(): HasMany
    {
        return $this->hasMany(Konten::class, 'id_kategori', 'id_kategori');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}
