<?php

declare(strict_types=1);

namespace App\Repositories\Shopify;

use App\Contracts\Shopify\ProductVariantRepositoryInterface;
use App\DTOs\Shopify\ProductVariantDTO;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function findByShopifyId(string $shopifyId): ?ProductVariant
    {
        return ProductVariant::query()
            ->where('shopify_id', $shopifyId)
            ->first();
    }

    public function getByProduct(Product $product): Collection
    {
        return ProductVariant::query()
            ->where('product_id', $product->id)
            ->get();
    }

    public function createFromDTO(Product $product, ProductVariantDTO $dto): ProductVariant
    {
        return ProductVariant::query()->create(
            $this->mapDtoToAttributes($product, $dto)
        );
    }

    public function updateFromDTO(ProductVariant $variant, ProductVariantDTO $dto): ProductVariant
    {
        $variant->update($this->mapDtoToAttributes($variant->product, $dto));

        return $variant->refresh();
    }

    public function syncForProduct(Product $product, Collection $variantDTOs): void
    {
        $incomingShopifyIds = $variantDTOs->pluck('shopifyId');

        foreach ($variantDTOs as $dto) {
            $existing = $this->findByShopifyId($dto->shopifyId);

            $existing
                ? $this->updateFromDTO($existing, $dto)
                : $this->createFromDTO($product, $dto);
        }

        ProductVariant::query()
            ->where('product_id', $product->id)
            ->whereNotIn('shopify_id', $incomingShopifyIds)
            ->delete();
    }

    private function mapDtoToAttributes(Product $product, ProductVariantDTO $dto): array
    {
        return [
            'product_id' => $product->id,
            'shopify_id' => $dto->shopifyId,
            'title' => $dto->title,
            'sku' => $dto->sku,
            'price' => $dto->price,
            'inventory_quantity' => $dto->inventoryQuantity,
            'synced_at' => now(),
        ];
    }
}
