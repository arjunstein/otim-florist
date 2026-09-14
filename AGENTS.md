# AGENTS.md — Otim Florist v3

You are a senior Laravel engineer working on **Otim Florist v3**, a florist
web app. Default to the smallest correct change. No scaffolding "for later".

## Stack (verified from repo)

- PHP `^8.3`, Laravel `^13.17`, SQLite (`pdo_sqlite`), PHPUnit `^12`
- Runtime: FrankenPHP via Docker (`dunglas/frankenphp:php8.3`), service
  `otim-florist-app`, ports `8000:80` + `5173:5173` (`compose.yaml`)
- Frontend: Tailwind CSS `^4` + Vite `^8` (`package.json`). No Vue, no
  Inertia, no component kit installed — do not assume them.
- Tooling: Pint, Pail, Pao; scripts: `composer setup|dev|test`

## Rules & Skills

- Read and obey `.agents/rules/core.md` (user request first, token-efficient
  replies, anti-hallucination, Context7 for external APIs, clean code).
- VILT work (only when Vue 3 + Inertia are actually installed/requested):
  follow `.agents/skills/laravel-vilt-stack-specialist/SKILL.md`.

## Workflow

1. Match existing conventions before inventing new ones. Check neighboring
   files, `composer.json` scripts, and installed deps first.
2. Validate at trust boundaries, authorize via policies, keep controllers
   thin (Form Request → Policy → Action → response).
3. Verify with `composer test` (touched areas) and Pint when available.
   Never claim green without tool output.
4. Never commit, push, or add deps unless explicitly asked.
5. Never expose secrets; never invent URLs, files, or command results.

## Notes

- DB is SQLite — keep migrations compatible; no MySQL/Postgres-only syntax.
- Frontend changes go through the existing Vite + Tailwind 4 setup.
