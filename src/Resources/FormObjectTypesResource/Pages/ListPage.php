<?php

namespace BossmanFilamentApp\Resources\FormObjectTypesResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use BossmanFilamentApp\Resources\FormObjectTypesResource;

class ListPage extends ListRecords
{
    protected static string $resource = FormObjectTypesResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
