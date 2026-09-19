# Core Rules

## 1. User Request First

- Do exactly what is asked. No extra features, refactors, or files beyond the request.
- Ambiguous but safe assumption: state it, then proceed. Ambiguous and risky: ask first.
- Never commit/push unless explicitly requested.

## 2. Consistent, Token-Efficient Responses

- Answer directly, < 4 lines of text (excluding tools/code). No preamble/postamble, no tool-call narration.
- Match the user's language (Indonesian → Indonesian, English → English). Keep identifiers, paths, errors, commands verbatim.
- One word/sentence suffices → don't elaborate. Detail only when asked or for security warnings/irreversible actions.
- Batch independent tool calls in one block. Prefer `rg`/Grep/Glob for search.

## 3. Anti-Hallucination

- Claims only from: user, repo, tool output, or verified sources. Never invent files, code, URLs, statuses.
- Verify every change via file read, test, lint, or relevant build before reporting success.
- Say plainly when evidence is missing/conflicting. Separate fact vs assumption vs recommendation.
- Never claim tests/lint/build passed without tool output proving it.

## 4. Context7 Required for External APIs

- Any implementation decision depending on external APIs/libraries/frameworks (syntax, config, version migration, CLI, setup): resolve library ID then query Context7 first.
- Don't rely on training data for APIs that may have changed. Cite sources when quoting external info.
- No Context7 needed for: pure business-logic refactors, code style review, general programming concepts.

## 5. Clean Code

- Requested code only; delete over add. Boring over clever. Smallest working diff wins.
- Before writing code: (1) must it exist? (2) stdlib/platform covers it? (3) installed dependency covers it? (4) one-liner suffices? (5) then write minimal.
- Follow surrounding conventions: naming, structure, libraries already used. No new dependency for trivial needs.
- No comments unless asked. No premature abstraction (single-impl interface, single-product factory, static-value config).
- Never sacrifice: input validation at trust boundaries, data-loss-proof error handling, security, accessibility, anything explicitly requested.
## 6. Docker-First Tooling

- Host has no PHP/Composer (`php`, `composer` not found). Run all PHP /
  Composer / Artisan / Node work inside the running container
  `otim-florist-app` (service `app`):
  `docker compose exec app <cmd>` (e.g. `docker compose exec app composer test`,
  `docker compose exec app php artisan migrate`, `docker compose exec app npm run build`).
- Never install PHP/Composer/Node on the host to work around this.
- If the container is down, start it with `docker compose up -d` first.
