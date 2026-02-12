<?php

declare(strict_types=1);

namespace App\Contracts\Shopify;

use App\DTOs\Shopify\ShopifyProductDTO;
use Illuminate\Support\Collection;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function findByShopifyId(string $shopifyId): ?Product;

    public function findById(int $id): ?Product;

    public function getAll(?string $status = null): Collection;

    public function createFromDTO(ShopifyProductDTO $dto): Product;

    public function updateFromDTO(Product $product, ShopifyProductDTO $dto): Product;

    public function existsByShopifyId(string $shopifyId): bool;
}
