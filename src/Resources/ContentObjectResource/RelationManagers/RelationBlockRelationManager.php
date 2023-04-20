<?php

namespace BossmanFilamentApp\Resources\ContentObjectResource\RelationManagers;

use App\Traits\CategoryTrait;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\ObjectModel;
use BossmanFilamentApp\Models\Template;
use BossmanFilamentApp\Resources\ContentObjectResource;

class RelationBlockRelationManager extends RelationManager
{
    use CategoryTrait;

    protected static string $relationship = 'relation_blocks';

//    protected static string $view = 'views::templates.default.content.relation-manager';

    protected static ?string $recordTitleAttribute = 'Relation Blocks';
    protected static ?string $label = 'Relation Blocks';
    protected int $counter;



    public static function form(Form $form): Form
    {

        $modelContentObject = ContentObject::select('id', 'name', 'content_object_id')->with('children', 'parent')->whereNull('content_object_id')->get();
        $content_objects = self::initMenuChildrenTree($modelContentObject, '-');// build multilevel menu tree

        return $form
            ->schema([

                Card::make()->schema([
//                    Forms\Components\Select::make('relation_blocks')
//                        ->multiple()
//                        ->searchable()
//                        ->relationship('relation_blocks', 'name'),
                ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(__('Type'))
                    ->getStateUsing(function ($record) {
                        return " (" . $record->children->count() . ")";
                    })
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
                    ->collection('gallery')->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
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
                TextColumn::make('content_object_id')->getStateUsing(
                    static function ($record): string {
                        $modelContentObject = ContentObject::select('id', 'name', 'content_object_id')->where('id', $record->id)->first();
                        $icon = ' <span class="parent-category_sub-tree">&#127866;</span> ';
                        $content_objects = self::initMenuParentTree($modelContentObject);// build multilevel menu tree
                        $html = implode(' ', $content_objects);
                        return $html;
                    }
                )
                    ->sortable()
                    ->label('Parent Category')->html(),

                TextColumn::make('children')->getStateUsing(
                    static function ($record): string {
                        $modelContentObject = ContentObject::with('children')->where('id', $record->id)->first();
                        $html = '<ul class="children_ul">';
                        if ($modelContentObject && $modelContentObject->children) {
                            foreach ($modelContentObject->children as $child) {
                                $html .= '<li class="">' . $child->name . '</li>';
                            }
                        }
                        $html .= '</ul>';
                        return $html;
                    }
                )->html(),
//                Tables\Columns\TextColumn::make('slug')
//                    ->label('Slug')
//                    ->sortable()
//                    ->searchable(),

                ToggleColumn::make('show_in_menu')
                    ->sortable()
                    ->label(__('Show In Menu')),
                ToggleColumn::make('is_published')
                    ->sortable()
                    ->label(__('Is published')),

            ])
            ->actions([
//                Tables\Actions\CreateAction::make(),
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return ContentObjectResource::getUrl('edit', $record->id);
                    }),

//                Tables\Actions\Action::make('Clone')
//                    ->action(function ($record) {
//                        ContentObjectResource::cloneObject($record);
//                    })->icon('heroicon-o-duplicate')
//                    ->requiresConfirmation(),
                Tables\Actions\DetachAction::make(),

            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn(AttachAction $action): array => [
                        Forms\Components\Select::make('recordId')
                            ->options(function (){
                                return ContentObject::query()->pluck('name','id');
                            })
                            ->reactive()
                            ->searchable()
                            ->required(),
                    ])

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ])
            ->reorderable('sort')
            ->defaultSort('sort');
    }




    protected static function getPluralRecordLabel(): ?string
    {
        return 'Relations';  // TODO: Change the autogenerated stub
    }

    public static function getTitleForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord): string
    {
        return ($ownerRecord->{static::$relationship}()->count()) ? parent::getTitle().' (' . $ownerRecord->{static::$relationship}()->count() .')':parent::getTitle();
    }

}
