<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { applyTheme, currentTheme, type Theme } from '@/theme';
import { activeToast, dismissToast, showToast } from '@/toast';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

const page = usePage();

const nav = [
    { label: 'Dashboard', href: '/dashboard', component: 'Dashboard/Overview' },
    { label: 'Products', href: '/products', component: 'Dashboard/Products' },
    { label: 'Categories', href: '/categories', component: 'Dashboard/Categories' },
    { label: 'Settings', href: '/settings', component: 'Dashboard/Settings' },
] as const;

const current = computed(() => page.component as string);
const title = computed(
    () => nav.find((item) => item.component === current.value)?.label ?? 'Dashboard',
);
const flash = computed(() => page.props.flash as { success?: string; error?: string });
const auth = computed(() => page.props.auth as { user: { name: string; email: string } | null });
const store = computed(() => (page.props.store as { name?: string } | undefined) ?? { name: 'Otim Florist' });
const userInitial = computed(() => auth.value.user?.name.trim().charAt(0).toUpperCase() ?? 'A');

const theme = ref<Theme>(currentTheme());
const isDark = ref(false);
const mobileNavigationOpen = ref(false);
const mobileNavigationToggle = ref<HTMLButtonElement | null>(null);
const mobileNavigationClose = ref<HTMLButtonElement | null>(null);
const logoutDialog = ref<HTMLDialogElement | null>(null);
const cancelLogoutButton = ref<HTMLButtonElement | null>(null);

function setTheme(value: Theme): void {
    theme.value = value;
    applyTheme(value);
    isDark.value = typeof document !== 'undefined' && document.documentElement.classList.contains('dark');
}

function toggleTheme(): void {
    setTheme(isDark.value ? 'light' : 'dark');
}

function logout(): void {
    router.post('/logout');
}

function openLogoutDialog(): void {
    logoutDialog.value?.showModal();
    nextTick(() => cancelLogoutButton.value?.focus());
}

function closeLogoutDialog(): void {
    logoutDialog.value?.close();
}

function updateSystemTheme(): void {
    if (theme.value === 'system') {
        setTheme('system');
    }
}

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

function closeMobileNavigationFromControl(): void {
    closeMobileNavigation();
}

function handleKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && mobileNavigationOpen.value && !logoutDialog.value?.open) {
        closeMobileNavigation();
    }
}

function closeNavigationOnDesktop(): void {
    if (desktopViewport?.matches) {
        closeMobileNavigation(false);
    }
}

let systemTheme: MediaQueryList | undefined;
let desktopViewport: MediaQueryList | undefined;

watch(mobileNavigationOpen, (isOpen) => {
    if (typeof document !== 'undefined') {
        document.body.classList.toggle('overflow-hidden', isOpen);
    }
});
watch(
    () => flash.value.success,
    (message) => {
        if (message) {
            showToast('success', message);
        }
    },
    { immediate: true },
);
watch(
    () => flash.value.error,
    (message) => {
        if (message) {
            showToast('error', message);
        }
    },
    { immediate: true },
);

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');
    systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
    desktopViewport = window.matchMedia('(min-width: 768px)');
    applyTheme(theme.value);
    systemTheme.addEventListener('change', updateSystemTheme);
    desktopViewport.addEventListener('change', closeNavigationOnDesktop);
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    systemTheme?.removeEventListener('change', updateSystemTheme);
    desktopViewport?.removeEventListener('change', closeNavigationOnDesktop);
    window.removeEventListener('keydown', handleKeydown);
    document.body.classList.remove('overflow-hidden');
});
</script>

