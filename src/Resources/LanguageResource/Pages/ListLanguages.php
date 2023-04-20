<?php

namespace BossmanFilamentApp\Resources\LanguageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\LanguageResource;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
