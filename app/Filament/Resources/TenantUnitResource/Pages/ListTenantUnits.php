<?php

namespace App\Filament\Resources\TenantUnitResource\Pages;

use App\Filament\Resources\TenantUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenantUnits extends ListRecords
{
    protected static string $resource = TenantUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
