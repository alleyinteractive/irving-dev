# Irving Dev

## Project Overview

Irving Dev is a headless [Irving](https://github.com/alleyinteractive/irving) project. The purpose of this project is to serve as a sandbox WordPress environment for developing Irving core or Irving-related projects.

## Installing this project

This repo is structured to be a drop in replacement of the `wp-content` directory of a WordPress install. You can install it in any working WordPress environment using one of the following methods.

### Alley's "Broadway" environment

Run `apm install irving` to create a new site at `~/broadway/www/irving-dev`, which is accessible from http://irving-dev.alley.test/.

You may need to run `vagrant provision` if you haven't in a while.

### Generic WordPress install

From the root directory of a WordPress install delete or move your `wp-content` directory and replace it by cloning this repo to your WordPress folder and installing submodules.

Example:
```
mv wp-content old-wp-content

git clone git@github.com:alleyinteractive/irving-dev.git wp-content

cd wp-content

git submodule update --init --recursive
```
Once installed
## Setting up WordPress for Irving

Once you have this project running in a WordPress site, ensure the following are properly configured in your WordPress site:

1. Set `WP_HOME` to the location of your Irving front end, e.g. http://localhost:3001, in your `wp-config.php` file.
2. Make sure permalinks are not set to "plain" under `Settings > Permalinks` in the admin, in order for the REST API to work as expected.
3. Activate an Irving compatible theme. This project includes two out of the box: `irving-example-theme` and `irving-twentytwentyone`.

## Setting up and running an Irving application

1. Navigate to the Irving app folder, i.e. `cd themes/irving-example-theme/client/irving`
1. Install Node modules by running `npm ci`.
1. Copy the `.env.example` file to `.env` and update the `API_ROOT_URL` value to point to the URL for your local WordPress install.
1. Use `npm run dev` to start your local server. The application will automatically open to the URL defined in your .env file, the default is http://localhost:3001.
