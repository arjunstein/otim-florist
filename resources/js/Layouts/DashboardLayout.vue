<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const nav = [
    { label: 'Overview', href: '/dashboard', component: 'Dashboard/Overview', icon: '📊' },
    { label: 'Products', href: '/products', component: 'Dashboard/Products', icon: '💐' },
    { label: 'Settings', href: '/settings', component: 'Dashboard/Settings', icon: '⚙️' },
] as const;

const current = computed(() => page.component as string);
const title = computed(
    () => nav.find((item) => item.component === current.value)?.label ?? 'Dashboard',
);
const flash = computed(() => page.props.flash as { success?: string; error?: string });
</script>

<template>
    <div class="min-h-screen bg-stone-100 text-stone-900">
        <aside
            class="fixed inset-y-0 left-0 hidden w-64 flex-col border-r border-stone-200 bg-white md:flex"
        >
            <div class="border-b border-stone-100 px-5 py-4">
                <p class="text-lg font-semibold tracking-tight">Otim Florist</p>
                <p class="text-xs text-stone-500">Store dashboard</p>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto p-3">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium',
                        current === item.component
                            ? 'bg-emerald-600 text-white'
                            : 'text-stone-600 hover:bg-stone-100',
                    ]"
                >
                    <span class="text-base">{{ item.icon }}</span>
                    {{ item.label }}
                </Link>
            </nav>
            <p class="border-t border-stone-100 px-5 py-3 text-xs text-stone-400">
                Dummy data · v0.1
            </p>
        </aside>

        <div class="md:pl-64">
            <header class="sticky top-0 z-10 border-b border-stone-200 bg-white">
                <div class="flex items-center justify-between gap-3 px-4 py-3 md:px-6">
                    <p class="text-sm font-semibold md:hidden">Otim Florist</p>
                    <nav class="flex items-center gap-1 md:hidden">
                        <Link
                            v-for="item in nav"
                            :key="item.href"
                            :href="item.href"
                            :class="[
                                'rounded-lg px-2.5 py-1.5 text-sm font-medium',
                                current === item.component
                                    ? 'bg-emerald-600 text-white'
                                    : 'text-stone-600 hover:bg-stone-100',
                            ]"
                        >
                            {{ item.icon }}
                        </Link>
                    </nav>
                    <p class="hidden text-sm font-medium text-stone-500 md:block">
                        {{ title }}
                    </p>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-stone-100 px-3 py-1.5 text-xs font-medium text-stone-600"
                    >
                        <span class="grid size-5 place-items-center rounded-full bg-emerald-600 text-[10px] text-white">
                            A
                        </span>
                        Admin
                    </span>
                </div>
                <p v-if="flash.success" class="bg-emerald-50 px-4 py-2 text-sm text-emerald-700 md:px-6">
                    {{ flash.success }}
                </p>
                <p v-if="flash.error" class="bg-red-50 px-4 py-2 text-sm text-red-700 md:px-6">
                    {{ flash.error }}
                </p>
            </header>

            <main class="mx-auto max-w-6xl space-y-6 p-4 md:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
