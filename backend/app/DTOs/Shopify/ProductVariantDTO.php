<?php

declare(strict_types=1);

namespace App\DTOs\Shopify;

final readonly class ProductVariantDTO
{
    public function __construct(
        public string $shopifyId,
        public string $shopifyProductId,
        public string $title,
        public string $price,
        public ?string $sku,
        public int $inventoryQuantity,
    ) {}

    public static function fromGraphQL(array $node, string $shopifyProductId): self
    {
        return new self(
            shopifyId: $node['id'],
            shopifyProductId: $shopifyProductId,
            title: $node['title'] ?? 'Default Title',
            price: $node['price'] ?? '0.00',
            sku: $node['sku'] ?? null,
            inventoryQuantity: (int) ($node['inventoryQuantity'] ?? 0),
        );
    }

    public function isInStock(): bool
    {
        return $this->inventoryQuantity > 0;
    }

    public function priceAsFloat(): float
    {
        return (float) $this->price;
    }
}
