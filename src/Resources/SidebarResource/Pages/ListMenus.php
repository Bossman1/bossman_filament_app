<?php

namespace BossmanFilamentApp\Resources\SidebarResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\SidebarResource;

class ListMenus extends ListRecords
{
    protected static string $resource = SidebarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
