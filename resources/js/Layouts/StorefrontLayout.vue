<script setup lang="ts">
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
const page = usePage<{ navigationCategories: NavigationCategory[] }>();
const navigationCategories = computed(() => page.props.navigationCategories);

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
            Skip to content
        </a>
        <header class="sticky top-0 z-20 border-b bg-background/85 backdrop-blur-xl">
            <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex min-h-11 shrink-0 items-center gap-2.5 rounded-xl pr-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <svg class="size-8 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                    <span class="font-semibold tracking-tight">Otim Florist</span>
                </Link>
                <nav class="ml-auto hidden items-center gap-1 lg:flex" aria-label="Main navigation">
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
                    aria-label="Open navigation"
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
                    aria-label="Close navigation"
                    @click="closeMobileNavigation(false)"
                />
                <nav
                    id="mobile-navigation"
                    class="absolute right-0 top-0 flex h-dvh w-72 max-w-[calc(100%-2rem)] flex-col border-l bg-card p-4 shadow-xl"
                    aria-label="Mobile navigation"
                    aria-modal="true"
                    role="dialog"
                >
                    <div class="flex min-h-11 items-center justify-between">
                        <p class="font-semibold tracking-tight">Navigation</p>
                        <button
                            ref="mobileNavigationClose"
                            type="button"
                            class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            aria-label="Close navigation"
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
                aria-label="Back to top"
                title="Back to top"
                @click="scrollToTop"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </Transition>

        <footer class="border-t border-primary/15 bg-primary text-primary-foreground">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm text-primary-foreground/70 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>Thoughtful flowers, simply arranged.</p>
                <p>© {{ new Date().getFullYear() }} Otim Florist</p>
            </div>
        </footer>
    </div>
</template>
