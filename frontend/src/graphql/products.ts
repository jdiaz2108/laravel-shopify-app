export const GET_PRODUCTS = `
    query GetProducts($page: Int, $first: Int, $filter: ProductFilter) {
        products(page: $page, first: $first, filter: $filter) {
            data {
                slug
                shopify_id
                title
                description
                vendor
                product_type
                handle
                status
                featured_image_url
                synced_at
                created_at
                updated_at
                variants {
                    slug
                    shopify_id
                    title
                    sku
                    price
                    inventory_quantity
                    synced_at
                    created_at
                    updated_at
                }
            }
            paginatorInfo {
                  count
                  currentPage
                  firstItem
                  hasMorePages
                  lastItem
                  lastPage
                  perPage
                  total
            }
        }
    }
`

export const GET_PRODUCT = `
    query GetProduct($slug: ID!) {
        product(slug: $slug) {
            slug
            shopify_id
            title
            description
            vendor
            product_type
            handle
            status
            featured_image_url
            synced_at
            created_at
            updated_at
            variants {
                slug
                shopify_id
                title
                sku
                price
                inventory_quantity
                synced_at
                created_at
                updated_at
            }
        }
    }
`
