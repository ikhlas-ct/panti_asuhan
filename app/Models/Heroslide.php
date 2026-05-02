<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $table = 'hero_slides';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'title',
        'description',
        'button_text',
        'button_link',
    ];
}
