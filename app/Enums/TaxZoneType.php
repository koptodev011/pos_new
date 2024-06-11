<?php

namespace App\Enums;

enum TaxZoneType: string
{
    case Country = 'country';
    case State = 'state';
    case PostalCode = 'postal_code';
}
