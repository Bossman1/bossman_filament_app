<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sidebar extends Model
{
   protected $fillable = ['name','key','sort'];


   public function widgets(): BelongsToMany
   {
       return $this->belongsToMany(Widget::class);
   }
}
