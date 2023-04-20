<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Template extends Model
{

   protected $fillable = ['name', 'slug'];



    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}
