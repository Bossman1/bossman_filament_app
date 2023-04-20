<?php

namespace BossmanFilamentApp\Resources;



use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use BossmanFilamentApp\Models\RelationView;
use BossmanFilamentApp\Resources\RelationViewResource\Pages\CreateRelationView;
use BossmanFilamentApp\Resources\RelationViewResource\Pages\EditRelationView;
use BossmanFilamentApp\Resources\RelationViewResource\Pages\ListRelationViews;

class RelationViewResource extends Resource
{
    protected static ?string $model = RelationView::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Template';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, $state){
                    $set('slug', \Str::slug($state));
                }),
                TextInput::make('slug')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
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
            'index' =>  ListRelationViews::route('/'),
            'create' =>  CreateRelationView::route('/create'),
            'edit' =>  EditRelationView::route('/{record}/edit'),
        ];
    }
}
