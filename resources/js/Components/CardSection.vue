<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
}>();
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-border/80 bg-card/85 shadow-2xs backdrop-blur-xs">
        <header class="flex flex-wrap items-start justify-between gap-3 border-b border-border/80 px-5 py-4 sm:px-6">
            <div>
                <h2 class="font-semibold tracking-tight text-foreground">{{ title }}</h2>
                <p v-if="subtitle" class="mt-1 text-sm text-muted-foreground">{{ subtitle }}</p>
            </div>
            <slot name="actions" />
        </header>
        <div class="p-5 sm:p-6">
            <slot />
        </div>
    </section>
</template>
