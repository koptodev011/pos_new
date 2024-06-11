<?php

namespace App\Filament\Resources\TenantUnitResource\Pages;

use App\Filament\Resources\TenantUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenantUnit extends EditRecord
{
    protected static string $resource = TenantUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
