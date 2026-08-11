# photo.madsnorgaard.net

Documentary photography archive. WordPress REST API backend (this repo) with a headless Nuxt 3 frontend at `madsnorgaard.net` (separate repo, in progress).

**Stack:** WordPress 7.x (headless: REST + uploads + wp-admin only) · PHP 8.4 · MySQL 8.0 · DDEV local dev · Docker on VPS2 production

---

## Quick start (local)

```bash
git clone git@github.com:madsnorgaard/photo.madsnorgaard.net.git
cd photo.madsnorgaard.net
cp .env.example .env          # fill in DB credentials for local
ddev start                    # starts DDEV + runs composer install automatically
```

DDEV auto-runs `composer install` on every start via a post-start hook. Third-party plugins are downloaded fresh — they are not committed to git.

To import a database:

```bash
ddev import-db --file=photo_wp.sql
ddev wp cache flush
ddev wp-activate-custom       # activate custom plugins after fresh import
```

---

## Plugin management

Plugins are split into two categories:

### Composer-managed (third-party, via wpackagist.org)

Not committed to git. Installed automatically by `composer install`.

| Plugin | Composer package |
|--------|-----------------|
| Intuitive CPT Order | `wpackagist-plugin/intuitive-custom-post-order` |
| WPS Hide Login | `wpackagist-plugin/wps-hide-login` |

Add a WP.org plugin:

```bash
ddev composer require wpackagist-plugin/plugin-slug
git add composer.json composer.lock && git commit -m "Add plugin-slug"
```

Update all packages:

```bash
ddev composer update
# or:
ddev wp-update
```

### Git-managed (custom + premium)

Committed to `wp-content/plugins/`.

| Plugin | Reason |
|--------|--------|
| `photo-archive-cpts` | Custom CPTs (photos, stories, projects) + native photo meta |
| `photo-archive-blocks` | Custom Gutenberg blocks |
| `photo-api-security` | REST API hardening |
| `event-archive` | Event photo wall endpoints (likes, guestbook, /top) |
| `mauer-stills-gallery` | Premium gallery filters — kept: its markup feeds the Nuxt /proj parser |

#### Headless cutover (2026-08-11)

The mauer-stills theme, ACF Pro 5.7.13, and all front-end-only plugins
(autoptimize, CF7, MonsterInsights, google-captcha, Rank Math, WP Super
Cache, akismet, photection, mauer-stills-portfolio, bulk-block-converter)
were removed. Photo metadata is native `register_post_meta`
(photo-archive-cpts ≥1.3.0); the `project` CPT is registered there too.
All public HTML routes 301 to madsnorgaard.net via
`mu-plugins/headless-redirect.php` (map: `docs/headless-cutover-audit.md`).

---

## Themes

| Theme | Status |
|-------|--------|
| `photo-archive` | **Active** — minimal FSE theme (public HTML is redirected anyway) |
| `twentytwenty*` | Default WP themes — gitignored fallback |

---

## WP-CLI

DDEV exposes WP-CLI via `ddev wp`. Custom shortcut commands:

```bash
ddev wp-update           # composer update + plugin/theme update + cache flush
ddev wp-status           # list plugins and themes with versions
ddev wp-activate-custom  # activate custom plugins after fresh DB import
```

Any WP-CLI command:

```bash
ddev wp plugin list
ddev wp option get siteurl
ddev wp search-replace 'https://photo.madsnorgaard.net' 'https://photo.madsnorgaard.net.ddev.site'
ddev wp cache flush
ddev wp cron event run --due-now
```

---

## REST API endpoints

Base: `https://photo.madsnorgaard.net/wp-json/wp/v2`

| Endpoint | Description |
|----------|-------------|
| `/photos` | Photo archive CPT |
| `/stories` | Story CPT |
| `/projects` | Portfolio projects CPT |
| `/series` | Series taxonomy (flat) |
| `/subjects` | Subjects taxonomy (hierarchical) |

All endpoints are public (read-only). Use `_embed=true` for featured media.

Native registered meta on photos: `archive_number`, `location`, `date_taken` (Y-m-d), `camera`.

CORS origins allowed: `https://madsnorgaard.net`, `http://localhost:3000`, `http://localhost:3001`.

---

## Custom plugins

### photo-api-security

- Blocks XML-RPC (returns 403)
- Removes `/wp/v2/users` endpoint, blocks `?author=N`
- Rate limit: 120 requests/min/IP
- Security headers on all REST responses
- Application Passwords restricted to `manage_options`

### photo-archive-cpts

Registers CPTs and taxonomies. Also registers WP image sizes:

| Size name | Dimensions | Use case |
|-----------|-----------|---------|
| `mauer_stills_thumb_6` | 1440×1440 | Hero / OG image |
| `mauer_stills_thumb_1` | 780px | Half-width tile |
| `mauer_stills_thumb_5` | 420px | Grid thumbnail |
| `mauer_stills_thumb_4` | 300px | Related projects |

---

## Production deployment

Push to `main` → GitHub Actions dispatches `repository_dispatch` to `madsnorgaard/contabo-infrastructure` → self-hosted runner on VPS1 deploys to VPS2.

Deploy steps:
1. Checks out `wp-content/` to `~/docker/photo.madsnorgaard.net/` on VPS2
2. `composer install --no-dev` inside the container
3. Activates custom plugins via WP-CLI
4. Flushes cache

WP-CLI on production:

```bash
cd ~/docker/photo.madsnorgaard.net
docker compose run --rm cli wp plugin list
docker compose run --rm cli wp plugin update --all
docker compose run --rm cli wp cache flush
```

---

## Security

- Never commit `.env` or database credentials
- WP-CLI Application Passwords: admin only
- XML-RPC permanently blocked at application level
- REST API rate-limited, user enumeration blocked

## Updates & CVE tracking

Everything version-pinned, everything watched — merging PRs is the whole job:

- **Dependabot (weekly, Mondays)** opens PRs for: composer plugins
  (wpackagist, majors included — plugin majors carry security fixes), the
  pinned `wordpress:` image tags in `docker-compose.yml`, and GitHub Actions.
- **`security-audit.yml` (weekly, Mondays)** double-checks `composer outdated`
  and Docker Hub for a newer WordPress tag; anything found opens/updates a
  pinned issue titled "Security audit: updates available". Optionally add a
  `WPSCAN_API_TOKEN` repo secret (free tier: wpscan.com) to also cross-check
  installed plugins against the WPScan vulnerability database.
- **WP core minors** self-apply in production inside the `wp_html` volume
  (e.g. the 7.0.2 image self-updated to 7.0.3); the image pin governs the
  PHP runtime and fresh volumes.
- **Admin access**: `/member` + `/wp-admin` sit behind Traefik basic auth
  (`WP_ADMIN_BASIC_AUTH` htpasswd entry in `.env`) — proxy-level protection
  replaced Wordfence 2026-08-11; bots never reach the login form.
  Rotate the passphrase any time with `scripts/rotate-admin-auth.sh`
  (`--generate` for a random one); it hashes server-side, handles the
  compose `$$`-escaping, recreates the container, and self-tests.
- **Before merging any backend change**, run the API contract harness:
  `bash scripts/api-snapshot.sh <base-url> scripts/api-current && diff -ru scripts/api-baseline scripts/api-current`
