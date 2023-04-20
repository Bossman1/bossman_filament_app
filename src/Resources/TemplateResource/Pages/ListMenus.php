<?php

namespace BossmanFilamentApp\Resources\TemplateResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\TemplateResource;

class ListMenus extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
