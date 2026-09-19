# Overview — Otim Florist v3

Florist web app on Laravel 13 + SQLite + Vue 3 + Inertia v3 + TypeScript +
Tailwind 4 + Vite 8, served by FrankenPHP in Docker. Has a dummy SPA
dashboard (no DB-backed domain features yet).

## Stack

- Backend: PHP 8.3, Laravel 13.17, Inertia adapter v3, Eloquent, PHPUnit 12
- Frontend: Vue 3.5, `@inertiajs/vue3` v3, TypeScript 5 (`vue-tsc`),
  Tailwind CSS 4, Vite 8 (`resources/js/app.ts`, `tsconfig.json`)
- DB: SQLite file (`database/database.sqlite`), sessions/cache/queue on DB driver
- Runtime: `dunglas/frankenphp:php8.3`, container `otim-florist-app`
  (`8000:80`, `5173:5173`)

## Layout

- `app/Http/Controllers/DashboardController.php` — dummy props for 3 pages
- `routes/web.php` — `/`, `/products`, `/settings` (+ `PUT /settings`)
- `resources/js/Pages/Dashboard/` — `Overview.vue`, `Products.vue`, `Settings.vue`
- `resources/js/Layouts/DashboardLayout.vue` — persistent sidebar layout
- `resources/js/types.ts` — shared prop interfaces
- `database/migrations/` — users, cache, jobs tables (defaults)
- `config/` — stock Laravel config; mail/broadcast default to `log`

## Agent Entry Points

- Persona: `AGENTS.md` (repo root)
- Rules: `.agents/rules/core.md`
- Skills: `.agents/skills/laravel-vilt-stack-specialist/SKILL.md`
- Setup & commands: `.agents/docs/setup.md`
