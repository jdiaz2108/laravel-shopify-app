<template>
  <RouterLink
    :to="{ name: 'products.show', params: { slug: product.slug } }"
    class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
  >
    <div class="relative h-52 w-full overflow-hidden bg-slate-100">
      <img
        v-if="product.featured_image_url"
        :src="product.featured_image_url"
        :alt="product.title"
        class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-slate-300">
        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
          />
        </svg>
      </div>

      <StatusBadge :status="product.status" class="absolute left-3 top-3" />
    </div>

    <div class="flex flex-1 flex-col gap-2 p-4">
      <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
        {{ product.vendor }}
      </p>

      <h3 class="line-clamp-2 text-sm font-semibold text-slate-800 group-hover:text-indigo-600">
        {{ product.title }}
      </h3>

      <p v-if="product.product_type" class="text-xs text-slate-500">
        {{ product.product_type }}
      </p>

      <div class="mt-auto flex items-center justify-between pt-3 border-t border-slate-100">
        <span class="text-sm font-bold text-slate-900">
          {{ lowestPrice }}
        </span>
        <span class="text-xs text-slate-400">
          {{ product.variants.length }} variant{{ product.variants.length !== 1 ? 's' : '' }}
        </span>
      </div>
    </div>
  </RouterLink>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import StatusBadge from '@/components/ui/StatusBadge.vue'
import type { Product } from '@/types/shopify'

const props = defineProps<{ product: Product }>()

const lowestPrice = computed(() => {
  if (!props.product.variants.length) return '—'

  const prices = props.product.variants.map((v) => parseFloat(v.price))
  const min = Math.min(...prices)

  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(min)
})
</script>
