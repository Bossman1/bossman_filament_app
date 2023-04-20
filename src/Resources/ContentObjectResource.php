<?php

namespace BossmanFilamentApp\Resources;

use App\Traits\CategoryTrait;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\CustomPage;
use BossmanFilamentApp\Models\ObjectModel;

use BossmanFilamentApp\Models\Template;
use BossmanFilamentApp\Resources\ContentObjectResource\Pages\CreatePage;
use BossmanFilamentApp\Resources\ContentObjectResource\Pages\EditPage;
use BossmanFilamentApp\Resources\ContentObjectResource\Pages\ListPage;
use BossmanFilamentApp\Resources\ContentObjectResource\RelationManagers\ContentObjectRelationManager;
use BossmanFilamentApp\Resources\ContentObjectResource\RelationManagers\FormsRelationManager;
use BossmanFilamentApp\Resources\ContentObjectResource\RelationManagers\RelationBlockRelationManager;


class ContentObjectResource extends Resource
{
    use Translatable, CategoryTrait;

    protected static ?string $model = ContentObject::class;

//    protected static ?string $navigationIcon = 'heroicon-o-template';
    protected static ?string $navigationGroup = 'Content Management';


    protected static ?int $navigationSort = 4;


    public static function getLabel(): string
    {
        return __('Content record');
    }

    public static function getPluralLabel(): string
    {
        return __('Contents record');
    }

    public static function form(Form $form): Form
    {

        $modelContentObject = ContentObject::select('id', 'name', 'content_object_id')->with('children', 'parent')->whereNull('content_object_id')->get();
        $content_objects = self::initMenuChildrenTree($modelContentObject, '-');// build multilevel menu tree
        $layoutDefault = \BossmanFilamentApp\Models\Layout::query()->select('id')->where('slug','main')->first();

        return $form
            ->schema([
                Section::make('Object Properties')->schema([
                    Card::make()->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $set('slug', Str::slug($state));
                            })->required(),
                        TextInput::make('slug')->required()->unique(),
                        Card::make()->schema([
                            Select::make('content_object_id')->options(function ($record) use ($content_objects) {
                                return $content_objects;
                            })
                                ->label(__('Parent content objects'))
                                ->placeholder(__('Main content object')),
                            Repeater::make('content')
                                ->label('Content Object')
                                ->schema([
                                    Select::make('objects_id')
                                        ->options(ObjectModel::query()->pluck('name', 'id'))->required()
                                ])
                                ->columns(1)
                                ->createItemButtonLabel('Add new Object')
                                ->collapsed()
                                ->cloneable()
                                ->orderable()
                                ->grid(1)
                                ->collapsed(false)
                                ->columns(1),


                        ]),
                        Select::make('layout_id')->options(function ($record) {
                            $layouts = \BossmanFilamentApp\Models\Layout::query()->get();
                            $layoutsArray = [];
                            foreach ($layouts as $layout) {
                                $layoutsArray[$layout->id] = $layout->name . ' (' . $layout->slug . ')';
                            }
                            return $layoutsArray;
                        })
                            ->default($layoutDefault->id)
                        ->label(__('Layout')),
                        Select::make('template_id')->options(function ($record) {
                            $template = \BossmanFilamentApp\Models\Template::query()->get();
                            $templateArray = [];
                            foreach ($template as $tmpl) {
                                $templateArray[$tmpl->id] = $tmpl->name . ' (' . $tmpl->slug . ')';
                            }
                            return $templateArray;
                        })
                             ->label(__("View")),
                        Select::make('relation_view_id')->options(function ($record){
                            $relation_view = \BossmanFilamentApp\Models\RelationView::query()->get();
                            $relation_viewArray = [];
                            foreach ($relation_view as $relation) {
                                $relation_viewArray[$relation->id] = $relation->name . ' ('.$relation->slug.')';
                            }
                            return $relation_viewArray;
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
                        Forms\Components\Toggle::make('is_published')->inline(true)->default(true)->label(__('Is published')),
                        Forms\Components\Toggle::make('show_in_menu')->inline(true)->label(__('Show In Menu')),
                    ])
                ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(__('Type'))
                    ->tooltip(function ($record) {

                        if ($record->children->count() > 0) {
                            $html = [];
                            foreach ($record->children as $child) {
                                $html[] = $child->name;
                            }
                            return implode(" | ", $html);
                        }
                        return false;
                    })
                    ->getStateUsing(function ($record) {
                        return " (" . $record->children->count() . ")";
                    })
                    ->icon(function ($record) {
                        if (!empty($record->children->toArray())) {
                            return 'heroicon-s-folder';
                        }
                        return 'heroicon-o-collection';
                    }),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('Image')
                    ->collection('gallery')->width(40),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->limit(35)
                    ->searchable(),
                Tables\Columns\TextColumn::make('template_id')
                    ->getStateUsing(function ($record) {
                        $template = Template::query()->select('name', 'slug')->where('id', $record->template_id)->first();
                        if ($template) {
                            return $template->name . ' | ' . $template->slug;
                        }
                        return '';
                    })
                     ->label(__("View")),
                Tables\Columns\TextColumn::make('layout_id')
                    ->getStateUsing(function ($record) {
                        $layout = Template::query()->select('name', 'slug')->where('id', $record->layout_id)->first();
                        if ($layout) {
                            return $layout->name . ' | ' . $layout->slug;
                        }
                        return '';
                    })
                    ->label(__("Layout")),
                TextColumn::make('content_object_id')->getStateUsing(
                    static function ($record): string {
                        $modelContentObject = ContentObject::with('children')->where('id', $record->id)->first();
                        $html = '<ul class="children_ul">';
                        if ($modelContentObject && $modelContentObject->children) {
                            foreach ($modelContentObject->children as $child) {
                                $html .= '<li class="" title="' . $child->name . '">' . Str::limit($child->name, 40, '...') . '</li>';
                            }
                        }
                        $html .= '</ul>';
                        return $html;
                    }
                )
                    ->sortable()
                    ->searchable()
                    ->label('Children')
                    ->html(),
//                Tables\Columns\TextColumn::make('slug')
//                    ->label('Slug')
//                    ->sortable()
//                    ->searchable(),

                ToggleColumn::make('is_published')
                    ->sortable()
                    ->label(__('Is published')),
                ToggleColumn::make('show_in_menu')
                    ->sortable()
                    ->label(__('Show In Menu')),


            ])
            ->actions([
//                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Clone')
                    ->action(function ($record) {
                        ContentObjectResource::cloneObject($record);
                    })->icon('heroicon-o-duplicate')
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ])
            ->reorderable('sort')
            ->defaultSort('sort');
    }


