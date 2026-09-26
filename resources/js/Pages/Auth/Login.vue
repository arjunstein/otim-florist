<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const showPassword = ref(false);

function submit(): void {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Sign in">
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <main class="grid min-h-dvh place-items-center bg-background p-4 sm:p-6">
        <section class="w-full max-w-md rounded-2xl border bg-card p-6 shadow-lg sm:p-8" aria-labelledby="login-title">
            <div class="flex items-center gap-3">
                <svg class="size-11 text-primary" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <path d="M20 7c4.7 0 8 4.3 6.8 8.8C31.3 14.6 35 18 35 22.5c0 4.3-4 7.4-8.2 6.3.5 4.6-3 8.7-7.5 8.7s-8-4.1-7.5-8.7C7.5 30 3.5 26.8 3.5 22.5c0-4.5 3.7-7.9 8.2-6.7C10.5 11.3 13.8 7 18.5 7Z" fill="currentColor" fill-opacity=".14" />
                    <path d="M20 12.5c1.9-3.6 6.5-3.8 8.5-.4 1.8 3 .1 6.3-2.7 7.2 3.8-.4 6.3 3.5 4.6 6.8-1.5 2.9-5.2 3-7.2 1.1 1.4 3.5-2 7-5.5 5.8-3.2-1.1-3.7-4.7-1.8-7.1-3.2 2-7.2-.4-6.6-4 .5-3.2 3.8-4.3 6.7-2.6-2.8-1.6-3-5.4-.5-7 1.8-1.2 4.2-.2 4.5 1.3Z" fill="currentColor" />
                    <circle cx="20" cy="21" r="2.5" fill="currentColor" fill-opacity=".45" />
                </svg>
                <div>
                    <p class="font-semibold tracking-tight">Otim Florist</p>
                    <p class="mt-0.5 text-sm text-muted-foreground">Store management</p>
                </div>
            </div>

            <div class="mt-8">
                <h1 id="login-title" class="text-2xl font-semibold tracking-tight">Welcome back</h1>
                <p class="mt-2 text-sm leading-6 text-muted-foreground">Sign in to access your dashboard.</p>
            </div>

            <form class="mt-6 flex flex-col gap-4" @submit.prevent="submit">
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        autofocus
                        :aria-describedby="form.errors.email ? 'email-error' : undefined"
                        :aria-invalid="Boolean(form.errors.email)"
                        :class="[
                            'min-h-11 w-full rounded-xl border bg-background px-3 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20',
                            form.errors.email ? 'border-destructive' : '',
                        ]"
                        placeholder="you@example.com"
                    />
                    <p v-if="form.errors.email" id="email-error" role="alert" class="text-sm text-destructive">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-sm font-medium">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            :aria-describedby="form.errors.password ? 'password-error' : undefined"
                            :aria-invalid="Boolean(form.errors.password)"
                            :class="[
                                'min-h-11 w-full rounded-xl border bg-background px-3 pr-12 text-sm shadow-xs transition-colors placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20',
                                form.errors.password ? 'border-destructive' : '',
                            ]"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 grid size-11 place-items-center rounded-r-xl text-muted-foreground transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            @click="showPassword = !showPassword"
                        >
                            <svg v-if="showPassword" class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m3 3 18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 4.3A10.9 10.9 0 0 1 12 4c5.5 0 9.5 4.6 10 8-.2 1.2-.9 2.7-2.1 4M6.2 6.2C4.1 7.7 2.6 10 2 12c.5 3.4 4.5 8 10 8 1.6 0 3-.4 4.2-1.1" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg v-else class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12s3.5-8 10-8 10 8 10 8-3.5 8-10 8S2 12 2 12Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.75" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password" id="password-error" role="alert" class="text-sm text-destructive">
                        {{ form.errors.password }}
                    </p>
                </div>

                <label class="flex min-h-11 items-center gap-3 text-sm text-muted-foreground">
                    <input v-model="form.remember" type="checkbox" class="size-4 rounded border-border text-primary focus:ring-ring" />
                    Remember me
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-11 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition-colors duration-200 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ form.processing ? 'Signing in' : 'Sign in' }}
                </button>
            </form>
        </section>
    </main>
</template>
