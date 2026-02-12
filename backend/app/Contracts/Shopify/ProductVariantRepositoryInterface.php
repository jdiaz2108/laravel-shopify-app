<?php

declare(strict_types=1);

namespace App\Contracts\Shopify;

use App\DTOs\Shopify\ProductVariantDTO;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

interface ProductVariantRepositoryInterface
{
    public function findByShopifyId(string $shopifyId): ?ProductVariant;

    public function getByProduct(Product $product): Collection;

    public function createFromDTO(Product $product, ProductVariantDTO $dto): ProductVariant;

    public function updateFromDTO(ProductVariant $variant, ProductVariantDTO $dto): ProductVariant;

    public function syncForProduct(Product $product, Collection $variantDTOs): void;
}
