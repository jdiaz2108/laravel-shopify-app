<?php

declare(strict_types=1);

use App\DTOs\Shopify\ShopifyProductDTO;
use App\DTOs\Shopify\ProductVariantDTO;
use Illuminate\Support\Collection;

it('can be constructed via from graphQL', function (): void {
    $dto = ShopifyProductDTO::fromGraphQL([
        'id' => 'gid://shopify/Product/123',
        'title' => 'Test product',
        'bodyHtml' => '<p>Description</p>',
        'vendor' => 'Test vendor',
        'productType' => 'Shoes',
        'handle' => 'test_product',
        'status' => 'active',
        'featuredImage' => ['url' => 'https://img.shopify.com.png'],
        'cursor' => 'test_cursor',
        'variants' => [
            'edges' => [
                [
                    'node' => [
                        'id' => 'gid://shopify/ProductVariant/1',
                        'title' => 'Large',
                        'price' => '19.99',
                        'sku' => 'SKU-001',
                        'inventoryQuantity' => 10,
                    ],
                ],
            ],
        ],
    ]);

    expect($dto->shopifyId)->toBe('gid://shopify/Product/123')
        ->and($dto->title)->toBe('Test product')
        ->and($dto->bodyHtml)->toBe('<p>Description</p>')
        ->and($dto->vendor)->toBe('Test vendor')
        ->and($dto->productType)->toBe('Shoes')
        ->and($dto->handle)->toBe('test_product')
        ->and($dto->status)->toBe('active')
        ->and($dto->imageUrl)->toBe('https://img.shopify.com.png')
        ->and($dto->cursor)->toBe('test_cursor')
        ->and($dto->variants)->toBeInstanceOf(Collection::class)
        ->and($dto->variants)->toHaveCount(1)
        ->and($dto->variants->first())->toBeInstanceOf(ProductVariantDTO::class);
});

it('applies defaults when optional fields are missing', function (): void {
    $dto = ShopifyProductDTO::fromGraphQL([
        'id'    => 'gid://shopify/Product/1',
        'title' => 'Minimal product',
    ]);

    expect($dto->bodyHtml)->toBeNull()
        ->and($dto->vendor)->toBe('')
        ->and($dto->productType)->toBe('')
        ->and($dto->handle)->toBe('')
        ->and($dto->status)->toBe('active')
        ->and($dto->imageUrl)->toBeNull()
        ->and($dto->cursor)->toBeNull()
        ->and($dto->variants)->toBeInstanceOf(Collection::class)
        ->and($dto->variants)->toBeEmpty();
});
