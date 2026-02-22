# photo.madsnorgaard.net

WordPress photo site managed via Docker on VPS2.

## Deployment

Push to `main` branch to trigger a deploy via GitHub Actions.

The workflow dispatches a `deploy` event to `madsnorgaard/contabo-infrastructure`,
which runs the actual deployment on VPS2.

## Requirements

- `DEPLOY_TOKEN` secret must be set in this repo (GitHub PAT with `repo` scope on contabo-infrastructure)
