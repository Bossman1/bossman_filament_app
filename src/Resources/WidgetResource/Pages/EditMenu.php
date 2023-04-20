<?php

namespace BossmanFilamentApp\Resources\WidgetResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\WidgetResource;

class EditMenu extends EditRecord
{
    protected static string $resource = WidgetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
