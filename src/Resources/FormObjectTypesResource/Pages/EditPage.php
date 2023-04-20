<?php

namespace BossmanFilamentApp\Resources\FormObjectTypesResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\FormObjectTypesResource;

class EditPage extends EditRecord
{
    protected static string $resource = FormObjectTypesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
