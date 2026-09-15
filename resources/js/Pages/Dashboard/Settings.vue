<script setup lang="ts">
import CardSection from '@/Components/CardSection.vue';
import PageHeader from '@/Components/PageHeader.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import type { SettingsProps } from '@/types';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: DashboardLayout });

const props = defineProps<SettingsProps>();

const form = useForm({ ...props.store });

function submit(): void {
    form.put('/settings', { preserveScroll: true });
}

const fields = [
    { key: 'name', label: 'Store name', hint: 'Shown on receipts and the storefront.' },
    { key: 'phone', label: 'Phone', hint: 'Customer contact number.' },
    { key: 'address', label: 'Address', hint: 'Pickup and delivery origin.' },
    { key: 'hours', label: 'Opening hours', hint: 'E.g. 08:00–20:00 daily.' },
] as const;
</script>

<template>
    <PageHeader title="Settings" subtitle="Store profile. Dummy save, flashes only." />

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
</template>
