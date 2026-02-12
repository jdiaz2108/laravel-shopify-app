<template>
  <div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Products</h1>
        <p class="mt-1 text-sm text-slate-500">{{ total }} products synced from Shopify</p>
      </div>

      <div class="flex items-center gap-2">
        <button
          v-for="filter in STATUS_FILTERS"
          :key="filter.value ?? 'all'"
          :class="[
            'rounded-lg px-3 py-1.5 text-sm font-medium transition cursor-pointer',
            (productFilter.status ?? null) === filter.value
              ? 'bg-indigo-600 text-white'
              : 'border border-slate-200 text-slate-600 hover:bg-slate-50',
          ]"
          @click="handleStatusFilter(filter.value)"
        >
          {{ filter.label }}
        </button>
      </div>
    </div>

    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      {{ error }}
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <template v-if="isLoading">
        <ProductCardSkeleton v-for="n in 12" :key="n" />
      </template>

      <template v-else-if="isEmpty">
        <div class="col-span-full py-16 text-center text-slate-400">
          <p class="text-lg font-medium">No products found</p>
          <p class="mt-1 text-sm">Try adjusting the filter above</p>
        </div>
      </template>

      <template v-else>
        <ProductCard v-for="product in products" :key="product.slug" :product="product" />
      </template>
    </div>

    <AppPagination
      v-if="!isLoading && !isEmpty"
      :current-page="currentPage"
      :last-page="lastPage"
      :total="total"
      :has-prev-page="hasPrevPage"
      :has-next-page="hasNextPage"
      @prev="prevPage"
      @next="nextPage"
      @goto="goToPage"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import ProductCard from '@/components/product/ProductCard.vue'
import ProductCardSkeleton from '@/components/product/ProductCardSkeleton.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import { useProducts } from '@/composables/useProducts'

const STATUS_FILTERS = [
  { label: 'All', value: null },
  { label: 'Active', value: 'active' },
  { label: 'Draft', value: 'draft' },
  { label: 'Archived', value: 'archived' },
]

const {
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
  updateFilter,
  clearFilter,
} = useProducts({ first: 12 })

function handleStatusFilter(status: string | null) {
  if (status === null) {
    clearFilter()
  } else {
    updateFilter({ status })
  }
}

onMounted(() => fetch())
</script>
