<?php

declare(strict_types=1);

namespace App\Traits;

trait UseSlugAsKey
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugLowercaseAttribute(): string
    {
        return mb_strtolower($this->slug);
    }
}
