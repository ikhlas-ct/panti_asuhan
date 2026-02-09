<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'icon',
        'price',
        'title',
        'gambar',
        'description',
        'order',
    ];

    public function steps()
    {
        return $this->hasMany(ServiceStep::class)->orderBy('step_number');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'icon', 'id_kategori');
    }

    public function getIconClassAttribute()
    {
        return $this->kategori ? $this->kategori->icon : '';
    }
}
