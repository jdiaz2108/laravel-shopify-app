<?php

declare(strict_types=1);

use App\DTOs\Shopify\ProductVariantDTO;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Shopify\ProductVariantRepository;
use Illuminate\Support\Collection;


function makeVariantDTO(array $overrides = []): ProductVariantDTO
{
    return ProductVariantDTO::fromGraphQL(
        array_merge([
            'id' => 'gid://shopify/ProductVariant/1',
            'title' => 'Large',
            'price' => '19.99',
            'sku' => 'SKU-001',
            'inventoryQuantity' => 10,
        ], $overrides),
        'gid://shopify/Product/123'
    );
}

beforeEach(function (): void {
    $this->repository = new ProductVariantRepository();
});

it('finds a variant by shopify id', function (): void {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
        'shopify_id' => 'gid://shopify/ProductVariant/1',
    ]);

    $result = $this->repository->findByShopifyId('gid://shopify/ProductVariant/1');

    expect($result)->not->toBeNull()
        ->and($result->id)->toBe($variant->id);
});

it('returns null when variant is not found by shopify id', function (): void {
    $result = $this->repository->findByShopifyId('gid://shopify/ProductVariant/999');

    expect($result)->toBeNull();
});

it('returns all variants for a product', function (): void {
    $product = Product::factory()->create();
    ProductVariant::factory()->count(3)->create(['product_id' => $product->id]);

    $results = $this->repository->getByProduct($product);

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results)->toHaveCount(3);
});

it('returns only variants belonging to the given product', function (): void {
    $product = Product::factory()->create();
    $otherProduct = Product::factory()->create();

    ProductVariant::factory()->count(2)->create(['product_id' => $product->id]);
    ProductVariant::factory()->count(3)->create(['product_id' => $otherProduct->id]);

    $results = $this->repository->getByProduct($product);

    expect($results)->toHaveCount(2);
});

it('creates a variant from a DTO', function (): void {
    $product = Product::factory()->create();
    $dto = makeVariantDTO();

    $variant = $this->repository->createFromDTO($product, $dto);

    expect($variant)->toBeInstanceOf(ProductVariant::class)
        ->and($variant->shopify_id)->toBe('gid://shopify/ProductVariant/1')
        ->and($variant->title)->toBe('Large')
        ->and($variant->price)->toBe('19.99');
});

it('persists the variant to the database on create', function (): void {
    $product = Product::factory()->create();
    $dto = makeVariantDTO();

    $this->repository->createFromDTO($product, $dto);

    $this->assertDatabaseHas('product_variants', [
        'shopify_id' => 'gid://shopify/ProductVariant/1',
        'product_id' => $product->id,
    ]);
});
