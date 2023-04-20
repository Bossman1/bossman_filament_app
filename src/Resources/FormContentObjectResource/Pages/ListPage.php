<?php

namespace BossmanFilamentApp\Resources\FormContentObjectResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Builder;
use BossmanFilamentApp\Models\FormContentObject;
use BossmanFilamentApp\Resources\FormContentObjectResource;

class ListPage extends ListRecords
{
    protected static string $resource = FormContentObjectResource::class;
    protected static ?string $model = FormContentObject::class;
//    use ListRecords\Concerns\Translatable;

//    public static function getTranslatableLocales(): array
//    {
//        $modelLanguages = Language::query()->orderBy('sort')->pluck('key', 'name');
//        if ($modelLanguages) {
//            return $modelLanguages->toArray();
//        }
//        return [];
//    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
//            Actions\LocaleSwitcher::make(),
        ];
    }

    public   function getTableQuery(): Builder
    {
        // your query
         return static::getResource()::getEloquentQuery()->with('children')->where('form_content_object_id', null);
    }

}
