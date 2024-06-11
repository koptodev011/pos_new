<?php

namespace App\Enums;

enum MenuType: string
{
    case Veg = 'Veg';
    case NonVeg = 'NonVeg';
    case Vegan = 'Vegan';
    case Egg = 'Egg';
    case Other = 'Other';

    public static function filterCases()
    {
        return [MenuType::Veg, MenuType::NonVeg, MenuType::Egg];
    }

    public function image()
    {
        return "/assets/images/{$this->value}.webp";
    }

}
