<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'nama',
        'alamat',
        'nohp',
        'email',
        'deskripsi',
        'instagram',
        'twitter',
        'facebook',
        'posisi',
        'foto_profil',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function konten()
    {
        return $this->hasMany(Konten::class, 'id_user', 'id_user');
    }
    public function websiteSetting()
    {
        return $this->hasOne(WebsiteSetting::class, 'karyawan_id', 'id_pegawai');
    }
}
