<template>
  <span :class="['inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium', styles]">
    {{ label }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  status: 'active' | 'draft' | 'archived' | string
}>()

const config: Record<string, { label: string; styles: string }> = {
  active: { label: 'Active', styles: 'bg-emerald-100 text-emerald-700' },
  draft: { label: 'Draft', styles: 'bg-amber-100 text-amber-700' },
  archived: { label: 'Archived', styles: 'bg-slate-100 text-slate-500' },
}

const normalizedStatus = computed(() => props.status.toLowerCase())
const current = computed(() => config[normalizedStatus.value] ?? config['archived'])
const label = computed(() => current.value.label)
const styles = computed(() => current.value.styles)
</script>
