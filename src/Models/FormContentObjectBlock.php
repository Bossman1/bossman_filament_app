<?php

namespace BossmanFilamentApp\Models;


use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class FormContentObjectBlock extends Model
{
    protected $fillable = ['form_content_object_id','form_content_object_child_id'];

}
