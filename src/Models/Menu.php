<?php

namespace BossmanFilamentApp\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;
   protected $fillable = ['name', 'key', 'content'];
    public $translatable = ['content'];


    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public static function scopeFetchByKey(Builder $builder, $sting){
        return $builder->where('key',$sting)->first();
    }


    private function getMenu($array){
        $items = [];
        foreach ($this->content as $key =>  $menuItems) {
            if(in_array($key, $array)){
                $items[$key] =  strtolower($menuItems['menu_list']);
            }
        }

        return $items;
    }

    public function getMenuByArray($array){
        $items =  $this->getMenu($array);
        $newArray = collect($items)->map(function ($arr){
            return  $arr;
        });
        return $newArray->toArray();
    }

    public function getMenuByObject($array){
        $items =  $this->getMenu($array);
        $newArray = collect($items)->map(function ($arr){
            return strtolower($arr);
        });
        return $newArray;
    }

}
