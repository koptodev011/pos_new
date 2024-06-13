<?php

namespace App\Enums;

enum FloorTableStatus: string
{
    case Available = 'Available';
    case Serving = 'Serving';
    case Reserved = 'Reserved';
   
    public static function values(): array
    {
        // Filter cases to include only the desired ones
        $filteredCases = array_filter(self::cases(), function($case) {
            return in_array($case->value, ['Available', 'Reserved']);
        });

        // Return the filtered cases as an array of 'name' => 'value'
        return array_column($filteredCases, 'name', 'value');
    }

}
