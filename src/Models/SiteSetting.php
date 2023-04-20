<?php

namespace BossmanFilamentApp\Models;


use Filament\Resources\Form;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'cms_settings';
    protected $fillable = ['template'];



}
