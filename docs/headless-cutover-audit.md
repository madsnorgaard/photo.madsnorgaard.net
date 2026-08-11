# Headless cutover audit — photo.madsnorgaard.net public URLs

Snapshot date: 2026-08-11. Source: rank-math `sitemap_index.xml` + REST
(`wp/v2/{posts,pages,project}` + taxonomy terms), cross-checked against the
Nuxt routes in `madsnorgaard.net/frontend/pages/`.

This table drives `mu-plugins/headless-redirect.php`. Everything not listed
falls through to the catch-all `301 → https://madsnorgaard.net/`.

## Disposition table

| URL pattern | Type | Count | Nuxt equivalent | Action |
|---|---|---|---|---|
| `/` | homepage | 1 | `madsnorgaard.net/` | 301 catch-all |
| `/proj/{slug}/` | project CPT | 20 | `madsnorgaard.net/proj/{slug}` (same slugs, verified) | 301 pattern: `/proj/{slug}` → same path on apex |
| `/proj/` | project archive | 1 | `madsnorgaard.net/archive` | 301 |
| `/proj-cat/{slug}/` | project_cat terms (assignments, documentary, portraits, events, commercial) | 5 | `madsnorgaard.net/archive` (listing; per-category filter is client-side) | 301 → `/archive` |
| `/{post-slug}/` | posts ("one-picture stories", 20) | 20 | `madsnorgaard.net/post/{slug}` (renders WP posts via `/api/wp/posts/{slug}`) | 301 pattern: single post → `/post/{slug}` |
| `/category/{slug}/` | post categories (9 incl. nested `storytelling/local`) | 9 | `madsnorgaard.net/category/{slug}` (exists) | 301 pattern → `/category/{last-segment}` |
| `/one-picture-stories/` | page (posts listing) | 1 | no listing page yet (`/stories` = future stories CPT) | 301 → `madsnorgaard.net/` (revisit when /stories ships) |
| `/biography/` | page | 1 | `madsnorgaard.net/cv` (closest: CV/work history) | 301 → `/cv` |
| `/contact/` | page | 1 | contact lives in the site footer everywhere | 301 → `/` |
| `/copyright-notice/` | page | 1 | none | 301 → `/` |
| `/sample-page/`, `/responsive-video-embeds/`, `/easy-galleries-with-half/`, `/custom-fonts-with-size-control/`, `/flexible-custom-colors/` | theme demo junk pages | 5 | none — should never have been public | 301 → `/` (and consider deleting the pages in wp-admin) |
| `/feed/`, `/comments/feed/`, per-post feeds | RSS | n | none | 301 → `/` via `do_feed*` hooks |
| attachment pages, date archives, author archives, search | WP auto-routes | n | none (author archives already blocked by photo-api-security) | 301 catch-all |

## Not affected (must keep working — no redirect)

- `/wp-json/**` — REST API (the Nuxt backend contract; see `scripts/api-baseline/`)
- `/wp-content/uploads/**` — images hotlinked by madsnorgaard.net (`nuxt.config.ts` image domains) — served by Apache, never reaches PHP
- wp-admin, the wps-hide-login slug, admin-ajax, cron
- `/sitemap_index.xml` etc. disappear with rank-math (Phase 3d); the Nuxt site has its own sitemap — intentional

## Implementation notes for headless-redirect.php

- `is_singular('project')` → `https://madsnorgaard.net/proj/{slug}`
- `is_singular('post')` → `https://madsnorgaard.net/post/{slug}`
- `is_category()` → `https://madsnorgaard.net/category/{slug}`
- explicit page map: `biography→/cv`; everything else (pages, archives, 404s, home) → `/`
- `photos`/`stories` CPTs are empty on prod (2026-08); when content lands, their
  single URLs would be `/archive/{slug}` and `/stories/{slug}` on the apex —
  add `is_singular('photo'|'story')` rules at that point.

## Google Search Console

After cutover, photo.madsnorgaard.net rankings will transfer via the 301s.
No action strictly needed, but consider a Change of Address in GSC if the
property is registered there.
