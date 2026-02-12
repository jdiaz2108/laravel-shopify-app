<?php

declare(strict_types=1);

namespace App\Infrastructure\Shopify;

use App\DTOs\Shopify\ShopifyProductDTO;
use App\Infrastructure\Shopify\Queries\ShopifyQueries;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

final class ShopifyClient
{
    private const TOKEN_CACHE_KEY = 'shopify_access_token';
    private const TOKEN_BUFFER_SECS = 60;
    private const GRAPHQL_API_PATH = '/admin/api/2026-01/graphql.json';
    private const TOKEN_PATH = '/admin/oauth/access_token';

    private readonly string $shop;
    private readonly string $clientId;
    private readonly string $clientSecret;

    public function __construct()
    {
        $this->shop = config("shopify.shop");
        $this->clientId = config("shopify.client_id");
        $this->clientSecret = config("shopify.client_secret");
    }

    public function getProducts(int $limit = 50, ?string $cursor = null): Collection
    {
        $variables = array_filter([
            'first' => $limit,
            'after' => $cursor,
        ]);

        $data = $this->query(ShopifyQueries::productsQuery(), $variables);

        return collect($data['products']['edges'] ?? [])
            ->map(fn(array $edge) => ShopifyProductDTO::fromGraphQL($edge['node']));
    }

    public function getProduct(string $shopifyId): ?ShopifyProductDTO
    {
        $data = $this->query(ShopifyQueries::productQuery(), ['id' => $shopifyId]);

        $node = $data['product'] ?? null;

        return $node ? ShopifyProductDTO::fromGraphQL($node) : null;
    }

    public function query(string $query, array $variables = []): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->getToken(),
            ])->post($this->graphqlUrl(), [
                'query' => $query,
                'variables' => $variables,
            ]);

            if ($response->failed()) {
                throw new Exception('graphql-query' . $response->status() . ': ' . $response->body());
            }

            $json = $response->json();
            $errors = $json['errors'] ?? [];

            if (!empty($errors)) {
                throw new Exception('graphql-errors: ' . json_encode($errors));
            }

            return $json['data'] ?? [];
        } catch (Exception $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new Exception('Unexpected error: ' . $e->getMessage(), previous: $e);
        }
    }

    private function getToken(): string
    {
        return Cache::remember(
            key: self::TOKEN_CACHE_KEY,
            ttl: $this->tokenTtl(),
            callback: fn() => $this->fetchToken(),
        );
    }

    private function fetchToken(): string
    {
        $response = Http::asForm()->post($this->tokenUrl(), [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->failed()) {
            throw new Exception(
                'token-request' . $response->status() . ': ' . $response->body()
            );
        }

        $data = $response->json();

        Cache::put(
            key: self::TOKEN_CACHE_KEY . '_expires_in',
            value: (int) ($data['expires_in'] ?? 3600),
            ttl: now()->addHour(),
        );

        return $data['access_token'];
    }

    private function tokenTtl(): int
    {
        $expiresIn = (int) Cache::get(self::TOKEN_CACHE_KEY . '_expires_in', 3600);

        return max(1, $expiresIn - self::TOKEN_BUFFER_SECS);
    }

    private function graphqlUrl(): string
    {
        return "https://{$this->shop}" . self::GRAPHQL_API_PATH;
    }

    private function tokenUrl(): string
    {
        return "https://{$this->shop}" . self::TOKEN_PATH;
    }
}
