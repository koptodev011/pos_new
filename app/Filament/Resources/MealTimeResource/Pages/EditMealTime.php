<?php

namespace App\Filament\Resources\MealTimeResource\Pages;

use App\Filament\Resources\MealTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMealTime extends EditRecord
{
    protected static string $resource = MealTimeResource::class;

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
