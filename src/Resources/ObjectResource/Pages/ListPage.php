<?php

namespace BossmanFilamentApp\Resources\ObjectResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use BossmanFilamentApp\Resources\ObjectResource;

class ListPage extends ListRecords
{
    protected static string $resource = ObjectResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
