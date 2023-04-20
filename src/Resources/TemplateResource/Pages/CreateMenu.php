<?php

namespace BossmanFilamentApp\Resources\TemplateResource\Pages;


use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;
use BossmanFilamentApp\Models\Language;
use BossmanFilamentApp\Resources\MenuResource;
use BossmanFilamentApp\Resources\TemplateResource;

class CreateMenu extends CreateRecord
{
    protected static string $resource = TemplateResource::class;
}
