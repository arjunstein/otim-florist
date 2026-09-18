<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { showToast } from '@/toast';
import type { Product, ProductsProps } from '@/types';
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<ProductsProps>();

const query = ref('');
const category = ref('All');
const editingProduct = ref<Product | null>(null);
const deletingProduct = ref<Product | null>(null);
const isDeleting = ref(false);
const productDialog = ref<HTMLDialogElement | null>(null);
const deleteDialog = ref<HTMLDialogElement | null>(null);
const productNameInput = ref<HTMLInputElement | null>(null);
const cancelDeleteButton = ref<HTMLButtonElement | null>(null);
const productForm = useForm({
    name: '',
    category_id: '',
    price: '',
});

const filtered = computed(() =>
    props.products.filter(
        (product) =>
            (category.value === 'All' || product.category.name === category.value) &&
            product.name.toLowerCase().includes(query.value.toLowerCase()),
    ),
);

const isEditing = computed(() => editingProduct.value !== null);

function formatPrice(price: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(price);
}

function openCreateDialog(): void {
    editingProduct.value = null;
    productForm.reset();
    productForm.clearErrors();
    productDialog.value?.showModal();
    nextTick(() => productNameInput.value?.focus());
}

function openEditDialog(product: Product): void {
    editingProduct.value = product;
    productForm.name = product.name;
    productForm.category_id = String(product.category.id);
    productForm.price = String(product.price);
    productForm.clearErrors();
    productDialog.value?.showModal();
    nextTick(() => productNameInput.value?.focus());
}

function closeProductDialog(): void {
    productDialog.value?.close();
}

function resetProductForm(): void {
    editingProduct.value = null;
    productForm.reset();
    productForm.clearErrors();
}

function saveProduct(): void {
    const options = {
        preserveScroll: true,
        onSuccess: closeProductDialog,
        onError: () => showToast('error', 'Product could not be saved. Check the form.'),
    };

    if (editingProduct.value) {
        productForm.put(`/products/${editingProduct.value.id}`, options);

        return;
    }

    productForm.post('/products', options);
}

function openDeleteDialog(product: Product): void {
    deletingProduct.value = product;
    deleteDialog.value?.showModal();
    nextTick(() => cancelDeleteButton.value?.focus());
}

function closeDeleteDialog(): void {
    deleteDialog.value?.close();
}

function resetDeleteDialog(): void {
    deletingProduct.value = null;
    isDeleting.value = false;
}

function deleteProduct(): void {
    if (!deletingProduct.value) {
        return;
    }

    isDeleting.value = true;
    router.delete(`/products/${deletingProduct.value.id}`, {
        preserveScroll: true,
        onSuccess: closeDeleteDialog,
        onError: () => {
            isDeleting.value = false;
            showToast('error', 'Product could not be deleted.');
        },
    });
}
</script>

