<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Menu extends Model implements TranslatableContract
{
    use Translatable, HasFactory;
    public $translatedAttributes = [
        'title',
        'desc',
    ];

    protected $fillable = [
        'price',
        'menu_image',
        'category_id'
    ];
}
