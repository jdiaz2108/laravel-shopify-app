<?php

declare(strict_types=1);

namespace App\DTOs\Shopify;

use Illuminate\Support\Collection;

final readonly class ShopifyProductDTO
{
    public function __construct(
        public string $shopifyId,
        public string $title,
        public ?string $bodyHtml,
        public string $vendor,
        public string $productType,
        public string $handle,
        public string $status,
        public ?string $imageUrl,
        public Collection $variants,
        public ?string $cursor = null,
    ) {}

    public static function fromGraphQL(array $node): self
    {
        $shopifyId = $node['id'];

        return new self(
            shopifyId: $shopifyId,
            title: $node['title'],
            bodyHtml: $node['bodyHtml'] ?? null,
            vendor: $node['vendor'] ?? '',
            productType: $node['productType'] ?? '',
            handle: $node['handle'] ?? '',
            status: strtolower($node['status'] ?? 'active'),
            imageUrl: $node['featuredImage']['url'] ?? null,
            variants: self::resolveVariants($node, $shopifyId),
            cursor: $node['cursor'] ?? null,
        );
    }

    private static function resolveVariants(array $node, string $shopifyProductId): Collection
    {
        $edges = $node['variants']['edges'] ?? [];

        return collect($edges)
            ->map(fn(array $edge) => ProductVariantDTO::fromGraphQL($edge['node'], $shopifyProductId));
    }
}
