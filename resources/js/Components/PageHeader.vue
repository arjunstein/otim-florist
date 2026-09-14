<script setup lang="ts">
defineProps<{
    title: string;
    subtitle: string;
}>();
</script>

<template>
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold tracking-tight md:text-2xl">{{ title }}</h1>
            <p class="mt-0.5 text-sm text-stone-500">{{ subtitle }}</p>
        </div>
        <div class="flex items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
