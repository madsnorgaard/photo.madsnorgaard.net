# photo.madsnorgaard.net

Documentary photography archive. WordPress REST API backend (this repo) with a headless Nuxt 3 frontend at `madsnorgaard.net` (separate repo, in progress).

**Stack:** WordPress 6.x · PHP 8.4 · MySQL 8.0 · DDEV local dev · Docker on VPS2 production

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
| Akismet | `wpackagist-plugin/akismet` |
| Autoptimize | `wpackagist-plugin/autoptimize` |
| Contact Form 7 | `wpackagist-plugin/contact-form-7` |
| Google Analytics (MonsterInsights) | `wpackagist-plugin/google-analytics-for-wordpress` |
| Google Captcha | `wpackagist-plugin/google-captcha` |
| Intuitive CPT Order | `wpackagist-plugin/intuitive-custom-post-order` |
| Rank Math SEO | `wpackagist-plugin/seo-by-rank-math` |
| Wordfence | `wpackagist-plugin/wordfence` |
| WP Super Cache | `wpackagist-plugin/wp-super-cache` |
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
| `photo-archive-cpts` | Custom CPTs (photos, stories, projects) |
| `photo-archive-blocks` | Custom Gutenberg blocks |
| `photo-api-security` | REST API hardening |
| `mauer-stills-gallery` | Premium Mauer Themes plugin |
| `mauer-stills-portfolio` | Premium Mauer Themes plugin |
| `advanced-custom-fields-pro` | Premium — see ACF upgrade below |
| `photection` | Premium image protection |

#### ACF Pro upgrade path

ACF 5.7.13 runs with PHP 8.4/WP 6.9 deprecations suppressed via `mu-plugins/suppress-acf5-notices.php`.
To upgrade to 6.x via Composer:

1. Get your key from `https://www.advancedcustomfields.com/my-account/`
2. Add to `composer.json`:
   ```json
   "repositories": [
     { "type": "composer", "url": "https://connect.advancedcustomfields.com" }
   ],
   "require": {
     "wpengine/advanced-custom-fields-pro": "^6.0"
   },
   "extra": {
     "installer-paths": {
       "wp-content/plugins/{$name}/": ["type:wordpress-plugin"]
     }
   }
   ```
3. Add `ACF_PRO_KEY=your-key` to `.env` and add it to `web_environment` in `.ddev/config.yaml`
4. Run `ddev composer install`
5. Remove old plugin from git: `git rm -r --cached wp-content/plugins/advanced-custom-fields-pro`
6. Add `wp-content/plugins/advanced-custom-fields-pro/` to `.gitignore`
7. Delete `wp-content/mu-plugins/suppress-acf5-notices.php`

---

## Themes

| Theme | Status |
|-------|--------|
| `mauer-stills` | Parent theme — purchased, committed to git |
| `mauer-stills-child` | Child theme — custom gallery CSS overrides |
| `photo-archive` | Custom archive theme |
| `twentytwenty*` | Default WP themes — gitignored, not needed |

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

ACF meta on photos: `archive_number`, `location`, `date_taken` (Y-m-d), `camera`.

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

- Never commit `.env`, database credentials, or ACF Pro license keys
- WP-CLI Application Passwords: admin only
- XML-RPC permanently blocked at application level
- REST API rate-limited, user enumeration blocked
