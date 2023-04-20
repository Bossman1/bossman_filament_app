<?php

namespace BossmanFilamentApp\Resources\RelationViewResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use BossmanFilamentApp\Resources\RelationViewResource;

class ListRelationViews extends ListRecords
{
    protected static string $resource = RelationViewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
