import { ref } from 'vue'
import { graphqlClient, GraphQLError } from '@/services/graphqlClient'
import { GET_PRODUCT } from '@/graphql/products'
import type { Product } from '@/types/shopify'

export function useProduct() {
  const product = ref<Product | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  async function fetch(slug: string | number): Promise<void> {
    isLoading.value = true
    error.value = null
    product.value = null

    try {
      const data = await graphqlClient.request<{ product: Product }>(GET_PRODUCT, { slug })

      product.value = data.product
    } catch (err) {
      error.value = err instanceof GraphQLError ? err.message : 'An unexpected error occurred.'
    } finally {
      isLoading.value = false
    }
  }

  return {
    product,
    isLoading,
    error,
    fetch,
  }
}
