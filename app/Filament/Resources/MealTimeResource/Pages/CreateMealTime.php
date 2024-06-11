<?php

namespace App\Filament\Resources\MealTimeResource\Pages;

use App\Filament\Resources\MealTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMealTime extends CreateRecord
{
    protected static string $resource = MealTimeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
