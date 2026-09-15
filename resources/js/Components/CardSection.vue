<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
}>();
</script>

<template>
    <section class="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <header class="flex flex-wrap items-start justify-between gap-3 border-b px-5 py-4 sm:px-6">
            <div>
                <h2 class="font-semibold tracking-tight">{{ title }}</h2>
                <p v-if="subtitle" class="mt-1 text-sm text-muted-foreground">{{ subtitle }}</p>
            </div>
            <slot name="actions" />
        </header>
        <div class="p-5 sm:p-6">
            <slot />
        </div>
    </section>
</template>
