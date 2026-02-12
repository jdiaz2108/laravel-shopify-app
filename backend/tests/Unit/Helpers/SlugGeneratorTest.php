<?php

declare(strict_types=1);

use App\Helpers\SlugGenerator;

it('generates a slug with default length', function (): void {
    $slug = SlugGenerator::generate();
    expect($slug)->toHaveLength(8);
});

it('generates a slug with a custom length', function (): void {
    $slug = SlugGenerator::generate(10);
    expect($slug)->toHaveLength(10);
});

it('generates an uppercase slug', function (): void {
    $slug = SlugGenerator::generate();
    expect($slug)->toBe(mb_strtoupper($slug));
});
