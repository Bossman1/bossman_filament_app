<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectTypeOption extends Model
{

    protected $fillable = ['name','key','object_types_id'];



    public function getObjectTypes(){
        return $this->belongsTo(ObjectType::class);
    }
}
