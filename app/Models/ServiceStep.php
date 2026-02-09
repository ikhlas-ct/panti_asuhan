<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStep extends Model
{
    use HasFactory;

    protected $table = 'service_step';
    protected $primaryKey = 'id';

    protected $fillable = [
        'service_id',
        'step_number',
        'title',
        'icon',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
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
