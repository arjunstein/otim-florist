<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
}>();
</script>

<template>
    <section class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <header class="flex flex-wrap items-start justify-between gap-2 border-b border-stone-100 px-5 py-4">
            <div>
                <h2 class="font-semibold">{{ title }}</h2>
                <p v-if="subtitle" class="mt-0.5 text-xs text-stone-500">{{ subtitle }}</p>
            </div>
            <slot name="actions" />
        </header>
        <div class="p-5">
            <slot />
        </div>
    </section>
</template>
