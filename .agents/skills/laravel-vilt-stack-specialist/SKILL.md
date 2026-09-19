---
name: laravel-vilt-stack-specialist
description: Use when building or modifying Laravel + Vue 3 + Inertia.js + Tailwind CSS (VILT) features. Covers version policy, thin controllers, Inertia props, Vue Composition API, Tailwind tokens, Vite delivery, testing, and boundaries.
---

# Laravel VILT Stack Specialist

Build cohesive VILT features. Server concerns stay in Laravel, page interaction stays in Vue. No Livewire/Alpine unless the request explicitly includes them.

## Version Policy

- New app or requested upgrade: latest stable, mutually compatible PHP, Laravel, Vue, Inertia (adapter + client), Tailwind, Vite. No prereleases unless requested.
- Scoped change: never upgrade dependencies to fit the change; report the blocking constraint instead.
- Verify version-specific APIs against installed config or current official docs before implementing.

## Laravel

- Thin controllers: route → Form Request (validate) → Policy (authorize) → Action/Service (mutate) → `Inertia::render()` or redirect with flash.
- Transactions only when multiple writes must stay atomic.
- Shape props deliberately: eager-load needed relations (no N+1), paginate server-side, expose minimal attributes (API Resources/DTOs, never raw models with hidden secrets).
- Shared props (`HandleInertiaRequests`) only for app-wide data: auth user, flash, locale, permissions.
- Mutations: conventional redirects + flash on success; return 422 with validation errors for Inertia forms.
- File uploads: validate mime/size server-side, store via disks, never trust client paths.

## Vue 3 + Inertia

- Match existing structure; prefer `<script setup>` + Composition API; TypeScript where the project uses it.
- Forms: `useForm()` for state, errors, pending; show loading/error/empty states; no client-side duplication of server state in a store.
- Navigation: partial reloads (`only`), `preserveState`/`preserveScroll` where correct; named routes via project route helper (e.g. Ziggy), no hard-coded URLs.
- Reusable UI as small components + composables; page components stay thin and prop-driven.
- Auth/permissions: hide UI by permission but always re-authorize server-side.

## Tailwind + Vite

- Use project design tokens (colors, spacing, radius) and responsive utilities; semantic accessible HTML (labels, focus states, aria where needed); custom CSS only for what utilities cannot express.
- Existing Vite setup only; no Laravel Mix; code-split heavy pages; lazy-load below-fold components.
- Assets: versioned builds, no absolute local paths in committed code.

## Testing

- Server first: Laravel feature tests for auth, validation, policies, redirects/flash; assert Inertia component + props (`assertInertia`) where relevant.
- Frontend tests only if the project already has that tooling; otherwise rely on server tests + manual verification.
- Verify with `php artisan test` for touched areas; run pint/lint when available.

## Boundaries

- No new state libraries, component kits, packages, or abstraction layers unless the request or existing architecture justifies them.
- Keep package manager + lockfile conventions; keep all versions mutually compatible.
- Never expose secrets, tokens, or internal fields in props, logs, or client code.
