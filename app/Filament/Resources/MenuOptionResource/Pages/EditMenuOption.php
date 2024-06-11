<?php

namespace App\Filament\Resources\MenuOptionResource\Pages;

use App\Filament\Resources\MenuOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuOption extends EditRecord
{
    protected static string $resource = MenuOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
