<?php

namespace BossmanFilamentApp\Resources\ObjectTypesResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\ObjectTypesResource;

class EditPage extends EditRecord
{
    protected static string $resource = ObjectTypesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
