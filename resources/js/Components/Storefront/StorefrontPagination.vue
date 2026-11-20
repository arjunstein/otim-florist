<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    pagination: { currentPage: number; lastPage: number; total: number; from: number | null; to: number | null; previousUrl: string | null; nextUrl: string | null };
}>();
</script>

<template>
    <nav v-if="pagination.lastPage > 1" aria-label="Halaman produk" class="mt-10 flex flex-wrap items-center justify-center gap-4 text-sm">
        <Link v-if="pagination.previousUrl" :href="pagination.previousUrl" preserve-scroll class="rounded-xl border px-4 py-2 font-semibold hover:bg-secondary">Sebelumnya</Link>
        <span>Halaman {{ pagination.currentPage }} dari {{ pagination.lastPage }}</span>
        <Link v-if="pagination.nextUrl" :href="pagination.nextUrl" preserve-scroll class="rounded-xl border px-4 py-2 font-semibold hover:bg-secondary">Berikutnya</Link>
    </nav>
</template>
