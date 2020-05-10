# Irving Dev

## Project Overview

Irving Dev is a headless [Irving](https://github.com/alleyinteractive/irving) project. The purpose of this project is to facilitate development of the Irving framework outside of a client-project context.

### Local

- [Frontend Irving](https://irving.alley.test)
- [WP Admin](https://irving-dev.alley.test/wp-admin/) (alley:interactive)

### Pantheon/Heroku Staging Environment

_Deploys from `staging` branch._ Merge code to this branch at-will.

- [Irving](https://irving-staging.herokuapp.com/)
  - [Heroku dashboard](https://dashboard.heroku.com/apps/irving-staging)
- [WordPress admin](https://staging-irving.alleydev.com/wp-admin/) (Creds in 1Pass)
  - [Pantheon dashboard](https://dashboard.pantheon.io/sites/a09a2cd1-6f16-4dc0-b0ec-5befb350af6f#staging/deploys)
- [Deploybot admin](https://alleyinteractive.deploybot.com/121799--IRV-Irving-Dev)

### Pantheon/Heroku "Production" Environment

_Deploys from `production` branch._ All merges to this branch must have peer code review and pass continuous integration checks.

- [Irving](https://irving-production.herokuapp.com/)
  - [Heroku dashboard](https://dashboard.heroku.com/apps/irving-production)
- [WordPress admin](https://live-irving.alleydev.com/wp-admin/) (Creds in 1Pass)
  - [Pantheon dashboard](https://dashboard.pantheon.io/sites/a09a2cd1-6f16-4dc0-b0ec-5befb350af6f#live/deploys)
- [Deploybot admin](https://alleyinteractive.deploybot.com/121799--IRV-Irving-Dev)

## New environment setup

### Alley developers

1. `apm install irving`
1. `vagrant provision` (only if you haven't since May 2020).
1. `cd private/irving/ && npm ci`
1. To run the app, `npm run dev`

## Frontend Codebase

The frontend codebase lives in `private/irving` and uses the Irving Core npm package. For more information, see the [Irving wiki](https://github.com/alleyinteractive/irving/wiki).

### Starting Irving

Use `npm run dev` to start your local server. The application will automatically open `https://irving.alley.test`.

## Backend Codebase

The backend is WordPress, and this repo lives in `/wp-content/`.

### Branch Workflow

1. Branch off of `production`, prefixing feature with the ticket number (let's call the new branch `feature/IRV-123/feature-name-description`).
1. Make all commits for the new feature into `feature/IRV-123/feature-name-description`.
1. Make a pull request for `feature/IRV-123/feature-name-description` into `production` and code review by Alley members. Merge the PR once approved.
