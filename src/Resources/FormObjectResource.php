<?php

namespace BossmanFilamentApp\Resources;


use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


use Illuminate\Support\Str;
use BossmanFilamentApp\Models\FormObjectModel;
use BossmanFilamentApp\Models\FormObjectType;
use BossmanFilamentApp\Models\Menu;
use BossmanFilamentApp\Models\ObjectType;
use BossmanFilamentApp\Resources\FormObjectResource\Pages\CreatePage;
use BossmanFilamentApp\Resources\FormObjectResource\Pages\EditPage;
use BossmanFilamentApp\Resources\FormObjectResource\Pages\ListPage;


class FormObjectResource extends Resource
{

    public ?bool $isHiden;
    protected static ?string $model = FormObjectModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-grid';
    protected static ?string $navigationGroup = 'Form Management';

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('Form Object');
    }

    public static function getPluralLabel(): string
    {
        return __('Form Objects');
    }

    public static function form(Form $form): Form
    {

        $modelMenus = Menu::query()->pluck('name', 'id')->toArray();
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        })->required(),
                    TextInput::make('slug')->required()->unique()->disabled(),
                    Card::make()->schema([
                        Repeater::make('content')
                            ->label('Fields')
                            ->schema([
                                TextInput::make('title')->required(),
                                Select::make('form_object_types_id')
                                    ->relationship('form_object_types', 'name')
                                    ->reactive()
//                                    ->afterStateUpdated(fn(callable $set) => $set('field_options', null))
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $set('field_options', null);

                                    })
                                    ->required(),

                                Select::make('field_options')
                                    ->reactive()
                                    ->options(function (callable $get) {
                                        $objectType = FormObjectType::find($get('form_object_types_id'));
                                        if (!$objectType) {
                                            return FormObjectType::FIELD_OPTIONS;
                                        }


                                        return ['required' => ObjectType::FIELD_OPTIONS['required']];
                                    })
                                    ->multiple(),

                            ])
                            ->columns(3)
                            ->createItemButtonLabel('Add Object Types')
                    ]),
                    Forms\Components\Toggle::make('is_published')->inline(false)->default(true)->label(__('Is published')),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable(),
//                Tables\Columns\TextColumn::make('slug')
//                    ->label('Slug')
//                    ->sortable(),
//                ToggleColumn::make('is_published')->label(__('Is published')),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ])
            ->defaultSort('id', 'DESC');
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
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
