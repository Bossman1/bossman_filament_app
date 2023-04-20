<?php

namespace BossmanFilamentApp\Resources\FormObjectResource\Pages;


use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use BossmanFilamentApp\Resources\FormObjectResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = FormObjectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success();
//            ->title('Test registered')
//            ->body('The Test has been created successfully.');
    }
}