<template>
    <PageHeader title="Products" subtitle="Manage catalog prices and categories.">
        <template #actions>
            <Link
                v-if="categories.length === 0"
                href="/categories"
                class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 sm:w-auto"
            >
                Create category
            </Link>
            <button
                v-else
                type="button"
                class="min-h-11 w-full rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 sm:w-auto"
                @click="openCreateDialog"
            >
                Add product
            </button>
        </template>
    </PageHeader>

    <dialog
        ref="productDialog"
        aria-labelledby="product-dialog-title"
        class="m-auto w-[calc(100%-2rem)] max-w-lg overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        @close="resetProductForm"
    >
        <form class="flex flex-col" @submit.prevent="saveProduct">
            <header class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 id="product-dialog-title" class="text-lg font-semibold tracking-tight">
                        {{ isEditing ? 'Edit product' : 'Add product' }}
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">Set product category and price.</p>
                </div>
                <button
                    type="button"
                    class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    aria-label="Close dialog"
                    @click="closeProductDialog"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <div class="grid gap-4 px-5 py-5 sm:grid-cols-2 sm:px-6">
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="product-name" class="text-sm font-medium">Product name</label>
                    <input
                        id="product-name"
                        ref="productNameInput"
                        v-model="productForm.name"
                        :aria-describedby="productForm.errors.name ? 'product-name-error' : undefined"
                        :aria-invalid="Boolean(productForm.errors.name)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.name ? 'border-destructive' : '',
                        ]"
                        placeholder="E.g. Rose Bouquet M"
                    />
                    <p v-if="productForm.errors.name" id="product-name-error" role="alert" class="text-sm text-destructive-foreground">
                        {{ productForm.errors.name }}
                    </p>
                </div>
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="product-category" class="text-sm font-medium">Category</label>
                    <select
                        id="product-category"
                        v-model="productForm.category_id"
                        :aria-describedby="productForm.errors.category_id ? 'product-category-error' : undefined"
                        :aria-invalid="Boolean(productForm.errors.category_id)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.category_id ? 'border-destructive' : '',
                        ]"
                    >
                        <option value="" disabled>Select category</option>
                        <option v-for="item in categories" :key="item.id" :value="String(item.id)">{{ item.name }}</option>
                    </select>
                    <p v-if="productForm.errors.category_id" id="product-category-error" role="alert" class="text-sm text-destructive-foreground">
                        {{ productForm.errors.category_id }}
                    </p>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="product-price" class="text-sm font-medium">Price (Rp)</label>
                    <input
                        id="product-price"
                        v-model="productForm.price"
                        type="number"
                        min="0"
                        max="999999999"
                        step="1000"
                        inputmode="numeric"
                        :aria-describedby="productForm.errors.price ? 'product-price-error' : undefined"
                        :aria-invalid="Boolean(productForm.errors.price)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.price ? 'border-destructive' : '',
                        ]"
                        placeholder="350000"
                    />
                    <p v-if="productForm.errors.price" id="product-price-error" role="alert" class="text-sm text-destructive-foreground">
                        {{ productForm.errors.price }}
                    </p>
                </div>
            </div>
            <footer class="flex flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    type="button"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                    @click="closeProductDialog"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="productForm.processing"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ productForm.processing ? 'Saving' : isEditing ? 'Save changes' : 'Create product' }}
                </button>
            </footer>
        </form>
    </dialog>

    <dialog
        ref="deleteDialog"
        aria-labelledby="delete-product-title"
        aria-describedby="delete-product-description"
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
                <h2 id="delete-product-title" class="mt-4 text-lg font-semibold tracking-tight">Delete product?</h2>
                <p id="delete-product-description" class="mt-2 text-sm leading-6 text-muted-foreground">
                    <span class="font-semibold text-foreground">{{ deletingProduct?.name }}</span> will be permanently deleted. This action cannot be undone.
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
                    @click="deleteProduct"
                >
                    {{ isDeleting ? 'Deleting' : 'Delete product' }}
                </button>
            </footer>
        </div>
    </dialog>

    <CardSection title="Total products" :subtitle="`${products.length} products`">
        <template #actions>
            <fieldset class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                <legend class="sr-only">Catalog filters</legend>
                <label for="product-search" class="sr-only">Search products</label>
                <input
                    id="product-search"
                    v-model="query"
                    type="search"
                    placeholder="Search products"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-56"
                />
                <label for="catalog-category" class="sr-only">Filter by category</label>
                <select
                    id="catalog-category"
                    v-model="category"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-auto"
                >
                    <option value="All">All categories</option>
                    <option v-for="item in categories" :key="item.id" :value="item.name">{{ item.name }}</option>
                </select>
            </fieldset>
        </template>

        <div v-if="products.length === 0" class="rounded-xl border border-dashed bg-muted/40 p-8 text-center">
            <p class="font-medium">No products yet</p>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ categories.length === 0 ? 'Create a category first, then add your first product.' : 'Add your first product to the catalog.' }}
            </p>
        </div>
        <div v-else class="-m-5 overflow-x-auto sm:-m-6">
            <table class="w-full min-w-[36rem] text-left text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <th class="px-3 py-3 font-semibold text-[11px] sm:px-6 sm:text-xs">Product</th>
                        <th class="px-5 py-3 font-semibold">Category</th>
                        <th class="px-5 py-3 text-right font-semibold">Price</th>
                        <th class="px-3 py-3 text-right font-semibold text-[11px] sm:px-6 sm:text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filtered" :key="product.id" class="border-b last:border-0 hover:bg-muted/60">
                        <td class="break-words px-3 py-3.5 font-semibold sm:px-6">{{ product.name }}</td>
                        <td class="px-5 py-3 text-muted-foreground">{{ product.category.name }}</td>
                        <td class="px-5 py-3 text-right font-medium">{{ formatPrice(product.price) }}</td>
                        <td class="px-3 py-3 text-right sm:px-6">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                                    :aria-label="`Edit ${product.name}`"
                                    :title="`Edit ${product.name}`"
                                    @click="openEditDialog(product)"
                                >
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    class="grid size-11 place-items-center rounded-xl text-destructive-foreground transition-colors duration-200 hover:bg-destructive"
                                    :aria-label="`Delete ${product.name}`"
                                    :title="`Delete ${product.name}`"
                                    @click="openDeleteDialog(product)"
                                >
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6m5 4v6m4-6v6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td colspan="4" class="px-5 py-12 text-center text-sm text-muted-foreground">
                            No products match your filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </CardSection>
</template>
