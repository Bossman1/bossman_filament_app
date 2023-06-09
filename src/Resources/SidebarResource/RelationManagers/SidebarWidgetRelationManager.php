<?php

namespace BossmanFilamentApp\Resources\SidebarResource\RelationManagers;

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
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\ObjectModel;
use BossmanFilamentApp\Models\Sidebar;
use BossmanFilamentApp\Models\Template;
use BossmanFilamentApp\Models\Widget;
use BossmanFilamentApp\Resources\ContentObjectResource;
use BossmanFilamentApp\Resources\SidebarResource;

class SidebarWidgetRelationManager extends RelationManager
{
    use CategoryTrait;

    protected static string $relationship = 'widgets';

//    protected static string $view = 'views::templates.default.content.relation-manager';

    protected static ?string $recordTitleAttribute = 'Widgets';
    protected static ?string $label = 'Widgets';


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

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return SidebarResource::getUrl('edit', $record->id);
                    }),
                Tables\Actions\DetachAction::make(),

            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn(AttachAction $action): array => [
                        Forms\Components\Select::make('recordId')
                            ->options(function () {
                                return Widget::query()->pluck('name', 'id');
                            })
                            ->reactive()
                            ->searchable()
                            ->required()
                            ->label(__('Widgets')),
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
        return 'Widgets';  // TODO: Change the autogenerated stub
    }

    public static function getTitleForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord): string
    {
        return parent::getTitle() . ' (' . $ownerRecord->{static::$relationship}()->count() . ')';
    }

}
