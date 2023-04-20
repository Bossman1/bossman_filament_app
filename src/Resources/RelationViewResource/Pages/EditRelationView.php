<?php

namespace BossmanFilamentApp\Resources\RelationViewResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use BossmanFilamentApp\Resources\RelationViewResource;

class EditRelationView extends EditRecord
{
    protected static string $resource = RelationViewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
