<template>
  <div class="flex items-center justify-between py-4">
    <p class="text-sm text-slate-500">
      Page <span class="font-medium text-slate-700">{{ currentPage }}</span> of
      <span class="font-medium text-slate-700">{{ lastPage }}</span> &mdash;
      <span class="font-medium text-slate-700">{{ total }}</span> total
    </p>

    <div class="flex items-center gap-2">
      <button
        :disabled="!hasPrevPage"
        class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 cursor-pointer"
        @click="$emit('prev')"
      >
        ← Prev
      </button>

      <button
        v-for="page in visiblePages"
        :key="page"
        :class="[
          'rounded-lg px-3 py-1.5 text-sm font-medium transition cursor-pointer',
          page === currentPage
            ? 'bg-indigo-600 text-white shadow-sm'
            : 'border border-slate-200 text-slate-600 hover:bg-slate-50',
        ]"
        @click="$emit('goto', page)"
      >
        {{ page }}
      </button>

      <button
        :disabled="!hasNextPage"
        class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 cursor-pointer"
        @click="$emit('next')"
      >
        Next →
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  currentPage: number
  lastPage: number
  total: number
  hasPrevPage: boolean
  hasNextPage: boolean
}>()

defineEmits<{
  prev: []
  next: []
  goto: [page: number]
}>()

const visiblePages = computed(() => {
  const range = 2
  const start = Math.max(1, props.currentPage - range)
  const end = Math.min(props.lastPage, props.currentPage + range)
  const pages: number[] = []

  for (let i = start; i <= end; i++) pages.push(i)

  return pages
})
</script>
