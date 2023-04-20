<?php
namespace BossmanFilamentApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ObjectModel extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = "objects";
    protected $fillable = ['name','slug','content','is_published','sort','object_types_id'];
    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean'
    ];



    public function object_types(){
        return $this->belongsTo(ObjectType::class);
    }

}
