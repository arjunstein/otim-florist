<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PaginationControls from '@/Components/PaginationControls.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { showToast } from '@/toast';
import type { CategoriesProps, Category } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<CategoriesProps>();

const createForm = useForm({ name: '' });
const editForm = useForm({ name: '' });
const editingCategory = ref<Category | null>(null);
const deletingCategory = ref<Category | null>(null);
const isDeleting = ref(false);
const createDialog = ref<HTMLDialogElement | null>(null);
const editDialog = ref<HTMLDialogElement | null>(null);
const deleteDialog = ref<HTMLDialogElement | null>(null);
const createCategoryInput = ref<HTMLInputElement | null>(null);
const editCategoryInput = ref<HTMLInputElement | null>(null);
const cancelDeleteButton = ref<HTMLButtonElement | null>(null);

function openCreateDialog(): void {
    createForm.reset();
    createForm.clearErrors();
    createDialog.value?.showModal();
    nextTick(() => createCategoryInput.value?.focus());
}

function closeCreateDialog(): void {
    createDialog.value?.close();
}

function resetCreateForm(): void {
    createForm.reset();
    createForm.clearErrors();
}

function createCategory(): void {
    createForm.post('/categories', {
        preserveScroll: true,
        onSuccess: closeCreateDialog,
        onError: () => showToast('error', 'Category could not be created. Check the form.'),
    });
}

function startEditing(category: Category): void {
    editForm.name = category.name;
    editForm.clearErrors();
    editingCategory.value = category;
    editDialog.value?.showModal();
    nextTick(() => editCategoryInput.value?.focus());
}

function closeEditDialog(): void {
    editDialog.value?.close();
}

function resetEditForm(): void {
    editingCategory.value = null;
    editForm.reset();
    editForm.clearErrors();
}

function updateCategory(): void {
    if (!editingCategory.value) {
        return;
    }

    editForm.put(`/categories/${editingCategory.value.id}`, {
        preserveScroll: true,
        onSuccess: closeEditDialog,
        onError: () => showToast('error', 'Category could not be updated. Check the form.'),
    });
}

function openDeleteDialog(category: Category): void {
    deletingCategory.value = category;
    deleteDialog.value?.showModal();
    nextTick(() => cancelDeleteButton.value?.focus());
}

function closeDeleteDialog(): void {
    deleteDialog.value?.close();
}

function resetDeleteDialog(): void {
    deletingCategory.value = null;
    isDeleting.value = false;
}

