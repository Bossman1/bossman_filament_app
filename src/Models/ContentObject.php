<?php

namespace BossmanFilamentApp\Models;


use Filament\Resources\Form;
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
class ContentObject extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'sort', 'content', 'is_published', 'content_object_id', 'category_id', 'show_in_menu', 'template_id', 'relation_view_id','form_content_object_id'];

protected    $foreignKey = 'ddddd';
    private $paginate = 2;

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'content' => 'json',
        'created_at' => 'date',
        'updated_at' => 'date'
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


    public function relation_blocks()
    {
        return $this->belongsToMany(
            ContentObject::class,
            'content_object_blocks',
            'content_object_id',
            'content_object_child_id',
            'id',
            'id'
        )
            ->withPivot('content_object_id')
            ->orderBy('content_objects.sort')
            ->with('custom_page');
    }


    public function objects()
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function getForm()
    {
        return $this->belongsTo(FormContentObject::class,'form_content_object_id','id');
    }


    public function children()
    {
        return $this->hasmany(self::class, 'content_object_id')->orderBy('sort');
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
        return $this->belongsTo(self::class, 'content_object_id');
    }

    public function allParents()
    {
        return $this->parent()->with('allParents');
    }

    public function custom_page()
    {
        return $this->hasOne(CustomPage::class);
    }

    public function field($value)
    {
        return isset($this->custom_page->content[$value]) ? $this->custom_page->content[$value]['value'] : '';
    }

    public function formField($identifier = null)
    {
        $object = collect($this->custom_page->content)->map(function ($obj, $key) use ($identifier) {
            if($identifier){
                if($key == $identifier){
                    return (object)$obj;
                }
            }else{
                return (object)$obj;
            }

        });

        if($identifier){
            return  (object)$object[$identifier];
        }
        return  $object;
    }


    public function getName()
    {
        if (isset(optional($this->custom_page)->content)) {
            return $this->custom_page->content[key($this->custom_page->content)] ? $this->custom_page->content[key($this->custom_page->content)]['value'] : '';
        }
    }


    public static function setIdentifier(string $value): string
    {
        return Str::limit(md5($value), 12, '');

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
        $query = $builder->with('custom_page', 'children', 'relation_blocks', 'parent','getForm')
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


    public function scopeWidgetRelatedProducts($query, $count = 10, $inRandomOrder = true, $charagterLimit = 2)
    {


        if (!isset($this->parent->id)) {
            return $query;
        }
        $keywords = explode(' ', strtolower($this->name));

        $filteredKeywords = [];
        array_walk($keywords, function ($v, $k) use (&$filteredKeywords, $charagterLimit) {
            if (strlen(trim($v)) >= $charagterLimit) {
                $filteredKeywords[$k] = $v;
            }
        });

        $modelCustomPages = CustomPage::with('content_object.children.custom_page.content_object', 'content_object.parent')
            ->where('content_object_id', $this->parent->id)->first();
        $content_object_ids = [];
        foreach ($modelCustomPages->content_object->children as $child) {
            if ($child->slug != $this->slug) {
                foreach ($child->custom_page->content as $custom_page) {
                    $objects_custom_page = (object)$custom_page;
                    foreach ($filteredKeywords as $keyword) {
                        if (str_contains($objects_custom_page->value, $keyword)) {
                            $content_object_ids[] = $child->id;
                        }
                    }
                }
            }
        }
        $modelMatchedContentObjects = ContentObject::whereIn('id', $content_object_ids);
        if ($inRandomOrder) {
            $modelMatchedContentObjects->inRandomOrder();
        }
        return $modelMatchedContentObjects->take($count);
    }


    /**
     * @param $query
     * @param $subMonth
     * @return array
     */
    public function scopeWidgetArchives($query, $subMonth = 12): object
    {


        $parent_key = '';
        $archiveDates = collect();

        if ($this->template_id == null) {
            $this->setErrors('View file is not set for Parent object');
        }
        $template = Template::select('slug')->where('id', $this->template_id)->first();

        for ($i = 0; $subMonth > $i; $i++) {
            $start_date = Carbon::now()->startOfMonth()->subMonth($i)->format('F Y');
            $start_year = Carbon::createFromDate($start_date)->format('Y');
            $start_month = Carbon::createFromDate($start_date)->format('m');
            $modelChildren = ($this->children) ? $this->children()->whereYear('created_at', $start_year)->whereMonth('created_at', $start_month)->get() : collect();
            $modelParent = ($this->parent) ? $this->parent->children()->whereYear('created_at', $start_year)->whereMonth('created_at', $start_month)->get() : collect();

            if ($modelChildren->isNotEmpty() || $modelParent->isNotEmpty()) {
                if ($modelChildren->isNotEmpty()) {
                    $model = $modelChildren;
                    if ($this->content_object_id == null) {
                        $parent_key = $this->slug;
                    } else {
                        $firstParent = ContentObject::select('slug')->where('id', $this->content_object_id)->first();
                        $parent_key = $firstParent->slug;
                    }
                }
                if ($modelParent->isNotEmpty()) {
                    $model = $modelParent;
                    $firstParent = ContentObject::select('slug')->where('id', $this->content_object_id)->first();
                    $parent_key = $firstParent->slug;
                }

                if (!$this->getErrors()->count) {
                    $archiveDates[] = (object)[
                        'label' => $start_date,
                        'model' => $model,
                        'view' => $template->slug,
                        'to' => $start_month . '-' . $start_year,
                        'parent_key' => $parent_key,
                    ];
                }


            }
        }
        return $this->getErrors()->count ? $this->getErrors() : $archiveDates;
    }


/////////////////////////////////////////////////////////    FOR CALENDAR

    public function scopeWidgetCalendarArchives()
    {
        $time = time();
        $object = [];
        $model = null;

        if ($this->template_id == null) {
            $this->setErrors('View file is not set for Parent object');
        }
        $template = Template::select('slug')->where('id', $this->template_id)->first();


        $numDay = date('d', $time); //03
        $numMonth = date('m', $time);
        $strMonth = date('F', $time);
        $numYear = date('Y', $time);
        $firstDay = mktime(0, 0, 0, $numMonth, 0, $numYear);
        $daysInMonth = cal_days_in_month(0, $numMonth, $numYear);
        $dayOfWeek = date('w', $firstDay);
        $start_date = Carbon::now()->startOfMonth()->format('F Y');
        $start_year = Carbon::createFromDate($start_date)->format('Y');
        $start_month = Carbon::createFromDate($start_date)->format('m');
        $modelChildren = ($this->children) ? $this->children()->whereYear('created_at', $start_year)->whereMonth('created_at', $start_month)->get() : collect();
        $modelParent = ($this->parent) ? $this->parent->children()->whereYear('created_at', $start_year)->whereMonth('created_at', $start_month)->get() : collect();
        $parent_key = '';
        if ($modelChildren->isNotEmpty()) {
            $model = $modelChildren;
            if ($this->content_object_id == null) {
                $parent_key = $this->slug;
            } else {
                $firstParent = ContentObject::select('slug')->where('id', $this->content_object_id)->first();
                $parent_key = $firstParent->slug;
            }
        }
        if ($modelParent->isNotEmpty()) {
            $model = $modelParent;
            $firstParent = ContentObject::select('slug')->where('id', $this->content_object_id)->first();
            $parent_key = $firstParent->slug;
        }


        for ($i = 1; $i <= $daysInMonth; $i++) {
            $ifRecordExist = false;
            $today = false;
            if (strlen($i) > 1) {
                $dig = $i;
            } else {
                $dig = ('0' . $i);

            }

            if ($model) {
                $ifRecordExist = $model->toQuery()->whereDay('created_at', $dig);
            }

            if ($i == $numDay) {
                $today = true;
            }

            if ($ifRecordExist && $ifRecordExist->count()) {

                $object['dates'][] = (object)[
                    'label' => $i,
                    'model' => $model,
                    'record' => true,
                    'parent_key' => $parent_key,
                    'view' => $template->slug,
                    'to' => $dig . '-' . $start_month . '-' . $start_year,
                    'today' => $today
                ];
            } else {
                $object['dates'][] = (object)[
                    'label' => $i,
                    'record' => false,
                    'today' => $today
                ];
            }

        }
        $object['dayOfWeek'] = (int)$dayOfWeek;
        $object['currentMonth'] = $strMonth;
        $object['currentDay'] = $numDay;
        $object['weekDay'] = date('l', $time);


        return (object)$object;
    }


}
