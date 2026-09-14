<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <main class="min-h-screen grid place-items-center bg-stone-50">
        <div class="text-center">
            <h1 class="text-3xl font-semibold text-stone-900">Otim Florist</h1>
            <p class="mt-2 text-stone-600">{{ message }}</p>
        </div>
    </main>
</template>
