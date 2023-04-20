<?php

namespace BossmanFilamentApp\Resources\FormContentObjectResource\Pages;


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
use Filament\Forms\Components\TagsInput;
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
use BossmanFilamentApp\Models\FormContentObject;
use BossmanFilamentApp\Models\FormCustomPage;
use BossmanFilamentApp\Models\FormObjectModel;
use BossmanFilamentApp\Models\FormObjectType;
use BossmanFilamentApp\Models\Language;
use BossmanFilamentApp\Pages\CustomActions\CustomLangSwitcher;
use BossmanFilamentApp\Resources\FormContentObjectResource;

class EditPage extends EditRecord
{
    use Translatable, BelongsToLivewire, CategoryTrait;

    public ?string $activeLocale = 'ka';


    public string|array|null $content_field;

    protected static string $resource = FormContentObjectResource::class;


//    protected static string $view = 'views::templates.content.custom_edit';


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

        $modelContentObject = FormContentObject::select('id', 'name', 'form_content_object_id')->with('children', 'parent')->whereNull('form_content_object_id')->get();
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
//                    Select::make('form_content_object_id')->options(function ($record) use ($content_objects) {
//                        return $content_objects;
//                    })
//                        ->label(__('Parent content objects'))
//                        ->columnSpan(2)
//                        ->placeholder(__('Main content object')),
                ]),
//                Fieldset::make('Form Objects')->schema([
                    Repeater::make('content')
                        ->disableLabel()
                        ->schema([
                            Select::make('form_objects_id')
                                ->options(FormObjectModel::query()->pluck('name', 'id'))
                                ->required()
                                ->label(__('Form Object'))
                        ])
                        ->columns(1)
                        ->createItemButtonLabel('Add new Object')
                        ->collapsible()
                        ->orderable()
                        ->disableItemCreation(),
