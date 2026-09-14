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
            <div v-for="field in fields" :key="field.key">
                <label :for="field.key" class="mb-1 block text-sm font-medium">
                    {{ field.label }}
                </label>
                <input
                    :id="field.key"
                    v-model="form[field.key]"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600"
                />
                <p v-if="form.errors[field.key]" class="mt-1 text-xs text-red-600">
                    {{ form.errors[field.key] }}
                </p>
                <p v-else class="mt-1 text-xs text-stone-400">{{ field.hint }}</p>
            </div>
            <div class="md:col-span-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'Saving…' : 'Save changes' }}
                </button>
            </div>
        </form>
    </CardSection>
</template>
