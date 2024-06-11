<?php

namespace App\Enums;

enum MenuPriceValidity: string
{
    case Forever = 'Forever';
    case Recurring = 'Recurring';
    case Period = 'Period';
}
