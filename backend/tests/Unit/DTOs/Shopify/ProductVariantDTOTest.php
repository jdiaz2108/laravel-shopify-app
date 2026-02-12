<?php

declare(strict_types=1);

use App\DTOs\Shopify\ProductVariantDTO;

it('can be constructed from graphQL', function (): void {
    $dto = ProductVariantDTO::fromGraphQL([
        'id' => 'gid://shopify/ProductVariant/123',
        'title' => 'Large / Red',
        'price' => '29.99',
        'sku' => 'SKU-001',
        'inventoryQuantity' => 5,
    ], 'gid://shopify/Product/999');

    expect($dto->shopifyId)->toBe('gid://shopify/ProductVariant/123')
        ->and($dto->shopifyProductId)->toBe('gid://shopify/Product/999')
        ->and($dto->title)->toBe('Large / Red')
        ->and($dto->price)->toBe('29.99')
        ->and($dto->sku)->toBe('SKU-001')
        ->and($dto->inventoryQuantity)->toBe(5);
});

it('applies defaults when optional fields are missing', function (): void {
    $dto = ProductVariantDTO::fromGraphQL(
        ['id' => 'gid://shopify/ProductVariant/1'],
        'gid://shopify/Product/1'
    );

    expect($dto->title)->toBe('Default Title')
        ->and($dto->price)->toBe('0.00')
        ->and($dto->sku)->toBeNull()
        ->and($dto->inventoryQuantity)->toBe(0);
});
