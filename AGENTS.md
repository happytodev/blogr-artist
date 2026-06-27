# Blogr Artist AGENTS.md

## ⚠️ Issue creation — MANDATORY

**Every bug fix or new feature MUST trigger a GitHub issue before any code
is written or proposed.** This ensures traceability.

- User says "there is a bug" → create issue with `--label bug`
- User says "I need a feature" → create issue with `--label feature`
- The issue is created via `gh issue create` immediately upon understanding the need
- The issue MUST be closed when the work is merged into `main`
- Skipping this is a process error

## ⚠️ Commit policy — ZERO TOLERANCE

**NEVER commit, amend, tag, or push unless the user explicitly loads the
`release-manager` skill and requests a release.** All commits must go
through the `release-manager` workflow. Violating this rule is a process error.

## ⚠️ TDD requirement — ZERO TOLERANCE

**Every bug fix and every feature addition MUST be driven by tests written
first (TDD).** Run the test to confirm it fails before implementing, then
run it again to confirm it passes after.

## Stack

- PHP 8.3+, Laravel 12.x, FilamentPHP v4, Pest PHP 4.0
- Testbench 10.x, in-memory SQLite
- Spatie Package Tools, Spatie Eloquent Sortable

## Commands

```bash
vendor/bin/pest --no-coverage       # Run all tests
vendor/bin/pest tests/Feature/SpecificTest.php
```

## Code conventions

- **Translation-First**: main tables hold non-translatable fields; translation tables hold title, slug, content, SEO, photos.
- **Feature tests** must declare `uses()` individually (Pest.php only covers base TestCase).
- **All new code** must include tests covering the new behavior.
- **Artwork category**: use `category_id` foreign key on `artworks` table (BelongsTo), not the translation pivot.
- **Tags**: use `relatedTags()` BelongsToMany via `artwork_translation_tag` pivot table.

## Resources

| File | Content |
|------|---------|
| [README.md](README.md) | Installation, prerequisites |
