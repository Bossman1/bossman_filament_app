<?php

namespace BossmanFilamentApp\Resources\LayoutResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\LayoutResource;


class ListMenus extends ListRecords
{
    protected static string $resource = LayoutResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
