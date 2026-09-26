<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PaginationControls from '@/Components/PaginationControls.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { showToast } from '@/toast';
import type { Product, ProductsProps } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<ProductsProps>();

const query = ref(props.filters.search ?? '');
const category = ref(props.filters.categoryId ? String(props.filters.categoryId) : 'All');
let filterTimer: ReturnType<typeof window.setTimeout> | undefined;
const editingProduct = ref<Product | null>(null);
const deletingProduct = ref<Product | null>(null);
const isDeleting = ref(false);
const productDialog = ref<HTMLDialogElement | null>(null);
const deleteDialog = ref<HTMLDialogElement | null>(null);
const cropDialog = ref<HTMLDialogElement | null>(null);
const productNameInput = ref<HTMLInputElement | null>(null);
const productImageInput = ref<HTMLInputElement | null>(null);
const cancelDeleteButton = ref<HTMLButtonElement | null>(null);
const imagePreviewUrl = ref<string | null>(null);
const cropCanvas = ref<HTMLCanvasElement | null>(null);
const cropImage = ref<HTMLImageElement | null>(null);
const cropImageUrl = ref<string | null>(null);
const cropImageName = ref('product-image');
const cropZoom = ref(1);
const cropOffset = ref({ x: 0, y: 0 });
const cropDragStart = ref<{ x: number; y: number; offsetX: number; offsetY: number } | null>(null);

const cropWidth = 1200;
const cropHeight = 900;
const productForm = useForm({
    name: '',
    description: '',
    category_id: '',
    price: '',
    sale_price: '',
    image: null as File | null,
    _method: '',
});

const isEditing = computed(() => editingProduct.value !== null);
const productImagePreview = computed(() => imagePreviewUrl.value ?? editingProduct.value?.imageUrl ?? null);

function formatPrice(price: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(price);
}

function openCreateDialog(): void {
    resetProductForm();
    productDialog.value?.showModal();
    nextTick(() => productNameInput.value?.focus());
}

function openEditDialog(product: Product): void {
    editingProduct.value = product;
    productForm.name = product.name;
    productForm.description = product.description ?? '';
    productForm.category_id = String(product.category.id);
    productForm.price = String(product.price);
    productForm.sale_price = product.salePrice ? String(product.salePrice) : '';
    productForm.image = null;
    productForm._method = '';
    productForm.clearErrors();
    productDialog.value?.showModal();
    nextTick(() => productNameInput.value?.focus());
}

function closeProductDialog(): void {
    productDialog.value?.close();
}

function resetProductForm(): void {
    releaseImagePreview();
    editingProduct.value = null;
    productForm.reset();
    productForm.clearErrors();
    if (productImageInput.value) {
        productImageInput.value.value = '';
    }
}

function selectImage(event: Event): void {
    const [image] = (event.target as HTMLInputElement).files ?? [];

    if (!image) {
        return;
    }

    if (image.size > 3 * 1024 * 1024) {
        showToast('error', 'Image size must not exceed 3 MB.');
        if (productImageInput.value) {
            productImageInput.value.value = '';
        }

        return;
    }

    openCropDialog(image);
}

function clearSelectedImage(): void {
    releaseImagePreview();
    productForm.image = null;

    if (productImageInput.value) {
        productImageInput.value.value = '';
    }
}

function releaseImagePreview(): void {
    if (imagePreviewUrl.value) {
        URL.revokeObjectURL(imagePreviewUrl.value);
        imagePreviewUrl.value = null;
    }
}

function openCropDialog(file: File): void {
    resetCropDialog();
    cropImageName.value = file.name.replace(/\.[^/.]+$/, '') || 'product-image';
    cropImageUrl.value = URL.createObjectURL(file);

    const image = new Image();
    image.onload = () => {
        cropImage.value = image;
        cropZoom.value = 1;
        cropOffset.value = centeredCropOffset();
        drawCrop();
        cropDialog.value?.showModal();
    };
    image.onerror = () => {
        showToast('error', 'Image could not be opened. Choose another file.');
        resetCropDialog();
    };
    image.src = cropImageUrl.value;
}

function cropScale(): number {
    if (!cropImage.value) {
        return 1;
    }

    return Math.max(cropWidth / cropImage.value.naturalWidth, cropHeight / cropImage.value.naturalHeight) * cropZoom.value;
}

