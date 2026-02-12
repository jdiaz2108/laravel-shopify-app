<template>
  <div>
    <RouterLink
      :to="{ name: 'products.index' }"
      class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800"
    >
      ← Back to products
    </RouterLink>

    <div v-if="isLoading" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
      <div class="h-96 animate-pulse rounded-2xl bg-slate-100" />
      <div class="space-y-4">
        <div class="h-4 w-1/4 animate-pulse rounded bg-slate-100" />
        <div class="h-8 w-3/4 animate-pulse rounded bg-slate-100" />
        <div class="h-4 w-1/2 animate-pulse rounded bg-slate-100" />
      </div>
    </div>

    <div
      v-else-if="error"
      class="mt-8 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      {{ error }}
    </div>

    <template v-else-if="product">
      <div class="mt-6 grid grid-cols-1 gap-10 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl bg-slate-100 max-h-72">
          <img
            v-if="product.featured_image_url"
            :src="product.featured_image_url"
            :alt="product.title"
            class="h-full w-full object-contain"
          />
          <div v-else class="flex h-80 items-center justify-center text-slate-300">
            <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3">
            <StatusBadge :status="product.status" />
            <span class="text-sm text-slate-500">{{ product.vendor }}</span>
          </div>

          <h1 class="text-3xl font-bold text-slate-900">
            {{ product.title }}
          </h1>

          <div class="flex flex-wrap gap-3 text-sm text-slate-500">
            <span v-if="product.product_type">
              Type: <strong class="text-slate-700">{{ product.product_type }}</strong>
            </span>
            <span>
              Handle: <strong class="text-slate-700">{{ product.handle }}</strong>
            </span>
          </div>

          <div
            v-if="product.description"
            class="prose prose-sm max-w-none rounded-xl border border-slate-100 bg-slate-50 p-4 text-slate-600"
            v-html="product.description"
          />
        </div>
      </div>

      <div class="mt-10">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">
          Variants ({{ product.variants.length }})
        </h2>

        <div class="overflow-hidden rounded-2xl border border-slate-200">
          <table class="w-full text-sm">
            <thead
              class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500"
            >
              <tr>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">SKU</th>
                <th class="px-4 py-3 text-left">Price</th>
                <th class="px-4 py-3 text-left">Stock</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
              <tr v-for="variant in product.variants" :key="variant.slug" class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium text-slate-800">
                  {{ variant.title }}
                </td>
                <td class="px-4 py-3 font-mono text-slate-500">
                  {{ variant.sku ?? '—' }}
                </td>
                <td class="px-4 py-3 font-semibold text-slate-800">
                  {{ formatPrice(variant.price) }}
                </td>
                <td class="px-4 py-3">
                  <span
                    :class="[
                      'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                      variant.inventory_quantity > 0
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-red-100 text-red-600',
                    ]"
                  >
                    {{
                      variant.inventory_quantity > 0
                        ? `${variant.inventory_quantity} in stock`
                        : 'Out of stock'
                    }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import StatusBadge from '@/components/ui/StatusBadge.vue'
import { useProduct } from '@/composables/useProduct'

const route = useRoute()
const { product, isLoading, error, fetch } = useProduct()

onMounted(() => fetch(route.params.slug as string))

function formatPrice(price: string): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(parseFloat(price))
}
</script>
