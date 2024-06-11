<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class CryptHelper
{
    public static function encrypt($value)
    {
        return Crypt::encrypt($value);
    }

    public static function decrypt($value)
    {
        return Crypt::decrypt($value);
    }

}
