<?php

namespace BossmanFilamentApp\Resources\MenuResource\Pages;


use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;
use BossmanFilamentApp\Models\Language;
use BossmanFilamentApp\Resources\MenuResource;

class CreateMenu extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    public static function getTranslatableLocales(): array
    {
        $modelLanguages = Language::query()->orderBy('sort')->pluck('key', 'name');
        if ($modelLanguages) {
            return $modelLanguages->toArray();
        }
        return [];
    }


    protected function getFormSchema($record = null): array
    {
        $modelLanguages = Language::query()->select('image')->where('key', $this->activeLocale)->first();
        $langImage = "<img src='/storage/" . $modelLanguages->image . "' style='width: 24px'>";

        return [
            Card::make()->schema([
                TextInput::make('name')
                    ->reactive()
                    ->afterStateUpdated(fn(Closure $set, $state) => $set('key', \Str::slug($state))),
                TextInput::make('key'),
                Repeater::make('content')->schema([
                    TextInput::make('menu_list')
                ])
                    ->hint(function ()use ($langImage) {
                        return new HtmlString('<span style="display: flex;"><strong>' . __('Language') . ':</strong> &nbsp;&nbsp;' . $langImage.'</span>');
                    }),
            ])
        ];

    }

    protected static string $resource = MenuResource::class;
}
