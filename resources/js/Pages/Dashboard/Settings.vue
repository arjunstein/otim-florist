<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { SettingsProps } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps<SettingsProps>();

const form = useForm({ ...props.store });
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
    { key: 'phone', label: 'Phone', hint: 'Customer contact number.' },
    { key: 'address', label: 'Address', hint: 'Pickup and delivery origin.' },
    { key: 'hours', label: 'Opening hours', hint: 'E.g. 08:00–20:00 daily.' },
] as const;
</script>

<template>
    <PageHeader title="Settings" subtitle="Store profile and account security." />

    <CardSection title="Store profile" subtitle="Changes are validated, nothing is persisted.">
        <form @submit.prevent="submit" class="grid gap-5 md:grid-cols-2">
            <div v-for="field in fields" :key="field.key" class="flex flex-col gap-1.5">
                <label :for="field.key" class="text-sm font-medium">
                    {{ field.label }}
                </label>
                <input
                    :id="field.key"
                    v-model="form[field.key]"
                    :aria-describedby="`${field.key}-hint`"
                    :aria-invalid="Boolean(form.errors[field.key])"
                    :class="[
                        'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors focus:border-ring focus:ring-2 focus:ring-ring/20',
                        form.errors[field.key] ? 'border-destructive' : '',
                    ]"
                />
                <p v-if="form.errors[field.key]" :id="`${field.key}-hint`" class="text-sm text-destructive-foreground">
                    {{ form.errors[field.key] }}
                </p>
                <p v-else :id="`${field.key}-hint`" class="text-sm text-muted-foreground">{{ field.hint }}</p>
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
                <p v-if="passwordForm.errors.current_password" id="current-password-error" role="alert" class="text-sm text-destructive-foreground">
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
            <p v-if="passwordForm.errors.password" id="password-error" role="alert" class="text-sm text-destructive-foreground">
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
