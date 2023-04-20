<?php

namespace BossmanFilamentApp\Resources\ObjectResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\ObjectResource;

class EditPage extends EditRecord
{
    protected static string $resource = ObjectResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
