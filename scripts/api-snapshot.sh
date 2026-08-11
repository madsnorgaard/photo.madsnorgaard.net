#!/usr/bin/env bash
# REST API contract snapshot for photo.madsnorgaard.net.
#
# Captures the endpoints the Nuxt frontend (madsnorgaard.net) depends on,
# normalized so diffs show *contract* changes (fields appearing/disappearing,
# rendering changes) rather than content noise (dates, counters).
#
# Usage:
#   scripts/api-snapshot.sh https://photo.madsnorgaard.net.ddev.site scripts/api-current
#   diff -ru scripts/api-baseline scripts/api-current
#
# Refresh the baseline only when a contract change is intentional:
#   scripts/api-snapshot.sh <base-url> scripts/api-baseline
set -uo pipefail

BASE="${1:?usage: api-snapshot.sh <base-url> <out-dir>}"
OUT="${2:?usage: api-snapshot.sh <base-url> <out-dir>}"
mkdir -p "$OUT"
FAIL=0

# Volatile keys stripped from every object; header *values* (X-WP-Total) are
# reduced to header names since totals grow with content.
NORMALIZE='walk(if type == "object" then del(.date, .date_gmt, .modified, .modified_gmt, .targetHints) else . end)'

snap() { # snap <name> <url-path> [jq-assertion]
  local name="$1" url="$2" assert="${3:-}"
  local hdr body
  hdr=$(mktemp) body=$(mktemp)
  if ! curl -sk -D "$hdr" -o "$body" --max-time 30 "$BASE$url"; then
    echo "FAIL fetch: $name ($url)"; FAIL=1; rm -f "$hdr" "$body"; return
  fi
  grep -oiE '^x-wp-total(pages)?' "$hdr" | tr '[:upper:]' '[:lower:]' | sort -u > "$OUT/$name.headers"
  if ! jq -S "$NORMALIZE" "$body" > "$OUT/$name.json" 2>/dev/null; then
    echo "FAIL not-json: $name ($url): $(head -c 200 "$body")"; FAIL=1
  elif [ -n "$assert" ] && ! jq -e "$assert" "$body" > /dev/null 2>&1; then
    echo "FAIL assert: $name ($url) — $assert"; FAIL=1
  fi
  rm -f "$hdr" "$body"
}

first_id() { # lowest ID of a collection, stable over time
  curl -sk --max-time 30 "$BASE/wp-json/wp/v2/$1?per_page=1&orderby=id&order=asc&_fields=id" | jq -r '.[0].id // empty'
}

PHOTO_ID=$(first_id photos)
STORY_ID=$(first_id stories)
PROJECT_ID=$(first_id project)
EVENT_PHOTO_ID=$(first_id event-photos)

# ── Photos: the ACF-decoupling contract ─────────────────────────────
# Note: prod's photos/stories CPTs are empty as of 2026-08 (content migration
# in progress) — the single-item snapshots below self-skip until content exists.
snap photos-list "/wp-json/wp/v2/photos?per_page=5&_fields=id,slug,meta"
[ -n "$PHOTO_ID" ] && snap photo-single "/wp-json/wp/v2/photos/$PHOTO_ID?_embed" \
  '.meta | has("archive_number") and has("location") and has("date_taken") and has("camera")'
snap photos-fields "/wp-json/wp/v2/photos?per_page=2&_fields=id,slug,meta"

# ── Stories: blocks_data / resolved_photos contract ─────────────────
snap stories-list "/wp-json/wp/v2/stories?per_page=3&_fields=id,slug"
[ -n "$STORY_ID" ] && snap story-single "/wp-json/wp/v2/stories/$STORY_ID?_resolve_photos=1" \
  'has("blocks_data") and has("resolved_photos")'

# ── Projects: content.rendered is the mauer-stills-gallery canary ───
snap projects-list "/wp-json/wp/v2/project?per_page=3&_embed&_fields=id,slug,title,_links,_embedded"
[ -n "$PROJECT_ID" ] && snap project-single "/wp-json/wp/v2/project/$PROJECT_ID?_embed" \
  '.content.rendered | length > 0'

# ── Core content + taxonomies ───────────────────────────────────────
snap posts-sticky "/wp-json/wp/v2/posts?per_page=3&sticky=true&_fields=id,slug"
snap media-list "/wp-json/wp/v2/media?per_page=3&orderby=id&order=asc&_fields=id,source_url,alt_text,media_details.sizes"
for tax in categories tags series subjects; do
  snap "tax-$tax" "/wp-json/wp/v2/$tax?per_page=5&orderby=id&order=asc"
done

# ── Event archive ───────────────────────────────────────────────────
snap event-photos "/wp-json/wp/v2/event-photos?per_page=3&orderby=id&order=asc&_fields=id,slug,meta" \
  '.[0].meta | has("like_count") and has("there_count") and has("capture_date")'
[ -n "$EVENT_PHOTO_ID" ] && snap event-photo-single "/wp-json/wp/v2/event-photos/$EVENT_PHOTO_ID?_embed"
snap event-sets "/wp-json/wp/v2/event-sets?per_page=5&orderby=id&order=asc"
snap event-notes "/wp-json/wp/v2/event-notes?per_page=3&_fields=id,meta"
# Prod predates the /top deploy (returns rest_no_route until Phase 1 ships);
# tighten this to the array-only assert once deployed.
snap event-top "/wp-json/event-archive/v1/top?count=3" '(.ids | type == "array") or (.code == "rest_no_route")'

if [ "$FAIL" -ne 0 ]; then
  echo "SNAPSHOT FAILED — contract assertions broken (see FAIL lines above)"
  exit 1
fi
echo "Snapshot OK → $OUT"
