<script setup lang="ts">
defineProps<{
    label: string;
    value: string;
    delta: string;
    up: boolean;
    icon: string;
}>();
</script>

<template>
    <article class="rounded-xl border border-stone-200 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-stone-500">{{ label }}</p>
            <span class="grid size-9 place-items-center rounded-lg bg-emerald-50 text-lg">
                {{ icon }}
            </span>
        </div>
        <p class="mt-2 text-2xl font-semibold tracking-tight">{{ value }}</p>
        <p :class="['mt-1 text-xs font-medium', up ? 'text-emerald-600' : 'text-stone-500']">
            {{ delta }}
        </p>
    </article>
</template>
