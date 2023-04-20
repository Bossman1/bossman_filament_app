<?php

namespace BossmanFilamentApp\Resources\TestingResource\Pages;


use Filament\Resources\Pages\ViewRecord;
use BossmanFilamentApp\Resources\ObjectResource;
use BossmanFilamentApp\Resources\TestingResource;


class ViewPage extends ViewRecord
{
    protected static string $resource = TestingResource::class;
}
