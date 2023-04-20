<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['name','key'];




    public function sidebar(){
        return $this->hasOne(Sidebar::class,'id','id');
    }


}
