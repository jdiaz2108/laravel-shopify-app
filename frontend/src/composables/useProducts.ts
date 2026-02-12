import { ref, computed, watch } from 'vue'
import { graphqlClient, GraphQLError } from '@/services/graphqlClient'
import { GET_PRODUCTS } from '@/graphql/products'
import type { Product, PaginatedProducts, ProductFilters } from '@/types/shopify'

interface UseProductsOptions {
  first?: number
  filter?: ProductFilters
}

export function useProducts(options: UseProductsOptions = {}) {
  const products = ref<Product[]>([])
  const currentPage = ref(1)
  const lastPage = ref(1)
  const total = ref(0)
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const productFilter = ref<ProductFilters>(options.filter ?? {})
  const perPage = options.first ?? 12

  const hasNextPage = computed(() => currentPage.value < lastPage.value)
  const hasPrevPage = computed(() => currentPage.value > 1)
  const isEmpty = computed(() => !isLoading.value && products.value.length === 0)

  async function fetch(page = 1): Promise<void> {
    isLoading.value = true
    error.value = null

    try {
      const data = await graphqlClient.request<{ products: PaginatedProducts }>(GET_PRODUCTS, {
        page,
        first: perPage,
        filter: Object.keys(productFilter.value).length > 0 ? productFilter.value : undefined,
      })

      products.value = data.products.data
      console.log('Fetched products:', products.value) // Debug log
      currentPage.value = data.products.paginatorInfo.currentPage
      lastPage.value = data.products.paginatorInfo.lastPage
      total.value = data.products.paginatorInfo.total
    } catch (err) {
      error.value = err instanceof GraphQLError ? err.message : 'An unexpected error occurred.'
    } finally {
      isLoading.value = false
    }
  }

  function nextPage(): void {
    if (hasNextPage.value) fetch(currentPage.value + 1)
  }

  function prevPage(): void {
    if (hasPrevPage.value) fetch(currentPage.value - 1)
  }

  function goToPage(page: number): void {
    fetch(page)
  }

  function setFilter(filter: ProductFilters): void {
    productFilter.value = filter
    fetch(1)
  }

  function updateFilter(partialFilter: Partial<ProductFilters>): void {
    productFilter.value = { ...productFilter.value, ...partialFilter }
    fetch(1)
  }

  function clearFilter(): void {
    productFilter.value = {}
    fetch(1)
  }

  watch(productFilter, () => fetch(1), { deep: true })

  return {
    products,
    currentPage,
    lastPage,
    total,
    isLoading,
    error,
    hasNextPage,
    hasPrevPage,
    isEmpty,
    productFilter,
    fetch,
    nextPage,
    prevPage,
    goToPage,
    setFilter,
    updateFilter,
    clearFilter,
  }
}
