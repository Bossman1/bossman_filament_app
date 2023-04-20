<?php

namespace BossmanFilamentApp\Resources\WidgetResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\WidgetResource;

class ListMenus extends ListRecords
{
    protected static string $resource = WidgetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
