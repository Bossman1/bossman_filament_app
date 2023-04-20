<?php

namespace BossmanFilamentApp\Resources\SidebarResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\SidebarResource;

class EditMenu extends EditRecord
{
    protected static string $resource = SidebarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
