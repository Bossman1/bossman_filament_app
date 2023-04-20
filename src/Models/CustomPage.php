<?php

namespace BossmanFilamentApp\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CustomPage extends Model implements HasMedia
{

    use  InteractsWithMedia, HasTranslations;
    protected $fillable = ['content_object_id','content'];
    public $translatable = ['content'];
    protected $casts = [
        'content' => 'json'
    ];
    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function content_objects(){
        return $this->belongsTo(ContentObject::class);
    }


    public function content_object(){
        return $this->hasOne(ContentObject::class,'id','content_object_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished($query, $status = 1)
    {
        return $query->where('is_published', $status);
    }

}
