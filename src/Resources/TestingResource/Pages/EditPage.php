<?php

namespace BossmanFilamentApp\Resources\TestingResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\ObjectResource;
use BossmanFilamentApp\Resources\TestingResource;

class EditPage extends EditRecord
{
    protected static string $resource = TestingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
