<?php

namespace BossmanFilamentApp\Resources;


use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use BossmanFilamentApp\Models\Language;


class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationGroup = 'Other';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')->label(__('Name'))->placeholder('Exp: Georgia'),
                    TextInput::make('key')->label(__('Key'))->placeholder('Exp: ka'),
                    FileUpload::make('image')->label(__('Upload Flag image'))
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\ImageColumn::make('image'),
                ToggleColumn::make('is_default')->label(__('Default')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort');
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
            'index' => \BossmanFilamentApp\Resources\LanguageResource\Pages\ListLanguages::route('/'),
            'create' => \BossmanFilamentApp\Resources\LanguageResource\Pages\CreateLanguage::route('/create'),
            'edit' => \BossmanFilamentApp\Resources\LanguageResource\Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
