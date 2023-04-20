<?php

namespace BossmanFilamentApp\Resources\ContentObjectResource\Pages;


use App\Traits\CategoryTrait;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\BelongsToLivewire;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\ContentObjectPage;
use BossmanFilamentApp\Models\CustomPage;
use BossmanFilamentApp\Models\Language;
use BossmanFilamentApp\Models\Menu;
use BossmanFilamentApp\Models\ObjectModel;
use BossmanFilamentApp\Models\ObjectType;
use BossmanFilamentApp\Pages\CustomActions\CustomLangSwitcher;
use BossmanFilamentApp\Resources\ContentObjectResource;
use SebastianBergmann\Template\Template;

class EditPage extends EditRecord
{
    use Translatable, BelongsToLivewire, CategoryTrait;

    public ?string $activeLocale = 'ka';


    public string|array|null $content_field;

    protected static string $resource = ContentObjectResource::class;


//    protected static string $view = 'views::templates.default.content.custom_edit';


    public function __construct($id = null)
    {

        parent::__construct($id);

        $this->activeLocale = array_values(self::getTranslatableLocales())[0];
    }

    public static function getTranslatableLocales(): array
    {
        $modelLanguages = Language::query()->orderBy('sort')->pluck('key', 'name');
        if ($modelLanguages) {
            return $modelLanguages->toArray();
        }
        return [];
    }


