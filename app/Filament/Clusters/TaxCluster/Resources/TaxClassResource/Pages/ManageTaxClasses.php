<?php

namespace App\Filament\Clusters\TaxCluster\Resources\TaxClassResource\Pages;

use App\Filament\Clusters\TaxCluster\Resources\TaxClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTaxClasses extends ManageRecords
{
    protected static string $resource = TaxClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
