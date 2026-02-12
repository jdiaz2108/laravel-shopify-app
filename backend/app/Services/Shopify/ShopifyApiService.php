<?php

declare(strict_types=1);

namespace App\Services\Shopify;

use App\DTOs\Shopify\ShopifyProductDTO;
use App\Infrastructure\Shopify\ShopifyClient;
use Illuminate\Support\Collection;

final class ShopifyApiService
{
    public function __construct(
        private readonly ShopifyClient $client,
    ) {}

    public function getAllProducts(int $batchSize = 50): Collection
    {
        $allProducts = collect();
        $pageInfo = null;
        $page = 1;

        do {
            $products = $this->client->getProducts($batchSize, $pageInfo);

            $allProducts = $allProducts->merge($products);

            $pageInfo = $products->count() < $batchSize ? null : $this->resolveNextPageInfo($products);
            $page++;
        } while ($pageInfo !== null);

        return $allProducts;
    }

    public function findProduct(string $shopifyId): ?ShopifyProductDTO
    {
        return $this->client->getProduct($shopifyId);
    }

    public function getProductPage(int $limit = 50, ?string $pageInfo = null): Collection
    {
        return $this->client->getProducts($limit, $pageInfo);
    }

    public function productExists(string $shopifyId): bool
    {
        return $this->findProduct($shopifyId) !== null;
    }

    private function resolveNextPageInfo(Collection $products): ?string
    {
        $last = $products->last();

        return $last?->pageInfo ?? null;
    }
}
