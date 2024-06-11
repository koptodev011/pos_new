<?php

namespace App\Enums;

enum TaxPriceDisplayType: string
{
    case IncludeTax = 'include_tax';
    case ExcludeTax = 'exclude_tax';
}