//                ])
//                    ->label('Objects')
//                    ->columns(1),
                TagsInput::make('emails')->label(__('Emails'))->placeholder(__('Emails')),
                Toggle::make('send_email')->inline(false)->default(true)->label(__('Send Emails')),
                Toggle::make('collect_data')->inline(false)->default(true)->label(__('Record data')),
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

            $modelObjects = FormObjectModel::query()->with('form_object_types')->whereIn('id', $objectsIds)
                ->orderByRaw("FIELD(id, $ids_ordered)")
                ->get(); //todo NICK make same as in edit page , issue if choose with similar content objects

            if (!$modelObjects) {
                abort(404, '$modelObjects not found');
            }
            $objectTypes = [];

            foreach ($modelObjects as $modelObject) {
                foreach ($modelObject->content as $k => $content) {
                    $objectTypes[] = [
                        'form_object_types_id' => $content['form_object_types_id'],
                        'field_options' => $content['field_options'],
                        'title' => $content['title'],
                        'form_object_id' => $modelObject->id,
                        'single_menu' => $content['single_menu'] ?? '',
                        'multiple_menu' => $content['multiple_menu'] ?? [],
                    ];

                }
            }

            $objectTypeKeys = [];
            foreach ($objectTypes as $key => $objectType) {
                $objectTypeModel = FormObjectType::query()->select('key')->where('id', $objectType['form_object_types_id'])->first();
                $objectTypeKeys[] = [
                    'object_type_key' => $objectTypeModel->key,
                    'field_options' => $objectType['field_options'],
                    'form_object_type_id' => $objectType['form_object_types_id'],
                    'form_object_id' => $objectType['form_object_id'],
                    'object_title' => $objectType['title'],
                    'single_menu' => $objectType['single_menu'] ?? '',
                    'multiple_menu' => $objectType['multiple_menu'] ?? [],
                ];

            }

            $contentObjects = [];

            if ($this->record->form_custom_page && !empty($this->record->form_custom_page->getTranslation('content', $this->activeLocale))) { // edit

                foreach ($this->record->form_custom_page->getTranslation('content', $this->activeLocale) as $identifier => $content) {

                    $this->content_field = $content['value'];
                    $modelObjectType = FormObjectType::select('key')->where('id', $content['form_object_type_id'])->first();
                    $modelObject = FormObjectModel::select('content')->where('id', $content['form_object_id'])->first();
                    $required = false;

                    foreach ($modelObject->content as $object) {
                        if (is_array($object['field_options'])) {
                            foreach ($object['field_options'] as $field_option) {
                                if ($content['form_object_type_id'] == $object['form_object_types_id'] && $field_option == 'required') {
                                    $required = true;
                                }
                            }
                        }
                    }

                    $identifierText = __('Identifier');;
                    $modelLanguages = Language::query()->select('image')->where('key', $this->activeLocale)->first();
                    $langImage = "<img src='/storage/" . $modelLanguages->image . "' style='width: 24px'>";


                    $matchingFields = [

                        'email' => TextInput::make('email__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {

                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('email__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('email__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            }),
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
                            }),
                        'number-input' => TextInput::make('number-input__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->numeric()
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {
                                $get('number-input__custom__' . $identifier);
                            })
                            ->label(function (Closure $get, $set) use ($identifier, $content) {
                                $set('number-input__custom__' . $identifier, $content['value']);
                                return $content['label'];
                            }),
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
                            }),
                        'color-picker' => ColorPicker::make('color-picker__custom__' . $identifier)
                            ->hint(function (Closure $set, $get) use ($identifierText, $identifier, $langImage) {
                                return new HtmlString('<span style="display: flex;"><strong>' . $identifierText . ':</strong> &nbsp;&nbsp;' . $identifier . '&nbsp;&nbsp; <strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage . '</span>');
                            })
                            ->afterStateHydrated(function (Closure $get, $set) use ($identifier) {

                                $get('color-picker__custom__' . $identifier);
                            })
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
                            }),

                    ];

                    $contentObjects[] = $matchingFields[$modelObjectType->key] ?? '';

                }


            } else {//create

                foreach ($objectTypeKeys as $k => $objectTypeKey) {

                    $required = false;
                    if (isset($objectTypeKey['field_options'])) {
                        foreach ($objectTypeKey['field_options'] as $field_option) {
                            if ($field_option == 'required') {
                                $required = true;
                            }

                        }
                    }


                    $encodeString = base64_encode('form_object_id:' . $objectTypeKey['form_object_id'] . ',form_object_type_id:' . $objectTypeKey['form_object_type_id'] . ',object_title_slug:' . Str::slug($objectTypeKey['object_title']) . ',label:' . $objectTypeKey['object_title'] . ',key:' . $k);
                    $identifier = FormContentObject::setIdentifier($encodeString, $objectTypeKey['object_title']);
                    $matchingFields = [
                        'input-text' => TextInput::make('input-text__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'number-input' => TextInput::make('number-input__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'email' => TextInput::make('email__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'textarea' => Textarea::make('textarea__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'rich-text-editor' => RichEditor::make('rich-text-editor__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'color-picker' => ColorPicker::make('color-picker__custom__' . $identifier)
                            ->label($objectTypeKey['object_title']),
                        'date-picker' => DatePicker::make('date-picker__custom__' . $identifier)
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

        $contentObject = FormContentObject::firstOrNew(['id' => $requestDataArray['id']]);
        $contentObject->name = $requestDataArray['name'];
        $slug = $requestDataArray['slug'];

        if ($contentObject::whereSlug($slug)->where('id', '!=', $contentObject->id)->exists()) {
            $slug = $slug . '-' . $contentObject->id;
        }
        $contentObject->slug = $slug;
        $contentObject->form_content_object_id = $requestDataArray['form_content_object_id'];
        $contentObject->emails = $requestDataArray['emails'];
        $contentObject->send_email = $requestDataArray['send_email'];
        $contentObject->collect_data = $requestDataArray['collect_data'];


        $contentObject->save(); // save form content object
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
                $modelObjectType = FormObjectType::where('id', $objectType['form_object_types_id'])->first();
                if ($modelObjectType) {
                    $dataKey = base64_encode('form_object_id:' . $objectType['form_object_id'] . ',object_type_id:' . $modelObjectType->id . ',object_title_slug:' . Str::slug($objectType['object_type_label']) . ',label:' . $objectType['object_type_label'] . ',key:' . $key++);
                    $identifier = FormContentObject::setIdentifier($dataKey, $objectType['object_type_label']);

                    $arrayContent[$this->activeLocale][$identifier] = [
                        'form_object_id' => $objectType['form_object_id'],
                        'form_object_type_id' => $modelObjectType->id,
                        'label' => $objectType['object_type_label'],
                        'object_type_key' => $modelObjectType->key,
                        'key' => $key
                    ];
                    $arrayContent[$this->activeLocale][$identifier]['value'] = $contentObjectArray[$identifier]['value'] ?? null;


                }
            }
        }


        $customPage = FormCustomPage::firstOrNew(['form_content_object_id' => $requestDataArray['id']]);

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
        return redirect()->to(route('filament.resources.form-content-objects.edit', $this->record->id));
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


    private function getObjectTypeArrays($form_content_object_id)
    {
        $modelContentObject = FormContentObject::select('id', 'name', 'slug', 'content')->where('id', $form_content_object_id)->first();
        $objectIds = [];
        foreach ($modelContentObject->content as $objects_id) {
            $objectIds[] = array_values($objects_id);
        }

        $objectTypesArray = [];
        foreach ($objectIds as $key => $objectId) {
            $modelObject = FormObjectModel::select('id', 'content')->where('id', $objectId)->first();
            if ($modelObject) {
                foreach ($modelObject->content as $k => $object) {
                    $objectTypesArray[$key][$k] = ['form_object_id' => $modelObject->id, 'form_object_types_id' => $object['form_object_types_id'], 'object_type_label' => $object['title']];
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
