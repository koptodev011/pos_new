<?php

namespace App\Filament\Resources\MenuOptionResource\Pages;

use App\Filament\Resources\MenuOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuOption extends CreateRecord
{
    protected static string $resource = MenuOptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