function centeredCropOffset(): { x: number; y: number } {
    if (!cropImage.value) {
        return { x: 0, y: 0 };
    }

    const scale = cropScale();

    return {
        x: (cropWidth - cropImage.value.naturalWidth * scale) / 2,
        y: (cropHeight - cropImage.value.naturalHeight * scale) / 2,
    };
}

function clampCropOffset(offset: { x: number; y: number }): { x: number; y: number } {
    if (!cropImage.value) {
        return { x: 0, y: 0 };
    }

    const scale = cropScale();
    const renderedWidth = cropImage.value.naturalWidth * scale;
    const renderedHeight = cropImage.value.naturalHeight * scale;

    return {
        x: Math.min(0, Math.max(cropWidth - renderedWidth, offset.x)),
        y: Math.min(0, Math.max(cropHeight - renderedHeight, offset.y)),
    };
}

function drawCrop(): void {
    const canvas = cropCanvas.value;
    const image = cropImage.value;

    if (!canvas || !image) {
        return;
    }

    const context = canvas.getContext('2d');

    if (!context) {
        return;
    }

    cropOffset.value = clampCropOffset(cropOffset.value);
    const scale = cropScale();
    const width = image.naturalWidth * scale;
    const height = image.naturalHeight * scale;

    context.clearRect(0, 0, cropWidth, cropHeight);
    context.drawImage(image, cropOffset.value.x, cropOffset.value.y, width, height);
}

function updateCropZoom(): void {
    cropOffset.value = clampCropOffset(cropOffset.value);
    drawCrop();
}

function startCropDrag(event: PointerEvent): void {
    const canvas = cropCanvas.value;

    if (!canvas || !event.isPrimary) {
        return;
    }

    cropDragStart.value = {
        x: event.clientX,
        y: event.clientY,
        offsetX: cropOffset.value.x,
        offsetY: cropOffset.value.y,
    };
    window.addEventListener('pointermove', moveCropImage);
    window.addEventListener('pointerup', endCropDrag, { once: true });
    window.addEventListener('pointercancel', endCropDrag, { once: true });
}

function moveCropImage(event: PointerEvent): void {
    const canvas = cropCanvas.value;
    const dragStart = cropDragStart.value;

    if (!canvas || !dragStart) {
        return;
    }

    const bounds = canvas.getBoundingClientRect();
    cropOffset.value = clampCropOffset({
        x: dragStart.offsetX + ((event.clientX - dragStart.x) * cropWidth) / bounds.width,
        y: dragStart.offsetY + ((event.clientY - dragStart.y) * cropHeight) / bounds.height,
    });
    drawCrop();
}

function endCropDrag(): void {
    cropDragStart.value = null;
    window.removeEventListener('pointermove', moveCropImage);
    window.removeEventListener('pointerup', endCropDrag);
    window.removeEventListener('pointercancel', endCropDrag);
}

function saveCroppedImage(): void {
    const canvas = cropCanvas.value;

    if (!canvas) {
        return;
    }

    canvas.toBlob((blob) => {
        if (!blob) {
            showToast('error', 'Image could not be cropped. Try another file.');

            return;
        }

        releaseImagePreview();
        productForm.image = new File([blob], `${cropImageName.value}.webp`, { type: 'image/webp' });
        imagePreviewUrl.value = URL.createObjectURL(productForm.image);
        cropDialog.value?.close();
    }, 'image/webp', 0.9);
}

function cancelCropDialog(): void {
    cropDialog.value?.close();
}

function resetCropDialog(): void {
    endCropDrag();
    cropImage.value = null;
    cropDragStart.value = null;
    cropOffset.value = { x: 0, y: 0 };
    cropZoom.value = 1;

    if (cropImageUrl.value) {
        URL.revokeObjectURL(cropImageUrl.value);
        cropImageUrl.value = null;
    }

    if (productImageInput.value) {
        productImageInput.value.value = '';
    }
}

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', moveCropImage);
    window.removeEventListener('pointerup', endCropDrag);
    window.removeEventListener('pointercancel', endCropDrag);
});

