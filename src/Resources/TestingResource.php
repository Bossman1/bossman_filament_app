<?php

namespace BossmanFilamentApp\Resources;


use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use BossmanFilamentApp\Models\ObjectType;
use BossmanFilamentApp\Models\Testing;
use BossmanFilamentApp\Resources\TestingResource\Pages\CreatePage;
use BossmanFilamentApp\Resources\TestingResource\Pages\EditPage;
use BossmanFilamentApp\Resources\TestingResource\Pages\ListPage;
use BossmanFilamentApp\Resources\TestingResource\Pages\ViewPage;


class TestingResource extends Resource
{

    protected static ?string $model = Testing::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';
    protected static ?string $navigationGroup = 'Testing section';
    public static function getLabel(): string
    {
        return __('Testing');
    }

    public static function getPluralLabel(): string
    {
        return __('Testings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                       ->required(),
                    FileUpload::make('attachment')->multiple()
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

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([

            ])
            ->bulkActions([

            ]);
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
