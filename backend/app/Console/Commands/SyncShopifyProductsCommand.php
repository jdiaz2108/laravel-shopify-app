<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\Shopify\SyncResultDTO;
use App\Services\Shopify\ProductSyncService;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class SyncShopifyProductsCommand extends Command
{
    protected $signature = 'shopify:sync-products
                            {--shopify-id= : Sync a single product by its Shopify ID}
                            {--batch-size=50 : Number of products to fetch per page (max 250)}';

    protected $description = 'Sync products from Shopify into the local database';

    public function __construct(
        private readonly ProductSyncService $productSyncService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $shopifyId = $this->option('shopify-id');

        $this->info('Starting Shopify product sync...');

        $this->line($shopifyId
            ? "Syncing product with Shopify ID: <comment>{$shopifyId}</comment>"
            : 'Syncing all products...');

        try {
            $result = $shopifyId
                ? $this->productSyncService->syncOne($shopifyId)
                : $this->productSyncService->syncAll();

            $this->renderResultTable($result);

            if ($result->hasFailures()) {
                $this->warn("Sync completed with {$result->failed} failure(s). Check the logs for details.");

                return self::FAILURE;
            }

            $this->info('Sync completed successfully.');

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error("Shopify API error: {$e->getMessage()}");

            return self::FAILURE;
        } catch (Throwable $e) {
            $this->error("Unexpected error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function renderResultTable(SyncResultDTO $result): void
    {
        $this->table(
            headers: ['Total', 'Created', 'Updated', 'Failed'],
            rows: [[
                $result->total,
                $result->created,
                $result->updated,
                $result->failed > 0 ? $result->failed : '0',
            ]],
        );
    }
}
