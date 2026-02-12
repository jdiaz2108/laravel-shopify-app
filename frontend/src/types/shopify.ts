export interface ProductVariant {
  slug: number
  shopify_id: string
  title: string
  sku: string | null
  price: string
  inventory_quantity: number
  synced_at: string
  created_at: string
  updated_at: string
}

export interface Product {
  slug: number
  shopify_id: string
  title: string
  description: string | null
  vendor: string
  product_type: string
  handle: string
  status: 'active' | 'draft' | 'archived'
  featured_image_url: string | null
  variants: ProductVariant[]
  synced_at: string
  created_at: string
  updated_at: string
}

export interface PaginationInfo {
  currentPage: number
  lastPage: number
  perPage: number
  total: number
}

export interface PaginatedProducts {
  data: Product[]
  paginatorInfo: PaginationInfo
}

export interface ProductFilters {
  slug?: string | number
  shopify_id?: string
  status?: string
  title?: string
  vendor?: string
}
