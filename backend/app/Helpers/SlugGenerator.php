<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class SlugGenerator
{
    public static function generate(int $length = 8): string
    {
        return mb_strtoupper(Str::random($length));
    }
}
