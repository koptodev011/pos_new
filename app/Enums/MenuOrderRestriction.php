<?php

namespace App\Enums;

enum MenuOrderRestriction: string
{
    case Delivery = 'Delivery';
    case Pickup = 'Pickup';
    case Both = 'Both';
}