function saveProduct(): void {
    const isEdit = Boolean(editingProduct.value);
    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: closeProductDialog,
        onError: (errors: Record<string, string>) => {
            const firstError = Object.values(errors)[0];
            showToast(
                'error',
                firstError || (isEdit ? 'Failed to update product. Please check the form.' : 'Failed to create product. Please check the form.'),
            );
            nextTick(() => {
                const firstInvalid = productDialog.value?.querySelector('[aria-invalid="true"]') as HTMLElement | null;
                firstInvalid?.focus();
                firstInvalid?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        },
    };

    if (editingProduct.value) {
        productForm._method = 'put';
        productForm.post(`/products/${editingProduct.value.id}`, options);

        return;
    }

    productForm._method = '';
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

function applyFilters(perPage = props.products.pagination.perPage): void {
    window.clearTimeout(filterTimer);
    filterTimer = undefined;

    router.get(
        '/products',
        {
            search: query.value || undefined,
            category_id: category.value === 'All' ? undefined : category.value,
            per_page: perPage,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
}

function queueFilters(): void {
    window.clearTimeout(filterTimer);
    filterTimer = window.setTimeout(() => applyFilters(), 300);
}
</script>

<template>
    <Head title="Products" />

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
        class="m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        @close="resetProductForm"
    >
        <form class="flex flex-col" @submit.prevent="saveProduct">
            <header class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 id="product-dialog-title" class="text-lg font-semibold tracking-tight">
                        {{ isEditing ? 'Edit product' : 'Add product' }}
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">Add product details, price, and image.</p>
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
                        :aria-invalid="Boolean(productForm.errors.name)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.name ? 'border-destructive' : '',
                        ]"
                        placeholder="E.g. Rose Bouquet M"
                    />
                </div>
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="product-category" class="text-sm font-medium">Category</label>
                    <select
                        id="product-category"
                        v-model="productForm.category_id"
                        :aria-invalid="Boolean(productForm.errors.category_id)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.category_id ? 'border-destructive' : '',
                        ]"
                    >
                        <option value="" disabled>Select category</option>
                        <option v-for="item in categories" :key="item.id" :value="String(item.id)">{{ item.name }}</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="product-description" class="text-sm font-medium">Description <span class="text-muted-foreground">(optional)</span></label>
                    <textarea
                        id="product-description"
                        v-model="productForm.description"
                        rows="4"
                        maxlength="2000"
                        :aria-invalid="Boolean(productForm.errors.description)"
                        :class="[
                            'w-full resize-y rounded-xl border bg-background px-3 py-2.5 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.description ? 'border-destructive' : '',
                        ]"
                        placeholder="Describe the arrangement, flowers, and occasion."
                    />
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
                        :aria-invalid="Boolean(productForm.errors.price)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.price ? 'border-destructive' : '',
                        ]"
                        placeholder="350000"
                    />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="product-sale-price" class="text-sm font-medium">Sale price <span class="text-muted-foreground">(optional)</span></label>
                    <input
                        id="product-sale-price"
                        v-model="productForm.sale_price"
                        type="number"
                        min="0"
                        :max="productForm.price || undefined"
                        step="1000"
                        inputmode="numeric"
                        :aria-invalid="Boolean(productForm.errors.sale_price)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.sale_price ? 'border-destructive' : '',
                        ]"
                        placeholder="300000"
                    />
                </div>
                <div class="flex flex-col gap-1.5 sm:col-span-2">
                    <label for="product-image" class="text-sm font-medium">Product image <span class="text-muted-foreground">(optional)</span></label>
                    <input
                        id="product-image"
                        ref="productImageInput"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        aria-describedby="product-image-help"
                        :aria-invalid="Boolean(productForm.errors.image)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 py-2 text-sm shadow-xs file:mr-3 file:rounded-lg file:border-0 file:bg-secondary file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-secondary-foreground hover:file:bg-secondary/80 focus:border-ring focus:ring-2 focus:ring-ring/20',
                            productForm.errors.image ? 'border-destructive' : '',
                        ]"
                        @change="selectImage"
                    />
                    <p id="product-image-help" class="text-xs leading-5 text-muted-foreground">JPG, PNG, or WebP up to 3 MB.</p>
                    <div v-if="productImagePreview" class="relative overflow-hidden rounded-xl border bg-muted/30">
                        <img :src="productImagePreview" alt="Product image preview" class="aspect-[4/3] w-full object-cover" />
                        <button
                            v-if="productForm.image"
                            type="button"
                            class="absolute right-3 top-3 min-h-11 rounded-lg bg-background/95 px-3 text-sm font-semibold shadow-sm transition-colors hover:bg-background"
                            @click="clearSelectedImage"
                        >
                            Remove image
                        </button>
                    </div>
                    <p v-if="productForm.progress" class="text-sm text-muted-foreground">Uploading {{ productForm.progress.percentage }}%</p>
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
        ref="cropDialog"
        aria-labelledby="crop-dialog-title"
        class="m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-2xl overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/40 backdrop:backdrop-blur-sm"
        @cancel.prevent="cancelCropDialog"
        @close="resetCropDialog"
    >
        <div class="flex max-h-[calc(100dvh-2rem)] flex-col">
            <header class="flex shrink-0 items-start justify-between gap-4 border-b px-5 py-4 sm:px-6">
                <div>
                    <h2 id="crop-dialog-title" class="text-lg font-semibold tracking-tight">Crop product image</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Adjust the photo in the 4:3 frame.</p>
                </div>
                <button
                    type="button"
                    class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    aria-label="Cancel image crop"
                    @click="cancelCropDialog"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </header>
            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto px-5 py-5 sm:px-6">
                <canvas
                    ref="cropCanvas"
                    :width="cropWidth"
                    :height="cropHeight"
                    class="aspect-[4/3] w-full touch-none cursor-grab rounded-xl bg-muted active:cursor-grabbing"
                    @pointerdown.prevent="startCropDrag"
                />
                <div class="grid gap-2">
                    <div class="flex items-center justify-between text-sm">
                        <label for="crop-zoom" class="font-medium">Zoom</label>
                        <span class="text-muted-foreground">{{ Math.round(cropZoom * 100) }}%</span>
                    </div>
                    <input
                        id="crop-zoom"
                        v-model.number="cropZoom"
                        type="range"
                        min="1"
                        max="3"
                        step="0.01"
                        class="h-2 w-full cursor-pointer accent-primary"
                        @input="updateCropZoom"
                    />
                    <p class="text-xs leading-5 text-muted-foreground">Drag to reposition. The saved image will be cropped to 4:3.</p>
                </div>
            </div>
            <footer class="flex shrink-0 flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    type="button"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                    @click="cancelCropDialog"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90"
                    @click="saveCroppedImage"
                >
                    Use image
                </button>
            </footer>
        </div>
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

    <CardSection title="Total products" :subtitle="`${products.pagination.total} products`">
        <template #actions>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row" role="search">
                <label for="product-search" class="sr-only">Search products</label>
                <input
                    id="product-search"
                    v-model="query"
                    type="search"
                    placeholder="Search products"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-56"
                    @input="queueFilters"
                />
                <label for="catalog-category" class="sr-only">Filter by category</label>
                <select
                    id="catalog-category"
                    v-model="category"
                    class="min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20 sm:w-auto"
                    @change="applyFilters()"
                >
                    <option value="All">All categories</option>
                    <option v-for="item in categories" :key="item.id" :value="String(item.id)">{{ item.name }}</option>
                </select>
            </div>
        </template>

        <div v-if="products.pagination.total === 0" class="rounded-xl border border-dashed bg-muted/40 p-8 text-center">
            <p class="font-medium">{{ filters.search || filters.categoryId ? 'No products match your filters.' : 'No products yet' }}</p>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ filters.search || filters.categoryId ? 'Try a different keyword or category.' : categories.length === 0 ? 'Create a category first, then add your first product.' : 'Add your first product to the catalog.' }}
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
                    <tr v-for="product in products.data" :key="product.id" class="border-b last:border-0 hover:bg-muted/60">
                        <td class="px-3 py-3.5 sm:px-6">
                            <div class="flex min-w-0 items-center gap-3">
                                <img v-if="product.imageUrl" :src="product.imageUrl" :alt="product.name" class="size-11 shrink-0 rounded-lg object-cover" />
                                <div class="min-w-0">
                                    <p class="break-words font-semibold">{{ product.name }}</p>
                                    <p v-if="product.description" class="mt-0.5 line-clamp-1 text-xs font-normal text-muted-foreground">{{ product.description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-muted-foreground">{{ product.category.name }}</td>
                        <td class="px-5 py-3 text-right font-medium">
                            <p v-if="product.salePrice" class="text-xs font-normal text-muted-foreground line-through">{{ formatPrice(product.price) }}</p>
                            <p :class="product.salePrice ? 'text-accent font-semibold' : ''">{{ formatPrice(product.salePrice ?? product.price) }}</p>
                        </td>
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
                                    class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors duration-200 hover:bg-destructive/10 hover:text-destructive"
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
                </tbody>
            </table>
        </div>
        <PaginationControls
            v-if="products.pagination.total"
            class="mt-5"
            :pagination="products.pagination"
            @per-page-change="applyFilters"
        />
    </CardSection>
</template>
