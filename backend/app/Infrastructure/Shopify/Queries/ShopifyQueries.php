<?php

declare(strict_types=1);

namespace App\Infrastructure\Shopify\Queries;

class ShopifyQueries
{
    public static function productsQuery(): string
    {
        return <<<'GRAPHQL'
        query GetProducts($first: Int!, $after: String) {
            products(first: $first, after: $after) {
                edges {
                    cursor
                    node {
                        id
                        title
                        bodyHtml
                        vendor
                        productType
                        handle
                        status
                        featuredImage {
                            url
                        }
                        variants(first: 50) {
                            edges {
                                node {
                                    id
                                    title
                                    price
                                    sku
                                    inventoryQuantity
                                    selectedOptions {
                                        name
                                        value
                                    }
                                }
                            }
                        }
                    }
                }
                pageInfo {
                    hasNextPage
                    endCursor
                }
            }
        }
        GRAPHQL;
    }

    public static function productQuery(): string
    {
        return <<<'GRAPHQL'
        query GetProduct($id: ID!) {
            product(id: $id) {
                id
                title
                bodyHtml
                vendor
                productType
                handle
                status
                featuredImage {
                    url
                }
                variants(first: 50) {
                    edges {
                        node {
                            id
                            title
                            price
                            sku
                            inventoryQuantity
                            selectedOptions {
                                name
                                value
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;
    }
}