function deleteCategory(): void {
    if (!deletingCategory.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(`/categories/${deletingCategory.value.id}`, {
        preserveScroll: true,
        onSuccess: closeDeleteDialog,
        onError: () => {
            isDeleting.value = false;
            showToast('error', 'Category could not be deleted.');
        },
    });
}

function changePerPage(perPage: number): void {
    router.get('/categories', { per_page: perPage }, { preserveScroll: true, preserveState: true, replace: true });
}
</script>

<template>
    <Head title="Categories" />

    <PageHeader title="Categories" subtitle="Create and manage product categories.">
        <template #actions>
            <button
                type="button"
                class="min-h-11 w-full rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 sm:w-auto"
                @click="openCreateDialog"
            >
                Add category
            </button>
        </template>
    </PageHeader>

    <dialog
        ref="createDialog"
        aria-labelledby="create-category-title"
        class="m-auto w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        @close="resetCreateForm"
    >
        <form class="flex flex-col" @submit.prevent="createCategory">
            <header class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 id="create-category-title" class="text-lg font-semibold tracking-tight">Add category</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Names must be unique.</p>
                </div>
                <button
                    type="button"
                    class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    aria-label="Close dialog"
                    @click="closeCreateDialog"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <div class="flex flex-col gap-1.5 px-5 py-5 sm:px-6">
                <label for="category-name" class="text-sm font-medium">Category name</label>
                <input
                    id="category-name"
                    ref="createCategoryInput"
                    v-model="createForm.name"
                    :aria-describedby="createForm.errors.name ? 'category-name-error' : undefined"
                    :aria-invalid="Boolean(createForm.errors.name)"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        createForm.errors.name ? 'border-destructive' : '',
                    ]"
                    placeholder="E.g. Bouquet"
                />
                <p v-if="createForm.errors.name" id="category-name-error" class="text-sm text-destructive-foreground">
                    {{ createForm.errors.name }}
                </p>
            </div>
            <footer class="flex flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    type="button"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                    @click="closeCreateDialog"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="createForm.processing"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ createForm.processing ? 'Creating' : 'Create category' }}
                </button>
            </footer>
        </form>
    </dialog>

    <dialog
        ref="deleteDialog"
        aria-labelledby="delete-category-title"
        aria-describedby="delete-category-description"
        class="m-auto w-[calc(100%-2rem)] max-w-sm overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        @close="resetDeleteDialog"
    >
        <div class="flex flex-col">
            <div class="px-5 pb-5 pt-6 sm:px-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="grid size-11 shrink-0 place-items-center rounded-full bg-destructive text-destructive-foreground">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M12 9v4m0 4h.01M10.3 3.9 2.5 17.4A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.6L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                    </div>
                    <button
                        type="button"
                        class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        aria-label="Close dialog"
                        @click="closeDeleteDialog"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <h2 id="delete-category-title" class="mt-4 text-lg font-semibold tracking-tight">Delete category?</h2>
                <p id="delete-category-description" class="mt-2 text-sm leading-6 text-muted-foreground">
                    <span class="font-semibold text-foreground">{{ deletingCategory?.name }}</span> will be permanently deleted. This action cannot be undone.
                </p>
            </div>
            <footer class="flex flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    ref="cancelDeleteButton"
                    type="button"
                    :disabled="isDeleting"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground disabled:cursor-not-allowed disabled:opacity-50"
                    @click="closeDeleteDialog"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    :disabled="isDeleting"
                    class="min-h-11 rounded-xl bg-destructive px-4 text-sm font-semibold text-destructive-foreground shadow-sm transition-colors duration-200 hover:bg-destructive/80 disabled:cursor-not-allowed disabled:opacity-50"
                    @click="deleteCategory"
                >
                    {{ isDeleting ? 'Deleting' : 'Delete category' }}
                </button>
            </footer>
        </div>
    </dialog>

    <dialog
        ref="editDialog"
        aria-labelledby="edit-category-title"
        class="m-auto w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        @close="resetEditForm"
    >
        <form class="flex flex-col" @submit.prevent="updateCategory">
            <header class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 id="edit-category-title" class="text-lg font-semibold tracking-tight">Edit category</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Update the category name.</p>
                </div>
                <button
                    type="button"
                    class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    aria-label="Close dialog"
                    @click="closeEditDialog"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <div class="flex flex-col gap-1.5 px-5 py-5 sm:px-6">
                <label for="edit-category-name" class="text-sm font-medium">Category name</label>
                <input
                    id="edit-category-name"
                    ref="editCategoryInput"
                    v-model="editForm.name"
                    :aria-describedby="editForm.errors.name ? 'edit-category-name-error' : undefined"
                    :aria-invalid="Boolean(editForm.errors.name)"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        editForm.errors.name ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="editForm.errors.name" id="edit-category-name-error" class="text-sm text-destructive-foreground">
                    {{ editForm.errors.name }}
                </p>
            </div>
            <footer class="flex flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    type="button"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                    @click="closeEditDialog"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="editForm.processing"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ editForm.processing ? 'Saving' : 'Save changes' }}
                </button>
            </footer>
        </form>
    </dialog>

    <CardSection title="Total categories" :subtitle="`${categories.pagination.total} categories`">
        <div v-if="categories.pagination.total === 0" class="rounded-xl border border-dashed bg-muted/40 p-8 text-center">
            <p class="font-medium">No categories yet</p>
            <p class="mt-1 text-sm text-muted-foreground">Add first category using form above.</p>
        </div>

        <ul v-else class="flex flex-col gap-3">
            <li v-for="category in categories.data" :key="category.id" class="rounded-xl border bg-card p-4">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="grid flex-1 gap-3 sm:grid-cols-[minmax(0,1fr)_10rem] sm:items-center">
                        <div class="min-w-0">
                            <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Category</p>
                            <p class="mt-1 break-words font-semibold">{{ category.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Created</p>
                            <p class="mt-1 text-sm text-muted-foreground">{{ category.createdAt }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 sm:shrink-0">
                        <button
                            type="button"
                            class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                            :aria-label="`Edit ${category.name}`"
                            :title="`Edit ${category.name}`"
                            @click="startEditing(category)"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="grid size-11 place-items-center rounded-xl text-destructive-foreground transition-colors duration-200 hover:bg-destructive"
                            :aria-label="`Delete ${category.name}`"
                            :title="`Delete ${category.name}`"
                            @click="openDeleteDialog(category)"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6m5 4v6m4-6v6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </li>
        </ul>
        <PaginationControls
            v-if="categories.pagination.total"
            class="mt-5"
            :pagination="categories.pagination"
            @per-page-change="changePerPage"
        />
    </CardSection>
</template>
