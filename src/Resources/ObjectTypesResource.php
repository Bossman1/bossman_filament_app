<?php

namespace BossmanFilamentApp\Resources;


use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ObjectType;
use BossmanFilamentApp\Resources\ObjectTypesResource\Pages\CreatePage;
use BossmanFilamentApp\Resources\ObjectTypesResource\Pages\EditPage;
use BossmanFilamentApp\Resources\ObjectTypesResource\Pages\ListPage;
use BossmanFilamentApp\Resources\ObjectTypesResource\Pages\ViewPage;


class ObjectTypesResource extends Resource
{

    protected static ?string $model = ObjectType::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;
    public static function getLabel(): string
    {
        return __('Field');
    }

    public static function getPluralLabel(): string
    {
        return __('Fields');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('key', Str::slug($state));
                        })->required(),
                    Forms\Components\TextInput::make('key')
                        ->disabled()
                        ->label(__('Key')),
                    Forms\Components\Toggle::make('is_published')->inline(false)->default(true)->label(__('Is published')),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->sortable(),
                ToggleColumn::make('is_published')->label(__('Is published')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([

            ])
            ->bulkActions([

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPage::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => ViewPage::route('/{record}'),
            'edit'   => EditPage::route('/{record}/edit'),
        ];
    }
}
