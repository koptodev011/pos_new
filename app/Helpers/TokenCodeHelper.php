<?php

namespace App\Helpers;

class TokenCodeHelper
{
    public static function newCode()
    {
        return str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

}
