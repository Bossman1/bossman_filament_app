<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Language extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['name', 'key', 'image','sort','is_default'];
    protected $casts = [
      'is_default' => 'boolean'
    ];
}
