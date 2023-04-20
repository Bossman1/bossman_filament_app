<?php

namespace BossmanFilamentApp\Resources\FormContentObjectResource\RelationManagers;

use BossmanFilamentApp\Traits\CategoryTrait;
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
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\FormContentObject;
use BossmanFilamentApp\Models\ObjectModel;
use BossmanFilamentApp\Models\Template;
use BossmanFilamentApp\Resources\ContentObjectResource;
use BossmanFilamentApp\Resources\FormContentObjectResource;

class FormContentObjectRelationManager extends RelationManager
{
    use CategoryTrait;

    protected static string $relationship = 'children';

//    protected static string $view = 'views::templates.content.relation-manager';

    protected static ?string $label = "Record";




    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->getStateUsing(
                        static function ($record): string {
                            return $record->created_at->toDayDateTimeString();
                        }
                    )
                    ->label('Date')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('form_content_object_id')->getStateUsing(
                    static function ($record): string {
                        $modelContentObject = FormContentObject::select('id', 'name', 'form_content_object_id')->where('id', $record->id)->first();
                        $icon = ' <span class="parent-category_sub-tree">&#127866;</span> ';
                        $content_objects = self::initMenuParentTree($modelContentObject);// build multilevel menu tree
                        $html = implode(' ', $content_objects);
                        return $html;
                    }
                )
                    ->label('Parent')->html(),


            ])
            ->actions([
//                Tables\Actions\CreateAction::make(),
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return FormContentObjectResource::getUrl('edit', $record->id);
                    }),

                Tables\Actions\DeleteAction::make(),

            ])
            ->filters([

            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
//                    ->url(function ($record){
//                        return ContentObjectResource::getUrl('create');
//                    }),
//                Tables\Actions\AttachAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ])
            ->defaultSort('created_at', 'DESC');
    }

    public static function getTitleForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord): string
    {
        return ($ownerRecord->{static::$relationship}()->count()) ? parent::getTitle().' (' . $ownerRecord->{static::$relationship}()->count() .')': parent::getTitle();
    }

}
