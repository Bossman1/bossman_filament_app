<?php

namespace BossmanFilamentApp\Resources\FormObjectResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;
use BossmanFilamentApp\Resources\FormObjectResource;

class ListPage extends ListRecords
{
    protected static string $resource = FormObjectResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
