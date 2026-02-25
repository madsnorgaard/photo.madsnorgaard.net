# photo.madsnorgaard.net

WordPress photography site managed via Docker on VPS2. Uses the mauer-stills theme with custom gallery and portfolio plugins.

**Status:** Inactive (stopped on VPS2). Themes and plugins tracked in git. DB import needed to bring fully online.

## Local Development

```bash
cp .env.example .env
# edit .env with local credentials
ddev start
# WP install wizard will appear — site runs without a DB import
ddev wp plugin list
```

## Production Deployment

Push to `main` branch to trigger a deploy via GitHub Actions.

The workflow dispatches a `deploy` event to `madsnorgaard/contabo-infrastructure`,
which runs the actual deployment on VPS2.

## WP-CLI (production)

```bash
cd /home/webadmin/docker/photo.madsnorgaard.net
docker compose run --rm cli wp plugin update --all
docker compose run --rm cli wp cache flush
```

## Requirements

- `DEPLOY_TOKEN` secret must be set in this repo (GitHub PAT with `repo` scope on contabo-infrastructure)
- `.env` file on VPS2 with real credentials (never committed)
