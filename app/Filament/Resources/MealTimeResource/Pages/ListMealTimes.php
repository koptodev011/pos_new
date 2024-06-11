<?php

namespace App\Filament\Resources\MealTimeResource\Pages;

use App\Filament\Resources\MealTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMealTimes extends ListRecords
{
    protected static string $resource = MealTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
