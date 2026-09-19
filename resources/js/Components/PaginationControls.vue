<script setup lang="ts">
import type { Pagination } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    pagination: Pagination;
}>();

const emit = defineEmits<{
    perPageChange: [value: number];
}>();

function visit(url: string | null): void {
    if (!url) {
        return;
    }

    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
    });
}

function changePerPage(event: Event): void {
    emit('perPageChange', Number((event.target as HTMLSelectElement).value));
}
</script>

<template>
    <div class="flex flex-col gap-4 border-t pt-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-muted-foreground">
            Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}
        </p>
        <div class="flex flex-wrap items-center gap-2">
            <label for="rows-per-page" class="text-sm text-muted-foreground">Rows per page</label>
            <select
                id="rows-per-page"
                :value="pagination.perPage"
                class="min-h-11 rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20"
                @change="changePerPage"
            >
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
            </select>
            <div class="ml-auto flex items-center gap-2 sm:ml-2">
                <button
                    type="button"
                    :disabled="!pagination.prevPageUrl"
                    class="min-h-11 rounded-xl border px-4 text-sm font-semibold transition-colors duration-200 hover:bg-secondary disabled:cursor-not-allowed disabled:opacity-50"
                    @click="visit(pagination.prevPageUrl)"
                >
                    Previous
                </button>
                <span class="min-w-24 text-center text-sm font-medium">Page {{ pagination.currentPage }} of {{ pagination.lastPage }}</span>
                <button
                    type="button"
                    :disabled="!pagination.nextPageUrl"
                    class="min-h-11 rounded-xl border px-4 text-sm font-semibold transition-colors duration-200 hover:bg-secondary disabled:cursor-not-allowed disabled:opacity-50"
                    @click="visit(pagination.nextPageUrl)"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>
