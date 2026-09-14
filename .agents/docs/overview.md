# Overview — Otim Florist v3

Florist web app on Laravel 13 + SQLite + Tailwind 4 + Vite 8, served by
FrankenPHP in Docker. Fresh skeleton state: default routes, views, and
migrations only — no domain features built yet.

## Stack

- Backend: PHP 8.3, Laravel 13.17, Eloquent, Blade, PHPUnit 12
- Frontend: Tailwind CSS 4, Vite 8, vanilla JS (`resources/js/app.js`)
- DB: SQLite file (`database/database.sqlite`), sessions/cache/queue on DB driver
- Runtime: `dunglas/frankenphp:php8.3`, container `otim-florist-app`
  (`8000:80`, `5173:5173`)

## Layout

- `app/` — `Http/Controllers`, `Models`, `Providers` (skeleton only)
- `routes/web.php` — single `/` route rendering `welcome` view
- `resources/views/welcome.blade.php` — default Laravel welcome page
- `database/migrations/` — users, cache, jobs tables (defaults)
- `config/` — stock Laravel config; mail/broadcast default to `log`

## Agent Entry Points

- Persona: `AGENTS.md` (repo root)
- Rules: `.agents/rules/core.md`
- Skills: `.agents/skills/laravel-vilt-stack-specialist/SKILL.md`
- Setup & commands: `.agents/docs/setup.md`