<template>
    <div class="dashboard-layout min-h-dvh text-foreground">
        <a
            href="#main-content"
            class="sr-only fixed left-4 top-4 z-50 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground focus:not-sr-only"
        >
            Skip to content
        </a>
        <Transition
            enter-active-class="transition duration-200 ease-out motion-reduce:transition-none"
            enter-from-class="translate-y-2 opacity-0"
            leave-active-class="transition duration-150 ease-in motion-reduce:transition-none"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-if="activeToast"
                :role="activeToast.type === 'error' ? 'alert' : 'status'"
                aria-live="polite"
                :class="[
                    'fixed right-4 top-4 z-[60] flex w-[calc(100%-2rem)] max-w-sm items-start gap-3 rounded-2xl border p-4 shadow-lg backdrop-blur-md',
                    activeToast.type === 'success'
                        ? 'bg-success text-success-foreground border-success/30'
                        : 'bg-destructive text-destructive-foreground border-destructive/30',
                ]"
            >
                <p class="flex-1 text-sm font-semibold">{{ activeToast.message }}</p>
                <button
                    type="button"
                    class="grid size-8 shrink-0 place-items-center rounded-lg transition-colors duration-200 hover:bg-card/30"
                    aria-label="Dismiss notification"
                    @click="dismissToast"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </Transition>
        <dialog
            ref="logoutDialog"
            aria-labelledby="logout-dialog-title"
            aria-describedby="logout-dialog-description"
            class="m-auto w-[calc(100%-2rem)] max-w-sm overflow-hidden rounded-2xl border bg-card p-0 text-card-foreground shadow-xl backdrop:bg-foreground/30 backdrop:backdrop-blur-sm"
        >
            <div class="px-5 pb-5 pt-6 sm:px-6">
                <div class="flex size-11 items-center justify-center rounded-xl bg-secondary text-secondary-foreground">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path d="M10 17l5-5-5-5M15 12H3M14 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h2 id="logout-dialog-title" class="mt-4 text-lg font-semibold tracking-tight">Sign out?</h2>
                <p id="logout-dialog-description" class="mt-2 text-sm leading-6 text-muted-foreground">
                    You will need to sign in again to access the dashboard.
                </p>
            </div>
            <footer class="flex flex-col-reverse gap-2 border-t bg-muted/30 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                <button
                    ref="cancelLogoutButton"
                    type="button"
                    class="min-h-11 rounded-xl px-4 text-sm font-semibold text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                    @click="closeLogoutDialog"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                    @click="logout"
                >
                    Sign out
                </button>
            </footer>
        </dialog>
        <aside
            class="fixed inset-y-0 left-0 hidden w-72 flex-col border-r border-border/80 bg-card/85 backdrop-blur-md md:flex"
        >
            <div class="border-b border-border/80 px-6 py-5">
                <Link href="/dashboard" class="group flex items-center gap-3 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary transition-transform duration-300 group-hover:scale-105">
                        <svg class="size-6" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                            <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                            <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                            <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".5" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate font-semibold tracking-tight text-foreground transition-colors group-hover:text-primary">{{ store.name }}</p>
                        <p class="text-xs text-muted-foreground">Florist Management</p>
                    </div>
                </Link>
            </div>
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-4" aria-label="Dashboard navigation">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :aria-current="current === item.component ? 'page' : undefined"
                    :class="[
                        'flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium transition-colors duration-200',
                        current === item.component
                            ? 'bg-primary text-primary-foreground shadow-xs font-semibold'
                            : 'text-muted-foreground hover:bg-secondary hover:text-foreground',
                    ]"
                >
                    <svg v-if="item.component === 'Dashboard/Overview'" class="size-4 shrink-0 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                        <rect x="14" y="14" width="7" height="7" rx="1.5" />
                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    </svg>
                    <svg v-else-if="item.component === 'Dashboard/Products'" class="size-4 shrink-0 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a4 4 0 0 1 4 4c0 1.9-1.3 3.5-3 3.9V13h3.5A3.5 3.5 0 0 1 20 16.5c0 1.6-1.1 3-2.6 3.4L17 22H7l-.4-2.1A3.5 3.5 0 0 1 4 16.5 3.5 3.5 0 0 1 7.5 13H11V9.9A4 4 0 0 1 8 6a4 4 0 0 1 4-4Z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg v-else-if="item.component === 'Dashboard/Categories'" class="size-4 shrink-0 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="7" y1="7" x2="7.01" y2="7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg v-else class="size-4 shrink-0 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ item.label }}</span>
                </Link>
            </nav>
            <div class="border-t border-border/80 p-4">
                <div class="flex items-center gap-3 rounded-xl bg-secondary/60 p-2.5">
                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary text-sm font-semibold text-primary-foreground shadow-2xs">
                        {{ userInitial }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-foreground">{{ auth.user?.name }}</p>
                        <p class="truncate text-xs text-muted-foreground">{{ auth.user?.email }}</p>
                    </div>
                    <button
                        type="button"
                        class="grid size-10 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors duration-200 hover:bg-destructive/10 hover:text-destructive focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        aria-label="Sign out"
                        title="Sign out"
                        @click="openLogoutDialog"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M10 17l5-5-5-5M15 12H3M14 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <Transition
            enter-active-class="transition-opacity duration-200 ease-out motion-reduce:transition-none"
            enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-150 ease-in motion-reduce:transition-none"
            leave-to-class="opacity-0"
        >
            <div v-if="mobileNavigationOpen" class="fixed inset-0 z-50 md:hidden">
                <button
                    type="button"
                    class="absolute inset-0 bg-foreground/30 backdrop-blur-sm"
                    aria-label="Close navigation"
                    @click="closeMobileNavigationFromControl"
                />
                <aside
                    id="mobile-navigation"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="mobile-navigation-title"
                    class="relative flex h-dvh w-72 max-w-[calc(100%-2rem)] flex-col border-r border-border/80 bg-card/95 backdrop-blur-md shadow-xl"
                >
                    <div class="flex items-center justify-between border-b border-border/80 px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="grid size-8 place-items-center rounded-lg bg-primary/10 text-primary">
                                <svg class="size-5" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                                    <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".2" />
                                    <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                                    <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".5" />
                                </svg>
                            </div>
                            <h2 id="mobile-navigation-title" class="font-semibold tracking-tight text-foreground">{{ store.name }}</h2>
                        </div>
                        <button
                            ref="mobileNavigationClose"
                            type="button"
                            class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground"
                            aria-label="Close navigation"
                            @click="closeMobileNavigationFromControl"
                        >
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex flex-1 flex-col gap-1 p-4" aria-label="Mobile dashboard navigation">
                        <Link
                            v-for="item in nav"
                            :key="item.href"
                            :href="item.href"
                            :aria-current="current === item.component ? 'page' : undefined"
                            :class="[
                                'flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium transition-colors duration-200',
                                current === item.component
                                    ? 'bg-primary text-primary-foreground shadow-xs font-semibold'
                                    : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground',
                            ]"
                            @click="closeMobileNavigation(false)"
                        >
                            <span>{{ item.label }}</span>
                        </Link>
                    </nav>
                    <div class="border-t border-border/80 p-4">
                        <div class="flex items-center gap-3 rounded-xl bg-secondary/60 p-2.5">
                            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary text-sm font-semibold text-primary-foreground shadow-2xs">
                                {{ userInitial }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-foreground">{{ auth.user?.name }}</p>
                                <p class="truncate text-xs text-muted-foreground">{{ auth.user?.email }}</p>
                            </div>
                            <button
                                type="button"
                                class="grid size-10 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors duration-200 hover:bg-destructive/10 hover:text-destructive"
                                aria-label="Sign out"
                                title="Sign out"
                                @click="openLogoutDialog"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M10 17l5-5-5-5M15 12H3M14 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </Transition>

        <div class="md:pl-72">
            <header class="sticky top-0 z-10 border-b border-border/80 bg-card/85 backdrop-blur-md">
                <div class="flex min-h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button
                        ref="mobileNavigationToggle"
                        type="button"
                        class="grid size-11 place-items-center rounded-xl text-muted-foreground transition-colors duration-200 hover:bg-secondary hover:text-secondary-foreground md:hidden"
                        aria-label="Open navigation"
                        aria-controls="mobile-navigation"
                        :aria-expanded="mobileNavigationOpen"
                        @click="openMobileNavigation"
                    >
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="hidden font-serif text-lg font-semibold tracking-tight text-foreground md:inline">{{ title }}</span>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <Link
                            href="/"
                            class="group inline-flex min-h-10 items-center gap-1.5 rounded-full border border-border/80 bg-background/60 px-3.5 text-xs font-semibold text-foreground/80 shadow-2xs backdrop-blur-xs transition-all duration-200 hover:border-primary/40 hover:bg-secondary hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            title="Buka halaman public toko"
                        >
                            <svg class="size-3.5 text-muted-foreground transition-colors group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Lihat Toko</span>
                        </Link>
                        <button
                            type="button"
                            class="grid size-10 place-items-center rounded-full border border-border/80 bg-background/60 text-secondary-foreground shadow-2xs backdrop-blur-xs transition-colors duration-200 hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            aria-label="Toggle dark mode"
                            :aria-pressed="isDark"
                            :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                            @click="toggleTheme"
                        >
                            <svg v-if="isDark" class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.75" />
                                <path d="M12 2.5v2M12 19.5v2M21.5 12h-2M4.5 12h-2M18.7 5.3l-1.4 1.4M6.7 17.3l-1.4 1.4M18.7 18.7l-1.4-1.4M6.7 6.7 5.3 5.3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                            </svg>
                            <svg v-else class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5 8.5 8.5 0 1 0 20.5 14.2Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <main id="main-content" class="mx-auto flex max-w-7xl flex-col gap-6 p-4 sm:p-6 lg:gap-8 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