    protected static function getNavigationGroup(): ?string
    {
        return parent::getNavigationGroup();
    }

    protected static function getNavigationSort(): ?int
    {
        return parent::getNavigationSort();
    }

    public static function getRelations(): array
    {
        return [
            ContentObjectRelationManager::class,
            RelationBlockRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => ListPage::route('/'),
            'create' => CreatePage::route('/create'),
//            'view' => ViewPage::route('/{record}'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {

        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }


    public static function cloneObject($record)
    {

        $modelContentObject = ContentObject::with('children')->find($record->id);
        $suffix = '-Copy';
        if ($modelContentObject) {
            $old_name = $modelContentObject->name . $suffix;
            $old_slug = $modelContentObject->slug . '-' . Str::slug($suffix);
            $old_id = $modelContentObject->id;

        }
        $cloneModelContentObject = $modelContentObject->replicate();
        $cloneModelContentObject->name = $old_name;
        $cloneModelContentObject->slug = $old_slug;
        if ($cloneModelContentObject->save()) {






            $modelCustomPage = CustomPage::where('content_object_id', $old_id)->first();
            if ($modelCustomPage) {
                $cloneModelCustomPage = $modelCustomPage->replicate();
                $cloneModelCustomPage->content_object_id = $cloneModelContentObject->id;
                $cloneModelCustomPage->save();
            }



            if ($modelContentObject->children) {
                foreach ($modelContentObject->children as $child) {
                    $old_id_ch = $child->id;
                    //clone children content objects
                    $cloneChild = $child->replicate();
                    $cloneChild->name = $cloneModelContentObject->name.$suffix;
                    $cloneChild->slug = $cloneModelContentObject->slug.$suffix;
                    $cloneChild->content_object_id = $cloneModelContentObject->id;
                    $cloneChild->save();
                    //end clone children content objects
                    $modelCustomPage = CustomPage::where('content_object_id', $old_id_ch)->first();
                    if($modelCustomPage){
                        $cloneModelCustomPage = $modelCustomPage->replicate();
                        $cloneModelCustomPage->content_object_id = $cloneChild->id;
                        $cloneModelCustomPage->save();
                    }
                }
            }



        }


    }

}
