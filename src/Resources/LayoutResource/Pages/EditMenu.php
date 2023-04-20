<?php

namespace BossmanFilamentApp\Resources\LayoutResource\Pages;


use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\LayoutResource;


class EditMenu extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
