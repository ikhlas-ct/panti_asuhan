<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    /** @use HasFactory<\Database\Factories\WebsiteSettingFactory> */
    use HasFactory;

    protected $table = 'website_settings';
    protected $primaryKey = 'id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'slogan',
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
        'created_at',
        'updated_at'
    ];

    public static function getInstance()
    {
        return self::firstOrCreate([]);
    }
}
