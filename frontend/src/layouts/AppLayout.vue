<template>
  <div class="min-h-screen bg-slate-50">
    <nav class="sticky top-0 z-10 border-b border-slate-200 bg-white">
      <div class="mx-auto flex h-14 max-w-7xl items-center justify-between px-4 sm:px-6">
        <RouterLink
          :to="{ name: 'products.index' }"
          class="text-base font-bold text-slate-900 tracking-tight"
        >
          Shopify Sync
        </RouterLink>

        <div class="flex items-center gap-4 text-sm font-medium text-slate-600">
          <RouterLink
            :to="{ name: 'products.index' }"
            class="hover:text-indigo-600 font-bold"
            active-class="text-indigo-600 font-bold"
          >
            Products
          </RouterLink>
        </div>
      </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
      <RouterView />
    </main>
  </div>
</template>

<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>
