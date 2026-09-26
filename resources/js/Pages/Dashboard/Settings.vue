<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { SettingsProps } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<SettingsProps>();

const form = useForm({
    name: props.store.name ?? '',
    phone: props.store.phone ?? '',
    address: props.store.address ?? '',
    hours: props.store.hours ?? '',
    google_reviews_url: props.store.google_reviews_url ?? '',
    google_rating: props.store.google_rating ?? '',
    google_reviews_count: props.store.google_reviews_count ?? '',
});
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});
const showCurrentPassword = ref(false);
const showNewPasswords = ref(false);

function submit(): void {
    form.put('/settings', { preserveScroll: true });
}

function updatePassword(): void {
    passwordForm.put('/settings/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
}

const fields = [
    { key: 'name', label: 'Store name', hint: 'Shown on receipts and the storefront.' },
    { key: 'phone', label: 'WhatsApp number', hint: 'Use 08… or 628…; this is used by Buy buttons.' },
    { key: 'address', label: 'Address', hint: 'Pickup and delivery origin.' },
    { key: 'hours', label: 'Opening hours', hint: 'E.g. 08:00–20:00 daily.' },
] as const;
</script>

<template>
    <Head title="Settings" />

    <PageHeader title="Settings" subtitle="Store profile and account security." />

    <CardSection title="Store profile" subtitle="Changes are saved and used on the storefront.">
        <form @submit.prevent="submit" class="grid gap-5 md:grid-cols-2">
            <div v-for="field in fields" :key="field.key" class="flex flex-col gap-1.5">
                <label :for="field.key" class="text-sm font-medium">
                    {{ field.label }}
                </label>
                <input
                    :id="field.key"
                    v-model="form[field.key]"
                    :type="field.key === 'phone' ? 'tel' : 'text'"
                    :inputmode="field.key === 'phone' ? 'tel' : undefined"
                    :autocomplete="field.key === 'phone' ? 'tel' : undefined"
                    :aria-describedby="`${field.key}-hint`"
                    :aria-invalid="Boolean(form.errors[field.key])"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        form.errors[field.key] ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="form.errors[field.key]" :id="`${field.key}-hint`" class="text-sm text-destructive">
                    {{ form.errors[field.key] }}
                </p>
                <p v-else :id="`${field.key}-hint`" class="text-sm text-muted-foreground">{{ field.hint }}</p>
            </div>

            <div class="md:col-span-2 border-t border-border/70 pt-5 mt-2">
                <div class="flex items-center gap-2">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-foreground">Ulasan Google (Google Reviews)</h3>
                </div>
                <p class="text-xs text-muted-foreground mt-1">Kelola rating bintang, jumlah ulasan, dan link profil Google Maps untuk badge storefront.</p>
            </div>

            <div class="md:col-span-2 flex flex-col gap-1.5">
                <label for="google_reviews_url" class="text-sm font-medium">Google Reviews URL</label>
                <input
                    id="google_reviews_url"
                    v-model="form.google_reviews_url"
                    type="url"
                    placeholder="https://share.google/..."
                    aria-describedby="google_reviews_url-hint"
                    :aria-invalid="Boolean(form.errors.google_reviews_url)"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        form.errors.google_reviews_url ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="form.errors.google_reviews_url" id="google_reviews_url-hint" class="text-sm text-destructive">
                    {{ form.errors.google_reviews_url }}
                </p>
                <p v-else id="google_reviews_url-hint" class="text-sm text-muted-foreground">Tautan share Google Maps atau ulasan profil bisnis toko Anda.</p>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="google_rating" class="text-sm font-medium">Google Rating</label>
                <input
                    id="google_rating"
                    v-model="form.google_rating"
                    type="number"
                    step="0.1"
                    min="1"
                    max="5"
                    placeholder="5.0"
                    aria-describedby="google_rating-hint"
                    :aria-invalid="Boolean(form.errors.google_rating)"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        form.errors.google_rating ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="form.errors.google_rating" id="google_rating-hint" class="text-sm text-destructive">
                    {{ form.errors.google_rating }}
                </p>
                <p v-else id="google_rating-hint" class="text-sm text-muted-foreground">Nilai rata-rata rating (skala 1.0 – 5.0).</p>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="google_reviews_count" class="text-sm font-medium">Total Ulasan</label>
                <input
                    id="google_reviews_count"
                    v-model="form.google_reviews_count"
                    type="number"
                    min="0"
                    placeholder="27"
                    aria-describedby="google_reviews_count-hint"
                    :aria-invalid="Boolean(form.errors.google_reviews_count)"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        form.errors.google_reviews_count ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="form.errors.google_reviews_count" id="google_reviews_count-hint" class="text-sm text-destructive">
                    {{ form.errors.google_reviews_count }}
                </p>
                <p v-else id="google_reviews_count-hint" class="text-sm text-muted-foreground">Jumlah ulasan yang masuk di Google Maps.</p>
            </div>

            <div class="md:col-span-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                >
                    <svg v-if="form.processing" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="30 20" />
                    </svg>
                    {{ form.processing ? 'Saving changes' : 'Save changes' }}
                </button>
            </div>
        </form>
    </CardSection>

    <CardSection title="Change password" subtitle="Use at least 8 characters. Other devices will be signed out.">
        <form @submit.prevent="updatePassword" class="max-w-2xl space-y-5">
            <div class="flex flex-col gap-1.5">
                <label for="current_password" class="text-sm font-medium">Current password</label>
                <div class="relative">
                    <input
                        id="current_password"
                        v-model="passwordForm.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        autocomplete="current-password"
                        :aria-describedby="passwordForm.errors.current_password ? 'current-password-error' : undefined"
                        :aria-invalid="Boolean(passwordForm.errors.current_password)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 pr-12 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            passwordForm.errors.current_password ? 'border-destructive' : '',
                        ]"
                    />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 grid size-11 place-items-center rounded-r-xl text-muted-foreground transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        :aria-label="showCurrentPassword ? 'Hide current password' : 'Show current password'"
                        @click="showCurrentPassword = !showCurrentPassword"
                    >
                        <svg v-if="showCurrentPassword" class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m3 3 18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 4.3A10.9 10.9 0 0 1 12 4c5.5 0 9.5 4.6 10 8-.2 1.2-.9 2.7-2.1 4M6.2 6.2C4.1 7.7 2.6 10 2 12c.5 3.4 4.5 8 10 8 1.6 0 3-.4 4.2-1.1" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg v-else class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M2 12s3.5-8 10-8 10 8 10 8-3.5 8-10 8S2 12 2 12Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.75" />
                        </svg>
                    </button>
                </div>
                <p v-if="passwordForm.errors.current_password" id="current-password-error" role="alert" class="text-sm text-destructive">
                    {{ passwordForm.errors.current_password }}
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-sm font-medium">New password</label>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="passwordForm.password"
                            :type="showNewPasswords ? 'text' : 'password'"
                            autocomplete="new-password"
                            :aria-describedby="passwordForm.errors.password ? 'password-error' : undefined"
                            :aria-invalid="Boolean(passwordForm.errors.password)"
                            :class="[
                                'min-h-11 w-full rounded-xl border bg-background px-3 pr-12 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                                passwordForm.errors.password ? 'border-destructive' : '',
                            ]"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 grid size-11 place-items-center rounded-r-xl text-muted-foreground transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            :aria-label="showNewPasswords ? 'Hide new password' : 'Show new password'"
                            @click="showNewPasswords = !showNewPasswords"
                        >
                            <svg v-if="showNewPasswords" class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m3 3 18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 4.3A10.9 10.9 0 0 1 12 4c5.5 0 9.5 4.6 10 8-.2 1.2-.9 2.7-2.1 4M6.2 6.2C4.1 7.7 2.6 10 2 12c.5 3.4 4.5 8 10 8 1.6 0 3-.4 4.2-1.1" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg v-else class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12s3.5-8 10-8 10 8 10 8-3.5 8-10 8S2 12 2 12Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.75" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="password_confirmation" class="text-sm font-medium">Confirm new password</label>
                    <input
                        id="password_confirmation"
                        v-model="passwordForm.password_confirmation"
                        :type="showNewPasswords ? 'text' : 'password'"
                        autocomplete="new-password"
                        :aria-describedby="passwordForm.errors.password ? 'password-error' : undefined"
                        :aria-invalid="Boolean(passwordForm.errors.password)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                            passwordForm.errors.password ? 'border-destructive' : '',
                        ]"
                    />
                </div>
            </div>
            <p v-if="passwordForm.errors.password" id="password-error" role="alert" class="text-sm text-destructive">
                {{ passwordForm.errors.password }}
            </p>

            <button
                type="submit"
                :disabled="passwordForm.processing"
                class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
            >
                <svg v-if="passwordForm.processing" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="30 20" />
                </svg>
                {{ passwordForm.processing ? 'Updating password' : 'Update password' }}
            </button>
        </form>
    </CardSection>
</template>
