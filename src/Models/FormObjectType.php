<?php
namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FormObjectType extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = "form_object_types";
    protected $fillable = ['name','key','is_published','sort'];


    const FIELD_OPTIONS = [
        'required' => 'Required',

    ];

//    public function getOptions(){
//        return $this->belongsTo(ObjectTypeOption::class,'id','object_types_id');
//    }
}
