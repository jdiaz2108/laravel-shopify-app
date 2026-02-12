<?php

declare(strict_types=1);

use App\DTOs\Shopify\ShopifyProductDTO;
use App\Models\Product;
use App\Repositories\Shopify\ProductRepository;
use App\Contracts\Shopify\ProductVariantRepositoryInterface;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    $variantRepository = Mockery::mock(ProductVariantRepositoryInterface::class);
    $variantRepository->shouldReceive('syncForProduct')->andReturnNull();
    $this->repository = new ProductRepository($variantRepository);
});

function makeProductDTO(array $overrides = []): ShopifyProductDTO
{
    return ShopifyProductDTO::fromGraphQL(array_merge([
        'id' => 'gid://shopify/Product/123',
        'title' => 'Test product',
        'vendor' => 'Test vendor',
        'productType' => 'Shoes',
        'handle' => 'test_product',
        'status' => 'active',
    ], $overrides));
}

it('finds a product by shopify id', function (): void {
    $product = Product::factory()->create(['shopify_id' => 'gid://shopify/Product/123']);
    $result = $this->repository->findByShopifyId('gid://shopify/Product/123');

    expect($result)->not->toBeNull()
        ->and($result->id)->toBe($product->id);
});

it('returns null when product is not found by shopify id', function (): void {
    $result = $this->repository->findByShopifyId('gid://shopify/Product/999');

    expect($result)->toBeNull();
});

it('finds a product by id', function (): void {
    $product = Product::factory()->create();
    $result = $this->repository->findById($product->id);

    expect($result)->not->toBeNull()
        ->and($result->id)->toBe($product->id);
});

it('returns null when product is not found by id', function (): void {
    $result = $this->repository->findById(999);
    expect($result)->toBeNull();
});

it('returns all products ordered by title', function (): void {
    Product::factory()->create(['title' => 'Gift Card']);
    Product::factory()->create(['title' => 'Selling Plans Ski Wax']);
    Product::factory()->create(['title' => 'The Videographer Snowboard']);

    $results = $this->repository->getAll();

    expect($results)->toBeInstanceOf(Collection::class)
        ->and($results->pluck('title')->toArray())->toBe(['Gift Card', 'Selling Plans Ski Wax', 'The Videographer Snowboard']);
});

it('filters products by status', function (): void {
    Product::factory()->create(['status' => 'active']);
    Product::factory()->create(['status' => 'active']);
    Product::factory()->create(['status' => 'draft']);

    $results = $this->repository->getAll(status: 'active');
    expect($results)->toHaveCount(2);
});

it('returns all products when status is null', function (): void {
    Product::factory()->count(3)->create();
    $results = $this->repository->getAll();

    expect($results)->toHaveCount(3);
});

it('creates a product from a DTO', function (): void {
    $dto = makeProductDTO();

    $product = $this->repository->createFromDTO($dto);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->shopify_id)->toBe('gid://shopify/Product/123')
        ->and($product->title)->toBe('Test product')
        ->and($product->status)->toBe('active');
});

it('persists the product to the database on create', function (): void {
    $dto = makeProductDTO();

    $this->repository->createFromDTO($dto);

    $this->assertDatabaseHas('products', [
        'shopify_id' => 'gid://shopify/Product/123',
        'title'      => 'Test product',
    ]);
});

it('returns true when product exists by shopify id', function (): void {
    Product::factory()->create(['shopify_id' => 'gid://shopify/Product/123']);

    expect($this->repository->existsByShopifyId('gid://shopify/Product/123'))->toBeTrue();
});
