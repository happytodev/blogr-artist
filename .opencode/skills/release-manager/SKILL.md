---
name: release-manager
description: Automate blogr-artist package releases: version bumping, CHANGELOG updates, tagging, and publishing to GitHub.
---

## When to use

Trigger phrases: "release", "tag a new version", "publish vX.Y.Z", "cut a release", "bump version".

## Workflow

### 1. Pre-flight check

```bash
gh pr list --base main --state open --json number,title
```

If open PRs exist, abort and ask the user to merge them first.

### 2. Preview changes since last release

```bash
git log $(git describe --tags --abbrev=0)..HEAD --oneline --no-decorate
```

Present the output to the user.

### 3. Determine the new version

- Read current version from `composer.json` (`"version"`)
- Ask user for bump type: `patch`, `minor`, `major`
- Present the computed version for confirmation

### 4. Run tests

```bash
vendor/bin/pest --no-coverage
```

If ANY test fails, abort immediately.

### 5. Update version files (atomic commit)

- `composer.json` — edit `"version"` field
- Commit: `chore: bump version to v{version}`

### 6. Update CHANGELOG.md (atomic commit)

Prepend a new entry following Keep a Changelog format.

```markdown
## [vX.Y.Z](https://github.com/happytodev/blogr-artist/compare/vA.B.C...vX.Y.Z) - YYYY-MM-DD

### Added / Fixed / Changed

- **Summary**: description
```

Commit: `docs(changelog): v{version}`

### 7. Sync with remote and tag

```bash
git fetch origin main
# Verify local and remote match
git push origin main
git tag v{version}
git push origin v{version}
```

### 8. Create GitHub Release

```bash
gh release create v{version} --title "v{version}" --notes "$RELEASE_NOTES"
```
