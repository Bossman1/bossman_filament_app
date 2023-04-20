<?php

namespace BossmanFilamentApp\Models;


use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @method static widgetArchives($query, $subMonth)
 * @method static widgetRelatedProducts($count = 10, $inRandomOrder = true, $charagterLimit = 2)
 * @method static widgetCalendarArchives()
 */
class FormContentObject extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'sort', 'content', 'is_published', 'form_content_object_id', 'category_id', 'show_in_menu', 'template_id', 'relation_view_id','send_email','collect_data','emails'];


    private $paginate = 2;

    protected $casts = [
        'is_published' => 'boolean',
        'send_email' => 'boolean',
        'collect_data' => 'boolean',
        'show_in_menu' => 'boolean',
        'emails' => 'array',
        'content' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


//    protected static function boot()
//    {
//        parent::boot();
//        static::created(function ($contentObject) {
//            $contentObject->slug = $contentObject->generateSlugCreate($contentObject->name);
//            $contentObject->save();
//        });
//
//
//    }

    private $errors = [];

    private function setErrors($message)
    {
        return $this->errors[] = $message;
    }

    private function getErrors()
    {
        $obj = [
            'count' => (int)count($this->errors),
            'errors' => (object)$this->errors
        ];
        return (object)$obj;
    }


    public function related_form()
    {
        return $this->belongsToMany(
            FormContentObject::class,
            'content_objects',
            'form_content_object_id',
            'id'

        );
    }


    public function formObject(){
         if(isset($this->content[0]) &&  count($this->content[0]) == 1){
             $modelFormObject = FormObjectModel::where('id',$this->content[0]['form_objects_id'])->first();
             dd($modelFormObject);
             return $this->content[0]['form_objects_id'];
         }
    }

    private function generateSlugCreate($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    public function objects()
    {
        return $this->belongsTo(FormObjectModel::class);
    }


    public function children()
    {
        return $this->hasmany(self::class, 'form_content_object_id')->orderBy('sort');
    }

    public function menuChildren()
    {
        return $this->children()
            ->whereNotNull('show_in_menu')
            ->where('show_in_menu', '!=', 0)
            ->published();
    }


    public function parent()
    {
        return $this->belongsTo(self::class, 'form_content_object_id');
    }

    public function allParents()
    {
        return $this->parent()->with('allParents');
    }

    public function form_custom_page()
    {
        return $this->hasOne(FormCustomPage::class);
    }

    public function field($value)
    {
        return isset($this->form_custom_page->content[$value]) ? $this->form_custom_page->content[$value]['value'] : '';
    }

    public function formField($identifier = null)
    {
        $object = collect($this->form_custom_page->content)->map(function ($obj, $key) use ($identifier) {
            if ($identifier) {
                if ($key == $identifier) {
                    return (object)$obj;
                }
            } else {
                return (object)$obj;
            }

        });

        if ($identifier) {
            return (object)$object[$identifier];
        }
        return $object;
    }


    public function getName()
    {
        if (isset(optional($this->form_custom_page)->content)) {
            return $this->form_custom_page->content[key($this->form_custom_page->content)] ? $this->form_custom_page->content[key($this->form_custom_page->content)]['value'] : '';
        }
    }


    public static function setIdentifier(string $value, string $label): string
    {
        return Str::limit(md5($value), 12, '') . '|' . Str::slug($label, '_');

    }

    public function scopePublished($query, $status = 1)
    {
        return $query->where('is_published', $status);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public static function scopeFetchBySlug(Builder $builder, $sting)
    {
        $query = $builder->with('form_custom_page', 'children', 'relation_blocks', 'parent', '')
            ->orderBy('sort')
            ->where('slug', $sting)
            ->published();

        if ($query) {
            return $query->first();
        }
        return null;

    }

    /**
     * @param Builder $builder
     * @param $slug
     * @param $identifier
     * @return string
     */
    public function scopeRelation(Builder $builder, $slug, $identifier = null)
    {
        $getRelations = $builder->with('relation_blocks', 'children', 'parent')->whereHas('relation_blocks')->orderBy('sort')->where('slug', $this->slug)->first();

        if ($getRelations) {
            $findRelation = $getRelations->relation_blocks->where('slug', $slug)->first();
            if ($identifier) {
                return $findRelation->field($identifier);
            }
            $related_block_with_children = $getRelations->relation_blocks->where('slug', $slug)->first();
            return $related_block_with_children->children ?? '';
        }
        return '';
    }

    public function isRequired($form)
    {
        $required = false;
        $modelFormObject = FormObjectModel::where('id', $form->form_object_id)->first();
        if($modelFormObject){
            foreach ($modelFormObject->content as $formObject) {
                if ($formObject['title'] == $form->label) {
                    if (isset($formObject['field_options'])) {
                        foreach ($formObject['field_options'] as $field_option) {
                            if ($field_option == 'required') {
                                $required = true;
                            }
                        }
                    }
                }

            }
        }
        return $required;
    }




}
