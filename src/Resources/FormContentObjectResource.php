<?php

namespace BossmanFilamentApp\Resources;

use App\Traits\CategoryTrait;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Illuminate\Support\Str;
use BossmanFilamentApp\Models\FormContentObject;
use BossmanFilamentApp\Models\FormCustomPage;
use BossmanFilamentApp\Models\FormObjectModel;

use BossmanFilamentApp\Models\Template;

use BossmanFilamentApp\Resources\FormContentObjectResource\Pages\CreatePage;
use BossmanFilamentApp\Resources\FormContentObjectResource\Pages\EditPage;
use BossmanFilamentApp\Resources\FormContentObjectResource\Pages\ListPage;
use BossmanFilamentApp\Resources\FormContentObjectResource\RelationManagers\FormContentObjectRelationManager;


class FormContentObjectResource extends Resource
{
    use Translatable, CategoryTrait;

    protected static ?string $model = FormContentObject::class;

//    protected static ?string $navigationIcon = 'heroicon-o-template';
    protected static ?string $navigationGroup = 'Form Management';


    protected static ?int $navigationSort = 4;


    public static function getLabel(): string
    {
        return __('Form Content record');
    }

    public static function getPluralLabel(): string
    {
        return __('Form Contents records');
    }

    public static function form(Form $form): Form
    {

        $modelContentObject = FormContentObject::select('id', 'name', 'form_content_object_id')->with('children', 'parent')->whereNull('form_content_object_id')->get();
        $content_objects = self::initMenuChildrenTree($modelContentObject, '-');// build multilevel menu tree

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

//                            Select::make('form_content_object_id')->options(function ($record) use ($content_objects) {
//                                return $content_objects;
//                            })
//                                ->label(__('Parent form content objects'))
//                                ->placeholder(__('Main content object')),
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
                        TagsInput::make('emails')->label(__('Emails'))->placeholder(__('Emails')),
                        Toggle::make('send_email')->inline(false)->default(true)->label(__('Send Emails')),
                        Toggle::make('collect_data')->inline(false)->default(true)->label(__('Record data')),
                    ])
                ])
            ]);
    }


    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make(__('Records'))

                    ->getStateUsing(function ($record) {
                        return " (" . $record->children->count() . ")";
                    })
                    ->icon(function ($record) {
                        if (!empty($record->children->toArray())) {
                            return 'heroicon-s-folder';
                        }
                        return 'heroicon-o-collection';
                    }),


                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->limit(35)
                    ->searchable(),

//                TextColumn::make('form_content_object_id')->getStateUsing(
//                    static function ($record): string {
//                        $modelContentObject = FormContentObject::with('children')->where('id', $record->id)->first();
//                        $html = '<ul class="children_ul">';
//                        if ($modelContentObject && $modelContentObject->children) {
//                            foreach ($modelContentObject->children as $child) {
//                                $html .= '<li class="" title="' . $child->name . '">' . Str::limit($child->name, 40, '...') . '</li>';
//                            }
//                        }
//                        $html .= '</ul>';
//                        return $html;
//                    }
//                )
//                    ->sortable()
//                    ->searchable()
//                    ->label('Children')
//                    ->html(),
//                Tables\Columns\TextColumn::make('slug')
//                    ->label('Slug')
//                    ->sortable()
//                    ->searchable(),






            ])
            ->actions([
//                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

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
           FormContentObjectRelationManager::class
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
        return static::getEloquentQuery()->whereNull('form_content_object_id')->count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {

        return static::getEloquentQuery()->whereNull('form_content_object_id')->count() > 10 ? 'warning' : 'primary';
    }


    public static function cloneFormObject($record)
    {

        $modelContentObject = FormContentObject::with('children')->find($record->id);
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


            $modelCustomPage = FormCustomPage::where('form_content_object_id', $old_id)->first();
            if ($modelCustomPage) {
                $cloneModelCustomPage = $modelCustomPage->replicate();
                $cloneModelCustomPage->form_content_object_id = $cloneModelContentObject->id;
                $cloneModelCustomPage->save();
            }


            if ($modelContentObject->children) {
                foreach ($modelContentObject->children as $child) {
                    $old_id_ch = $child->id;
                    //clone children content objects
                    $cloneChild = $child->replicate();
                    $cloneChild->name = $cloneModelContentObject->name . $suffix;
                    $cloneChild->slug = $cloneModelContentObject->slug . $suffix;
                    $cloneChild->form_content_object_id = $cloneModelContentObject->id;
                    $cloneChild->save();
                    //end clone children content objects
                    $modelCustomPage = FormCustomPage::where('form_content_object_id', $old_id_ch)->first();
                    if ($modelCustomPage) {
                        $cloneModelCustomPage = $modelCustomPage->replicate();
                        $cloneModelCustomPage->form_content_object_id = $cloneChild->id;
                        $cloneModelCustomPage->save();
                    }
                }
            }


        }


    }

}
