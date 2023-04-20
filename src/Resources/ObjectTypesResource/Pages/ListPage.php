<?php

namespace BossmanFilamentApp\Resources\ObjectTypesResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use BossmanFilamentApp\Resources\ObjectTypesResource;

class ListPage extends ListRecords
{
    protected static string $resource = ObjectTypesResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
