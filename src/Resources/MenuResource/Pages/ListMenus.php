<?php

namespace BossmanFilamentApp\Resources\MenuResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\MenuResource;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
