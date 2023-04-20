<?php

namespace BossmanFilamentApp\Resources\TestingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use BossmanFilamentApp\Resources\ObjectResource;
use BossmanFilamentApp\Resources\TestingResource;

class ListPage extends ListRecords
{
    protected static string $resource = TestingResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
