<?php

declare(strict_types=1);

use App\DTOs\Shopify\SyncResultDTO;

it('can be constructed', function (): void {
    $dto = new SyncResultDTO(created: 5, updated: 4, failed: 1, total: 10);

    expect($dto->created)->toBe(5)
        ->and($dto->updated)->toBe(4)
        ->and($dto->failed)->toBe(1)
        ->and($dto->total)->toBe(10);
});

it('can be constructed empty', function (): void {
    $dto = SyncResultDTO::empty();

    expect($dto->created)->toBe(0)
        ->and($dto->updated)->toBe(0)
        ->and($dto->failed)->toBe(0)
        ->and($dto->total)->toBe(0);
});

it('detects failures', function (): void {
    $dto = new SyncResultDTO(created: 2, updated: 1, failed: 1, total: 4);
    expect($dto->hasFailures())->toBeTrue();
});

it('reports no failures when failed is zero', function (): void {
    $dto = new SyncResultDTO(created: 2, updated: 1, failed: 0, total: 3);
    expect($dto->hasFailures())->toBeFalse();
});

it('was successful when total is greater than zero and no failures', function (): void {
    $dto = new SyncResultDTO(created: 2, updated: 1, failed: 0, total: 3);
    expect($dto->wasSuccessful())->toBeTrue();
});

it('was not successful when there are failures', function (): void {
    $dto = new SyncResultDTO(created: 2, updated: 1, failed: 1, total: 4);
    expect($dto->wasSuccessful())->toBeFalse();
});

it('was not successful when total is zero', function (): void {
    $dto = SyncResultDTO::empty();
    expect($dto->wasSuccessful())->toBeFalse();
});

it('returns the correct processed count', function (): void {
    $dto = new SyncResultDTO(created: 5, updated: 3, failed: 1, total: 9);
    expect($dto->processedCount())->toBe(8);
});

it('converts to array correctly', function (): void {
    $dto = new SyncResultDTO(created: 5, updated: 3, failed: 1, total: 9);

    expect($dto->toArray())->toBe([
        'total'   => 9,
        'created' => 5,
        'updated' => 3,
        'failed'  => 1,
    ]);
});
