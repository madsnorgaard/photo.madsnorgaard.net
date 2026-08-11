#!/usr/bin/env bash
# Rotate the Traefik basic-auth passphrase guarding /member + /wp-admin on
# photo.madsnorgaard.net. Run from your workstation (needs `ssh production`).
#
#   scripts/rotate-admin-auth.sh             # prompts (hidden input) for a new passphrase
#   scripts/rotate-admin-auth.sh --generate  # generates a strong one and prints it ONCE
#
# The passphrase is piped via stdin end-to-end: it never appears in shell
# history, command lines, or process lists. The bcrypt hash is $-doubled
# before landing in .env (docker compose interpolates ${...} in label values —
# single $ signs get eaten; see 2026-08-11 lockout).
set -euo pipefail

SITE_DIR='~/docker/photo.madsnorgaard.net'
SSH_HOST="${ROTATE_SSH_HOST:-production}"
URL='https://photo.madsnorgaard.net/member/'

if [ "${1:-}" = "--generate" ]; then
  PASS=$(openssl rand -base64 24 | tr -d '/+=' | cut -c1-28)
  echo "New passphrase (shown ONCE — store it in your password manager now):"
  echo
  echo "    $PASS"
  echo
else
  read -rsp "New passphrase (min 12 chars): " PASS; echo
  read -rsp "Repeat: " PASS2; echo
  [ "$PASS" = "$PASS2" ] || { echo "Passphrases do not match." >&2; exit 1; }
fi
[ "${#PASS}" -ge 12 ] || { echo "Too short — use at least 12 characters." >&2; exit 1; }

echo "Hashing on the server and updating .env..."
printf '%s' "$PASS" | ssh "$SSH_HOST" "cd $SITE_DIR && \
  H=\$(docker run --rm -i httpd:2.4-alpine htpasswd -niB admin | tr -d '\r\n' | sed 's/[\$]/&&/g') && \
  [ -n \"\$H\" ] && \
  sed -i '/^WP_ADMIN_BASIC_AUTH=/d' .env && \
  printf 'WP_ADMIN_BASIC_AUTH=%s\n' \"\$H\" >> .env && \
  docker compose up -d >/dev/null 2>&1 && \
  echo 'server: .env updated, container recreated'"

echo "Waiting for Traefik to pick up the new credentials..."
sleep 5
CODE=$(curl -sk -o /dev/null -w '%{http_code}' -u "admin:$PASS" "$URL")
NOAUTH=$(curl -sk -o /dev/null -w '%{http_code}' "$URL")
if [ "$CODE" = "200" ] && [ "$NOAUTH" = "401" ]; then
  echo "OK: new passphrase works (200 with creds, 401 without)."
else
  echo "PROBLEM: with-creds=$CODE (want 200), without=$NOAUTH (want 401)." >&2
  echo "The old passphrase may still be active, or the container is still restarting — retry the curl in a minute." >&2
  exit 1
fi
