<?php

namespace App\Utils;

class Str
{
    public static function random($length = 10): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $length);
    }
}
