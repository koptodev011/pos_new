<?php

namespace App\Filament\Clusters\TaxCluster\Resources\TaxZoneResource\Pages;

use App\Filament\Clusters\TaxCluster\Resources\TaxZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTaxZones extends ManageRecords
{
    protected static string $resource = TaxZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
