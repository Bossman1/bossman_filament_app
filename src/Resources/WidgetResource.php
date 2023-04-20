<?php

namespace BossmanFilamentApp\Resources;


use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use BossmanFilamentApp\Models\Widget;
use BossmanFilamentApp\Resources\WidgetResource\Pages\CreateMenu;
use BossmanFilamentApp\Resources\WidgetResource\Pages\EditMenu;
use BossmanFilamentApp\Resources\WidgetResource\Pages\ListMenus;


class WidgetResource extends Resource
{


    protected static ?string $model = Widget::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Navigation';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (Closure $set, $state) {
                        $set('key', \Str::slug($state));
                    }),
                TextInput::make('key')->required()->unique()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('key'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
