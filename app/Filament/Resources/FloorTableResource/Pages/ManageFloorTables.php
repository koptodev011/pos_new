<?php

namespace App\Filament\Resources\FloorTableResource\Pages;

use App\Filament\Resources\FloorTableResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFloorTables extends ManageRecords
{
    protected static string $resource = FloorTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
