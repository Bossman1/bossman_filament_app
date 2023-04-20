<?php

namespace BossmanFilamentApp\Resources\TestingResource\Pages;


use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use BossmanFilamentApp\Resources\ObjectResource;
use BossmanFilamentApp\Resources\TestingResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = TestingResource::class;

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
