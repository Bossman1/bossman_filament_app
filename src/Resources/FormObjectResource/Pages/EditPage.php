<?php

namespace BossmanFilamentApp\Resources\FormObjectResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\FormObjectResource;

class EditPage extends EditRecord
{
    protected static string $resource = FormObjectResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
