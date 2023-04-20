<?php
namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class FormObjectModel extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = "form_objects";
    protected $fillable = ['name','slug','content','is_published','sort','form_object_types_id'];
    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean'
    ];



    public function form_object_types(){
        return $this->belongsTo(FormObjectType::class);
    }

}
