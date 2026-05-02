<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'slogan',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_youtube',
        'title_pengantar',
        'paragraf_pengantar',
        'gambar_pengantar',
        'about_us',
        'why_choose_us',
    ];

    // Ambil satu-satunya record settings
    public static function getSetting(): ?self
    {
        return static::first();
    }
}
