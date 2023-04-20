<?php

namespace BossmanFilamentApp\Resources\ContentObjectResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Builder;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\Language;
use BossmanFilamentApp\Resources\ContentObjectResource;

class ListPage extends ListRecords
{
    protected static string $resource = ContentObjectResource::class;
    protected static ?string $model = ContentObject::class;
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
         return static::getResource()::getEloquentQuery()->with('children')->where('content_object_id', null);
    }

}
