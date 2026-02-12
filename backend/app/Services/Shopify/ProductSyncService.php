<?php

declare(strict_types=1);

namespace App\Services\Shopify;

use App\Contracts\Shopify\ProductRepositoryInterface;
use App\DTOs\Shopify\SyncResultDTO;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ProductSyncService
{
    public function __construct(
        private readonly ShopifyApiService $shopifyApiService,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function syncAll(): SyncResultDTO
    {
        try {
            $products = $this->shopifyApiService->getAllProducts();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch products from Shopify: {$e->getMessage()}", previous: $e);
        }

        return $this->syncCollection($products);
    }

    public function syncOne(string $shopifyId): SyncResultDTO
    {
        $product = $this->shopifyApiService->findProduct($shopifyId);

        if ($product === null) {
            throw new Exception("Product with Shopify ID {$shopifyId} not found");
        }

        return $this->syncCollection(collect([$product]));
    }

    private function syncCollection(Collection $products): SyncResultDTO
    {
        $created = 0;
        $updated = 0;
        $failed = 0;

        foreach ($products as $product) {
            try {
                DB::transaction(function () use ($product, &$created, &$updated): void {
                    $existing = $this->productRepository->findByShopifyId($product->shopifyId);

                    if ($existing === null) {
                        $this->productRepository->createFromDTO($product);
                        $created++;
                    } else {
                        $this->productRepository->updateFromDTO($existing, $product);
                        $updated++;
                    }
                });
            } catch (Throwable $e) {
                $failed++;
            }
        }

        $result = new SyncResultDTO(
            created: $created,
            updated: $updated,
            failed: $failed,
            total: $products->count(),
        );

        return $result;
    }
}
