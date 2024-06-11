<?php

namespace App\Filament\Resources\MenuOptionResource\Pages;

use App\Filament\Resources\MenuOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuOptions extends ListRecords
{
    protected static string $resource = MenuOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
