<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Placed = 'Placed';
    case Preparing = 'Preparing';
    case Delivered = 'Delivered';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
    case Ready = 'Ready';

    public static function values(): array
    {
        // Filter cases to include only the desired ones
        $filteredCases = array_filter(self::cases(), function($case) {
            return in_array($case->value, ['Placed', 'Preparing', 'Ready']);
        });

        // Return the filtered cases as an array of 'name' => 'value'
        return array_column($filteredCases, 'name', 'value');
    }

    public static function hostvalues(): array
    {
        // Filter cases to include only the desired ones
        $filteredCases = array_filter(self::cases(), function($case) {
            return in_array($case->value, ['Ready','Delivered']);
        });

        // Return the filtered cases as an array of 'name' => 'value'
        return array_column($filteredCases, 'name', 'value');
    }
}
