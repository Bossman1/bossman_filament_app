<?php

namespace BossmanFilamentApp\Resources\FormContentObjectResource\Pages;


use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use BossmanFilamentApp\Resources\FormContentObjectResource;

class CreatePage extends CreateRecord
{
//    use CreateRecord\Concerns\Translatable;
    protected static string $resource = FormContentObjectResource::class;

//    protected static string $view = 'views::templates.content.create';

    protected function getActions(): array
    {
        return [
//            LocaleSwitcher::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {

        return $this->getResource()::getUrl('edit', $this->record->id);
    }




    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success();
//            ->title('Test registered')
//            ->body('The Test has been created successfully.');
    }
}
