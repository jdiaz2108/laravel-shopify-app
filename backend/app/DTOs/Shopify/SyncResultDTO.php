<?php

declare(strict_types=1);

namespace App\DTOs\Shopify;

final readonly class SyncResultDTO
{
    public function __construct(
        public int $created,
        public int $updated,
        public int $failed,
        public int $total,
    ) {}

    public static function empty(): self
    {
        return new self(created: 0, updated: 0, failed: 0, total: 0);
    }

    public function hasFailures(): bool
    {
        return $this->failed > 0;
    }

    public function wasSuccessful(): bool
    {
        return $this->total > 0 && $this->failed === 0;
    }

    public function processedCount(): int
    {
        return $this->created + $this->updated;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'created' => $this->created,
            'updated' => $this->updated,
            'failed' => $this->failed,
        ];
    }
}
