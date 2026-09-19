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
const userInitial = computed(() => auth.value.user?.name.trim().charAt(0).toUpperCase() ?? 'A');
const theme = ref<Theme>(currentTheme());
const isDark = ref(document.documentElement.classList.contains('dark'));
const mobileNavigationOpen = ref(false);
const mobileNavigationToggle = ref<HTMLButtonElement | null>(null);
const mobileNavigationClose = ref<HTMLButtonElement | null>(null);
const logoutDialog = ref<HTMLDialogElement | null>(null);
const cancelLogoutButton = ref<HTMLButtonElement | null>(null);

function setTheme(value: Theme): void {
    theme.value = value;
    applyTheme(value);
    isDark.value = document.documentElement.classList.contains('dark');
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
    if (desktopViewport.matches) {
        closeMobileNavigation(false);
    }
}

const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
const desktopViewport = window.matchMedia('(min-width: 768px)');

applyTheme(theme.value);

watch(mobileNavigationOpen, (isOpen) => document.body.classList.toggle('overflow-hidden', isOpen));
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
    systemTheme.addEventListener('change', updateSystemTheme);
    desktopViewport.addEventListener('change', closeNavigationOnDesktop);
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    systemTheme.removeEventListener('change', updateSystemTheme);
    desktopViewport.removeEventListener('change', closeNavigationOnDesktop);
    window.removeEventListener('keydown', handleKeydown);
    document.body.classList.remove('overflow-hidden');
});
</script>

<template>
    <div class="min-h-dvh bg-background text-foreground">
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
                    'fixed right-4 top-4 z-[60] flex w-[calc(100%-2rem)] max-w-sm items-start gap-3 rounded-2xl border p-4 shadow-lg',
                    activeToast.type === 'success'
                        ? 'bg-success text-success-foreground'
                        : 'bg-destructive text-destructive-foreground',
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
            class="fixed inset-y-0 left-0 hidden w-72 flex-col border-r bg-card md:flex"
        >
            <div class="border-b px-6 py-5">
                <div class="flex items-center gap-3">
                    <svg class="size-10 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                        <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                        <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                    </svg>
                    <div>
                        <p class="font-semibold tracking-tight">Otim Florist</p>
                    </div>
                </div>
            </div>
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-4" aria-label="Dashboard navigation">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :aria-current="current === item.component ? 'page' : undefined"
                    :class="[
                        'flex min-h-11 items-center rounded-xl px-3 text-sm font-medium transition-colors duration-200',
                        current === item.component
                            ? 'bg-primary text-primary-foreground shadow-sm'
                            : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground',
                    ]"
                >
                    {{ item.label }}
                </Link>
            </nav>
            <div class="border-t p-4">
                <div class="flex items-center gap-3 rounded-xl bg-secondary/70 p-2">
                    <span class="grid size-10 shrink-0 place-items-center rounded-full bg-primary text-sm font-semibold text-primary-foreground">
                        {{ userInitial }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold">{{ auth.user?.name }}</p>
                        <p class="truncate text-xs text-muted-foreground">{{ auth.user?.email }}</p>
                    </div>
                    <button
                        type="button"
                        class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors duration-200 hover:bg-card hover:text-secondary-foreground"
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
                    class="relative flex h-dvh w-72 max-w-[calc(100%-2rem)] flex-col border-r bg-card shadow-xl"
                >
                    <div class="flex items-center justify-between border-b px-5 py-4">
                        <div>
                            <h2 id="mobile-navigation-title" class="font-semibold tracking-tight">Otim Florist</h2>
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
                                'flex min-h-11 items-center rounded-xl px-3 text-sm font-medium transition-colors duration-200',
                                current === item.component
                                    ? 'bg-primary text-primary-foreground shadow-sm'
                                    : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground',
                            ]"
                            @click="closeMobileNavigation(false)"
                        >
                            {{ item.label }}
                        </Link>
                    </nav>
                    <div class="border-t p-4">
                        <div class="flex items-center gap-3 rounded-xl bg-secondary/70 p-2">
                            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-primary text-sm font-semibold text-primary-foreground">
                                {{ userInitial }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold">{{ auth.user?.name }}</p>
                                <p class="truncate text-xs text-muted-foreground">{{ auth.user?.email }}</p>
                            </div>
                            <button
                                type="button"
                                class="grid size-11 shrink-0 place-items-center rounded-lg text-muted-foreground transition-colors duration-200 hover:bg-card hover:text-secondary-foreground"
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
            <header class="sticky top-0 z-10 border-b bg-card/95 backdrop-blur">
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
                    <p class="hidden text-sm text-muted-foreground md:block">{{ title }}</p>
                    <div class="ml-auto flex items-center gap-2">
                        <button
                            type="button"
                            class="grid size-11 place-items-center rounded-full bg-secondary text-secondary-foreground transition-colors duration-200 hover:bg-muted"
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
