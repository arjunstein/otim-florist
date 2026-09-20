<script setup lang="ts">
import type { StoreInfo } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

type NavigationCategory = {
    name: string;
    slug: string;
};

const mobileNavigationOpen = ref(false);
const showBackToTop = ref(false);
const mobileNavigationToggle = ref<HTMLButtonElement | null>(null);
const mobileNavigationClose = ref<HTMLButtonElement | null>(null);
const page = usePage<{
    navigationCategories: NavigationCategory[];
    store: StoreInfo;
}>();
const navigationCategories = computed(() => page.props.navigationCategories ?? []);
const store = computed<StoreInfo>(() => page.props.store ?? {
    name: 'Otim Florist',
    phone: '',
    address: 'Jl. Mawar No. 12, Jakarta',
    hours: '08:00–20:00 daily',
});

function openMobileNavigation(): void {
    mobileNavigationOpen.value = true;
    nextTick(() => mobileNavigationClose.value?.focus());
}

function closeMobileNavigation(returnFocus = true): void {
    mobileNavigationOpen.value = false;

    if (returnFocus) {
        nextTick(() => mobileNavigationToggle.value?.focus());
    }
}

function handleKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && mobileNavigationOpen.value) {
        closeMobileNavigation();
    }
}

function updateBackToTopVisibility(): void {
    showBackToTop.value = window.scrollY > 400;
}

function scrollToTop(): void {
    window.scrollTo({
        top: 0,
        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
    });
}

watch(mobileNavigationOpen, (isOpen) => document.body.classList.toggle('overflow-hidden', isOpen));

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
    window.addEventListener('scroll', updateBackToTopVisibility, { passive: true });
    updateBackToTopVisibility();
});
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
    window.removeEventListener('scroll', updateBackToTopVisibility);
    document.body.classList.remove('overflow-hidden');
});
</script>

<template>
    <div class="storefront min-h-dvh overflow-x-hidden bg-background text-foreground">
        <a
            href="#main-content"
            class="sr-only fixed left-4 top-4 z-50 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground focus:not-sr-only"
        >
            Langsung ke konten
        </a>
        <header class="sticky top-0 z-20 border-b bg-background/85 backdrop-blur-xl">
            <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex min-h-11 shrink-0 items-center gap-2.5 rounded-xl pr-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <svg class="size-8 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                    <span class="font-semibold tracking-tight">{{ store.name }}</span>
                </Link>
                <nav class="ml-auto hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
                    <Link
                        v-for="category in navigationCategories"
                        :key="category.slug"
                        :href="`/categories/${category.slug}`"
                        class="inline-flex min-h-11 items-center rounded-xl px-3 text-sm font-semibold text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    >
                        {{ category.name }}
                    </Link>
                </nav>
                <button
                    ref="mobileNavigationToggle"
                    type="button"
                    class="ml-auto grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring lg:hidden"
                    aria-label="Buka navigasi"
                    aria-controls="mobile-navigation"
                    :aria-expanded="mobileNavigationOpen"
                    @click="openMobileNavigation"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </header>

        <Transition
            enter-active-class="transition-opacity duration-200 ease-out motion-reduce:transition-none"
            enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-150 ease-in motion-reduce:transition-none"
            leave-to-class="opacity-0"
        >
            <div v-if="mobileNavigationOpen" class="fixed inset-0 z-40 lg:hidden">
                <button
                    type="button"
                    class="absolute inset-0 bg-foreground/30 backdrop-blur-sm"
                    aria-label="Tutup navigasi"
                    @click="closeMobileNavigation(false)"
                />
                <nav
                    id="mobile-navigation"
                    class="absolute right-0 top-0 flex h-dvh w-72 max-w-[calc(100%-2rem)] flex-col border-l bg-card p-4 shadow-xl"
                    aria-label="Navigasi seluler"
                    aria-modal="true"
                    role="dialog"
                >
                    <div class="flex min-h-11 items-center justify-between">
                        <p class="font-semibold tracking-tight">Navigasi</p>
                        <button
                            ref="mobileNavigationClose"
                            type="button"
                            class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            aria-label="Tutup navigasi"
                            @click="closeMobileNavigation()"
                        >
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flex flex-col gap-1">
                        <Link
                            v-for="category in navigationCategories"
                            :key="category.slug"
                            :href="`/categories/${category.slug}`"
                            class="flex min-h-11 items-center rounded-xl px-3 text-sm font-semibold text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            @click="closeMobileNavigation(false)"
                        >
                            {{ category.name }}
                        </Link>
                    </div>
                </nav>
            </div>
        </Transition>

        <main id="main-content">
            <slot />
        </main>

        <Transition
            enter-active-class="transition duration-200 ease-out motion-reduce:transition-none"
            enter-from-class="translate-y-2 opacity-0"
            leave-active-class="transition duration-150 ease-in motion-reduce:transition-none"
            leave-to-class="translate-y-2 opacity-0"
        >
            <button
                v-if="showBackToTop"
                type="button"
                class="fixed bottom-4 right-4 z-30 grid size-11 place-items-center rounded-full bg-primary text-primary-foreground shadow-lg transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 sm:bottom-6 sm:right-6"
                aria-label="Kembali ke atas"
                title="Kembali ke atas"
                @click="scrollToTop"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </Transition>

        <footer class="border-t border-primary/15 bg-primary text-primary-foreground">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="space-y-3 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center gap-2.5">
                            <svg class="size-7 text-primary-foreground" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                                <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                                <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                                <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                            </svg>
                            <span class="text-lg font-semibold tracking-tight">{{ store.name }}</span>
                        </div>
                        <p class="text-sm leading-relaxed text-primary-foreground/75">
                            Rangkaian bunga segar penuh makna, dirangkai dengan sepenuh hati untuk menyempurnakan setiap momen berharga Anda.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary-foreground/70">Alamat Toko</p>
                        <div class="flex items-start gap-2.5 text-sm text-primary-foreground/85">
                            <svg class="mt-0.5 size-4 shrink-0 text-primary-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <p class="leading-relaxed">{{ store.address }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary-foreground/70">Jam Operasional</p>
                        <div class="flex items-start gap-2.5 text-sm text-primary-foreground/85">
                            <svg class="mt-0.5 size-4 shrink-0 text-primary-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            <p class="leading-relaxed">{{ store.hours }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-primary-foreground/70">Kontak & Pemesanan</p>
                        <div v-if="store.phone" class="space-y-2">
                            <a
                                :href="`https://wa.me/${store.phone}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex min-h-10 items-center gap-2 rounded-xl bg-primary-foreground/15 px-3.5 py-2 text-sm font-medium text-primary-foreground backdrop-blur-sm transition-colors hover:bg-primary-foreground/25 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-foreground"
                            >
                                <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                <span>WhatsApp ({{ store.phone }})</span>
                            </a>
                        </div>
                        <p v-else class="text-sm text-primary-foreground/70">Hubungi kami melalui katalog online.</p>
                    </div>
                </div>

                <div class="mt-10 border-t border-primary-foreground/15 pt-6 text-center text-xs text-primary-foreground/70 sm:flex sm:items-center sm:justify-between sm:text-left">
                    <p>© {{ new Date().getFullYear() }} {{ store.name }}. Semua hak dilindungi.</p>
                    <p class="mt-2 sm:mt-0">Dibuat dengan cinta untuk keindahan bunga.</p>
                </div>
            </div>
        </footer>
    </div>
</template>


