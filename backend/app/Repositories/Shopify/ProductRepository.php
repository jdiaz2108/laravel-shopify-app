<?php

declare(strict_types=1);

namespace App\Repositories\Shopify;

use App\Contracts\Shopify\ProductRepositoryInterface;
use App\Contracts\Shopify\ProductVariantRepositoryInterface;
use App\DTOs\Shopify\ShopifyProductDTO;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly ProductVariantRepositoryInterface $variantRepository,
    ) {}

    public function findByShopifyId(string $shopifyId): ?Product
    {
        return Product::query()
            ->where('shopify_id', $shopifyId)
            ->first();
    }

    public function findById(int $id): ?Product
    {
        return Product::query()->find($id);
    }

    public function getAll(?string $status = null): Collection
    {
        return Product::query()
            ->when(
                value: $status !== null,
                callback: fn($query) => $query->where('status', $status),
            )
            ->orderBy('title')
            ->get();
    }

    public function createFromDTO(ShopifyProductDTO $dto): Product
    {
        $product = Product::query()->create($this->mapDtoToAttributes($dto));

        $this->variantRepository->syncForProduct($product, $dto->variants);

        return $product;
    }

    public function updateFromDTO(Product $product, ShopifyProductDTO $dto): Product
    {
        $product->update($this->mapDtoToAttributes($dto));

        $this->variantRepository->syncForProduct($product, $dto->variants);

        return $product->refresh();
    }

    public function existsByShopifyId(string $shopifyId): bool
    {
        return Product::query()
            ->where('shopify_id', $shopifyId)
            ->exists();
    }

    private function mapDtoToAttributes(ShopifyProductDTO $dto): array
    {
        return [
            'shopify_id' => $dto->shopifyId,
            'title' => $dto->title,
            'description' => $dto->bodyHtml,
            'vendor' => $dto->vendor,
            'product_type' => $dto->productType,
            'handle' => $dto->handle,
            'status' => $dto->status,
            'featured_image_url' => $dto->imageUrl,
            'synced_at' => now(),
        ];
    }
}
