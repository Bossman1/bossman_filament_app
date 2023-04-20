<?php

namespace BossmanFilamentApp\Resources\LanguageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\LanguageResource;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
