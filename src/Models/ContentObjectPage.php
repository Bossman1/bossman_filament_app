<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentObjectPage extends Model
{

    protected $fillable = ['name','slug','content_objects_id','sort','is_published'];
    protected $casts = [
       'is_published'  => 'boolean'
    ];

    public function content_objects(){
        return $this->belongsTo(ContentObject::class);
    }

}