    protected function getActions(): array
    {
        return [
            CustomLangSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }


    protected function getFormSchema($record = null): array
    {

//         $test = ContentObject::with('relation_blocks')->where('id',169)->first();
//         dd($test);

        $modelContentObject = ContentObject::select('id', 'name', 'content_object_id')->with('children', 'parent')->whereNull('content_object_id')->get();
        $content_objects = self::initMenuChildrenTree($modelContentObject, '-');// build multilevel menu tree
        $defaultSchema = [
            Section::make('Object Properties')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label(__('Content Object Name'))
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        })->required(),
                    TextInput::make('slug')->required()->unique()->label('Content object slug'),
                    Select::make('content_object_id')->options(function ($record) use ($content_objects) {
                        return $content_objects;
                    })
                        ->label(__('Parent content objects'))
                        ->columnSpan(2)
                        ->placeholder(__('Main content object')),
                ]),
                Fieldset::make('Objects')->schema([

                    Repeater::make('content')
                        ->disableLabel()
                        ->schema([
                            Select::make('objects_id')
                                ->options(ObjectModel::query()->pluck('name', 'id'))
                                ->required()
                                ->label('Object Name')
                        ])
                        ->columns(1)
                        ->createItemButtonLabel('Add new Object')
                        ->collapsible()
                        ->cloneable()
                        ->orderable()
                        ->grid(1),


                ])
                    ->label('Objects')
                    ->columns(1),
                 Select::make('layout_id')->options(function ($record){
                     $layout = \BossmanFilamentApp\Models\Layout::query()->get();
                     $layoutArray = [];
                     foreach ($layout as $lay) {
                         $layoutArray[$lay->id] = $lay->name . ' ('.$lay->slug.')';
                     }
                     return $layoutArray;
                 })
                ->label(__('Layout')),
                 Select::make('template_id')->options(function ($record){
                     $template = \BossmanFilamentApp\Models\Template::query()->get();
                     $templateArray = [];
                     foreach ($template as $tmpl) {
                         $templateArray[$tmpl->id] = $tmpl->name . ' ('.$tmpl->slug.')';
                     }
                     return $templateArray;
                 })
                     ->hint('File name for single and list view -- Path (Single view:  pages/content_object/ |  List view:  pages/content_object/list_view/)')
                     ->label(__("View")),
                 Select::make('relation_view_id')->options(function ($record){
                     $template = \BossmanFilamentApp\Models\RelationView::query()->get();
                     $templateArray = [];
                     foreach ($template as $tmpl) {
                         $templateArray[$tmpl->id] = $tmpl->name . ' ('.$tmpl->slug.')';
                     }
                     return $templateArray;
                 })
                     ->label(__("Relation view")),
                Select::make('sidebar_id')->options(function ($record){
                    $sidebar_view = \BossmanFilamentApp\Models\Sidebar::query()->get();
                    $sidebar_viewArray = [];
                    foreach ($sidebar_view as $sidebar) {
                        $sidebar_viewArray[$sidebar->id] = $sidebar->name . ' ('.$sidebar->key.')';
                    }
                    return $sidebar_viewArray;
                })
                    ->label(__("Sidebar")),

                Select::make('form_content_object_id')
                    ->relationship('getForm', 'name')
                    ->label(__("Have a Form")),
                 Toggle::make('is_published')->inline(true)->default(true)->label(__('Is published')),
                 Toggle::make('show_in_menu')->inline(true)->label(__('Show In Menu')),
            ])->collapsible()
                ->collapsed(),
        ];


        $arrayObjects = $this->record->content;

        $objectsIds = [];
        foreach ($arrayObjects as $key => $arrayObject) {
            foreach ($arrayObject as $object) {
                if ($object == null) {
                    continue;
                }
                $objectsIds[] = $object;
            }
        }

        if (!empty($objectsIds)) {
            $ids_ordered = implode(',', $objectsIds);

            $modelObjects = ObjectModel::query()->with('object_types')->whereIn('id', $objectsIds)
                ->orderByRaw("FIELD(id, $ids_ordered)")
                ->get(); //todo NICK make same as in edit page , issue if choose with similar content objects

            if (!$modelObjects) {
                abort(404, '$modelObjects not found');
            }
            $objectTypes = [];

            foreach ($modelObjects as $modelObject) {
                foreach ($modelObject->content as $k => $content) {
                    $objectTypes[] = [
                        'object_types_id' => $content['object_types_id'],
                        'field_options' => $content['field_options'],
                        'title' => $content['title'],
                        'object_id' => $modelObject->id,
                        'single_menu' => $content['single_menu'] ?? '',
                        'multiple_menu' => $content['multiple_menu'] ?? [],
                    ];

                }
            }

            $objectTypeKeys = [];
            foreach ($objectTypes as $key => $objectType) {
                $objectTypeModel = ObjectType::query()->select('key')->where('id', $objectType['object_types_id'])->first();
                $objectTypeKeys[] = [
                    'object_type_key' => $objectTypeModel->key,
                    'field_options' => $objectType['field_options'],
                    'object_type_id' => $objectType['object_types_id'],
                    'object_id' => $objectType['object_id'],
                    'object_title' => $objectType['title'],
                    'single_menu' => $objectType['single_menu'] ?? '',
                    'multiple_menu' => $objectType['multiple_menu'] ?? [],
                ];

            }

            $contentObjects = [];

            if ($this->record->custom_page && !empty($this->record->custom_page->getTranslation('content', $this->activeLocale))) { // edit

                foreach ($this->record->custom_page->getTranslation('content', $this->activeLocale) as $identifier => $content) {

                    $this->content_field = $content['value'];
                    $menuArray = [];


                    if ($content['object_type_key'] == 'dropdown-select-single') { // get single select
                        $modelObject = ObjectModel::where('id', $content['object_id'])->first();


                        $menu_id = '';
                        foreach ($modelObject->content as $objectModel) {
                            if ($objectModel['single_menu'] != null) {
                                $menu_id = $objectModel['single_menu'];
                            } else {
                                continue;
                            }
                        }
                        $modelMenu = Menu::select('content')->where('id', $menu_id)->first();

                        if($modelMenu){
                            foreach ($modelMenu->getTranslation('content', $this->activeLocale) as $menu) {
                                $menuArray[] = $menu['menu_list'];
                            }
                        }

                    }



                    if ($content['object_type_key'] == 'dropdown-select-multiple') { // get multiple  select
                        $modelObject = ObjectModel::where('id', $content['object_id'])->first();
                        $modelMenu = Menu::select('content')->where('id', $modelObject->content[0]['multiple_menu'])->first();
                        foreach ($modelMenu->getTranslation('content', $this->activeLocale) as $menu) {
                            $menuArray[] = $menu['menu_list'];
                        }
                    }


                    $modelObjectType = ObjectType::select('key')->where('id', $content['object_type_id'])->first();
                    $modelObject = ObjectModel::select('content')->where('id', $content['object_id'])->first();
                    $required = false;
                    $multiple = false;

                    foreach ($modelObject->content as $object) {
                        if (is_array($object['field_options'])) {
                            foreach ($object['field_options'] as $field_option) {
                                if ($content['object_type_id'] == $object['object_types_id'] && $field_option == 'required') {
                                    $required = true;
                                }
                                if ($content['object_type_id'] == $object['object_types_id'] && $field_option == 'multiple') {
                                    $multiple = true;
                                }
                            }
                        }
                    }

                    $identifierText = __('Identifier');;
                    $modelLanguages = Language::query()->select('image')->where('key', $this->activeLocale)->first();
                    $langImage = "<img src='/storage/" . $modelLanguages->image . "' style='width: 24px'>";

                    $matchingFields = [

                        'input-text' => TextInput::make('input-text__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {

                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('input-text__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('input-text__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->required($required),

                        'textarea' => Textarea::make('textarea__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');

                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('textarea__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('textarea__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->required($required),

                        'dropdown-select-single' => Select::make('dropdown-select-single__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->options(function () use ($menuArray) {
                                return $menuArray;
                            })
                            ->formatStateUsing(fn() => $content['value'])
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('dropdown-select-single__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->required($required),

                        'dropdown-select-multiple' => Select::make('dropdown-select-multiple__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->options($menuArray)
                            ->formatStateUsing(fn() => $content['value'])
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('dropdown-select-multiple__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->multiple()
                            ->required($required),

                        'rich-text-editor' => RichEditor::make('rich-text-editor__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');

                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('rich-text-editor__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('rich-text-editor__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->required($required),

                        'gallery' => SpatieMediaLibraryFileUpload::make('gallery')
                            ->formatStateUsing(function ($state, $record) use ($content) {
                                return $state;
                            })
                            ->multiple($multiple)
                            ->enableReordering()
                            ->collection('gallery')
                            ->required($required)
                            ->responsiveImages()
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');

                            })
                            ->label($content['label']),
                        'color-picker' => ColorPicker::make('color-picker__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {

                                $get('color-picker__custom__' . $identifier);
                            })
                            ->required($required)
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('color-picker__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            }),
                        'date-picker' => DatePicker::make('date-picker__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {

                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('date-picker__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('date-picker__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            })
                            ->required($required),

                    ];

                    $contentObjects[] = $matchingFields[$modelObjectType->key] ?? '';

                }


            } else {//create

                foreach ($objectTypeKeys as $k => $objectTypeKey) {

                    $modelObjectType = ObjectType::query()->where('id', $objectTypeKey['object_type_id'])->first();

                    $required = false;
                    $multiple = false;
                    if (isset($objectTypeKey['field_options'])) {
                        foreach ($objectTypeKey['field_options'] as $field_option) {
                            if ($field_option == 'required') {
                                $required = true;
                            }
                            if ($field_option == 'multiple') {
                                $multiple = true;
                            }

                        }
                    }

                    $menuArray = [];
                    if ($objectTypeKey['object_type_key'] == 'dropdown-select-single' && $objectTypeKey['single_menu']) { // get single menu
                        $modelMenu = Menu::select('content')->where('id', $objectTypeKey['single_menu'])->first();
                        foreach ($modelMenu->getTranslation('content', $this->activeLocale) as $menu) {
                            $menuArray[] = $menu['menu_list'];
                        }
                    }

                    if ($objectTypeKey['object_type_key'] == 'dropdown-select-multiple' && $objectTypeKey['multiple_menu']) { // get multiple menu
                        $modelMenu = Menu::select('content')->where('id', $objectTypeKey['multiple_menu'])->first();
                        foreach ($modelMenu->getTranslation('content', $this->activeLocale) as $menu) {
                            $menuArray[] = $menu['menu_list'];
                        }
                    }


                    $encodeString = base64_encode('object_id:' . $objectTypeKey['object_id'] . ',object_type_id:' . $objectTypeKey['object_type_id'] . ',object_title_slug:' . Str::slug($objectTypeKey['object_title']) . ',label:' . $objectTypeKey['object_title'] . ',key:' . $k);
                    $identifier = ContentObject::setIdentifier($encodeString);
                    $matchingFields = [
                        'input-text' => TextInput::make('input-text__custom__' . $identifier)
                            ->label($objectTypeKey['object_title'])
                            ->required($required),
                        'textarea' => Textarea::make('textarea__custom__' . $identifier)
                            ->required($required)
                            ->label($objectTypeKey['object_title']),
                        'rich-text-editor' => RichEditor::make('rich-text-editor__custom__' . $identifier)
                            ->required($required)
                            ->label($objectTypeKey['object_title']),
                        'dropdown-select-single' => Select::make('dropdown-select-single__custom__' . $identifier)
                            ->required($required)
                            ->options($menuArray)
                            ->label($objectTypeKey['object_title']),
                        'dropdown-select-multiple' => Select::make('dropdown-select-multiple__custom__' . $identifier)
                            ->required($required)
                            ->options(function () use ($menuArray) {
                                return $menuArray;
                            })
                            ->multiple()
                            ->label($objectTypeKey['object_title']),
                        'gallery' => SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->multiple($multiple)
                            ->required($required)
                            ->enableReordering()
                            ->label($objectTypeKey['object_title']),
                        'color-picker' => ColorPicker::make('color-picker__custom__' . $identifier)
                            ->required($required)
                            ->label($objectTypeKey['object_title']),
                        'date-picker' => DatePicker::make('date-picker__custom__' . $identifier)
                            ->required($required)
                            ->label($objectTypeKey['object_title']),
                    ];

                    $contentObjects[] = $matchingFields[$objectTypeKey['object_type_key']] ?? '';
                }
            }
//            dd($contentObjects,$defaultSchema);
//            input-text__custom__d46c3373e600
            return [Card::make()->schema(array_merge(array_filter($contentObjects), $defaultSchema))];

        }
        return [Card::make()->schema(array_merge([], $defaultSchema))];

    }

    public function save(bool $shouldRedirect = true): void
    {

        $requestDataArray = $this->data;


///////////////////////////// SAVE CONTENT OBJECT ////////////////////////

        $contentObject = ContentObject::firstOrNew(['id' => $requestDataArray['id']]);
        $contentObject->name = $requestDataArray['name'];
        $slug = $requestDataArray['slug'];

        if ($contentObject::whereSlug($slug)->where('id','!=',$contentObject->id)->exists()) {

            $slug = $slug .'-'.$contentObject->id;
        }
        $contentObject->slug = $slug;
        $contentObject->content_object_id = $requestDataArray['content_object_id'];
        $contentObject->layout_id = ($requestDataArray['layout_id'] != '') ? $requestDataArray['layout_id'] : null;
        $contentObject->template_id = ($requestDataArray['template_id'] != '') ? $requestDataArray['template_id'] : null;
        $contentObject->relation_view_id = ($requestDataArray['relation_view_id'] != '') ? $requestDataArray['relation_view_id'] : null;
        $contentObject->show_in_menu = ($requestDataArray['show_in_menu'] != '') ? $requestDataArray['show_in_menu'] : null;
        $contentObject->sidebar_id = ($requestDataArray['sidebar_id'] != '') ? $requestDataArray['sidebar_id'] : null;
        $contentObject->form_content_object_id = ($requestDataArray['form_content_object_id'] != '') ? $requestDataArray['form_content_object_id'] : null;
        $contentObject->save(); // save content object
// ///////////////////////////// END SAVE CONTENT OBJECT ////////////////////////


///////////////////////////// GET CUSTOM FIELDS IDENTIFIERS FROM REQUEST ////////////////////////
        $contentObjectArray = [];
        foreach ($requestDataArray as $key => $requestData) {
            $customFields = explode('__custom__', $key);
            if (isset($customFields[1])) {
                $contentObjectArray[$customFields[1]]['value'] = $requestData;
            }
        }
// ///////////////////////////// END GET CUSTOM FIELDS IDENTIFIERS FROM REQUEST ////////////////////////


        $arrayContent = [];
        $key = 0;
        $getObjectTypeArrays = $this->getObjectTypeArrays($requestDataArray['id']);

        foreach ($getObjectTypeArrays['objectTypesArray'] as $k => $objectTypes) {
            foreach ($objectTypes as $objectType) {
                $modelObjectType = ObjectType::where('id', $objectType['object_types_id'])->first();
                if ($modelObjectType) {
                    $dataKey = base64_encode('object_id:' . $objectType['object_id'] . ',object_type_id:' . $modelObjectType->id . ',object_title_slug:' . Str::slug($objectType['object_type_label']) . ',label:' . $objectType['object_type_label'] . ',key:' . $key++);
                    $identifier = ContentObject::setIdentifier($dataKey);

                    $arrayContent[$this->activeLocale][$identifier] = [
                        'object_id' => $objectType['object_id'],
                        'object_type_id' => $modelObjectType->id,
                        'label' => $objectType['object_type_label'],
                        'object_type_key' => $modelObjectType->key,
                        'key' => $key
                    ];
                    $arrayContent[$this->activeLocale][$identifier]['value'] = $contentObjectArray[$identifier]['value'] ?? null;


                }
            }
        }


        $customPage = CustomPage::firstOrNew(['content_object_id' => $requestDataArray['id']]);
        $allTranslations = [];
        foreach (self::getTranslatableLocales() as $translatableLocale) {
            if (isset($arrayContent[$translatableLocale])) {
                $allTranslations[$translatableLocale] = $arrayContent[$translatableLocale];
            } else {
                $allTranslations[$translatableLocale] = $customPage->getTranslation('content', $translatableLocale);
            }
        }
        $customPage->setTranslations('content', $allTranslations);
        $customPage->save();


        if (isset($requestDataArray['gallery'])) { // gallery
            $yourModel = ContentObject::find($requestDataArray['id']);
            foreach ($requestDataArray['gallery'] as $galleryItems) {
                if (is_object($galleryItems)) {
                    $yourModel->addMedia($galleryItems)->toMediaCollection('gallery');
                }
            }
        }


        if ($customPage->save()) // save custom  page
        {
            Notification::make()
                ->success()
                ->title('Saved successfully')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Something is not working')
                ->send();
        }
        $this->callHook('afterSave');
    }

    public function afterSave()
    {
        return redirect()->to(route('filament.resources.content-objects.edit', $this->record->id));
    }

    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
            ->success()
            ->title('testttt')
            ->body('testttttt.');
    }


    protected function getRedirectUrl(): string
    {

        return $this->getResource()::getUrl('index');
    }


    private function getObjectTypeArrays($content_object_id)
    {
        $modelContentObject = ContentObject::select('id', 'name', 'slug', 'content')->where('id', $content_object_id)->first();
        $objectIds = [];
        foreach ($modelContentObject->content as $objects_id) {
            $objectIds[] = array_values($objects_id);
        }

        $objectTypesArray = [];
        foreach ($objectIds as $key => $objectId) {
            $modelObject = ObjectModel::select('id', 'content')->where('id', $objectId)->first();
            if ($modelObject) {
                foreach ($modelObject->content as $k => $object) {
                    $objectTypesArray[$key][$k] = ['object_id' => $modelObject->id, 'object_types_id' => $object['object_types_id'], 'object_type_label' => $object['title']];
                }
            }
        }
        return [
            'objectTypesArray' => $objectTypesArray,
            'modelContentObject' => $modelContentObject,
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool // if  show children in tab section
    {
        return false;
    }
}
