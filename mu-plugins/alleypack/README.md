# Alleypack
Alleypack is a WordPress plugin that uses modules to load self-contained functionality that is often reusued across projects.

## Load a module
```php
\Alleypack\load_module( $slug, $version );
```

## Modules

### [Classnames](https://github.com/alleyinteractive/alleypack/blob/master/modules/classnames/readme.md)
Functions for managing concatenation and output of classes in your PHP templates.

### [CLI Helpers](https://github.com/alleyinteractive/alleypack/blob/master/modules/cli-helpers/readme.md)
CLI helper functions.

### [FM Modules](https://github.com/alleyinteractive/alleypack/blob/master/modules/fm-modules/readme.md)
Set up a modules-based page builder for editors.

### [One Timer](https://github.com/alleyinteractive/alleypack/blob/master/modules/one-timer/readme.md)
Ensure something runs once and only once.

### [Partials](https://github.com/alleyinteractive/alleypack/blob/master/modules/partials/readme.md)
An advanced template loader to DRY up template code.

### [Path Dispatch](https://github.com/alleyinteractive/alleypack/blob/master/modules/path-dispatch/readme.md)
Simply and easily add a URL which fires an action, triggers a callback, and/or loads a template.

### [Singleton](https://github.com/alleyinteractive/alleypack/blob/master/modules/singleton/readme.md)
Make a class into a singleton.

### [Sitemap](https://github.com/alleyinteractive/alleypack/blob/master/modules/sitemap/readme.md)
Add arbitrary URLs to your Jetpack-generated sitemap.

### [Sync Script](https://github.com/alleyinteractive/alleypack/blob/master/modules/sync-script/readme.md)
Sync data from an external source to a WordPress object.

### [Term Post Link](https://github.com/alleyinteractive/alleypack/blob/master/modules/term-post-link/readme.md)
Easily use terms as if they were posts.

### [Unique WP Query](https://github.com/alleyinteractive/alleypack/blob/master/modules/unique-wp-query/1.0/readme.md)
Replacement for WP_Query that only returns post objects that haven't already been returned.

### [Block Converter](https://github.com/alleyinteractive/alleypack/blob/master/modules/block-converter/1.0/readme.md)
Helper class to convert HTML into Gutenberg blocks

## Running Unit Tests Locally

From inside your Vagrant machine, run `composer install` from the root of the plugin to install packages.

You can then use `composer run-script phpunit` to run PHPUnit.

Use the `vrun` command bundled with Broadway to easily invoke the command from your host machine; for example, `vrun composer install` or `vrun composer run-script phpunit`.


## Setting up Alleypack as a submodule
Since Alleypack is a private submodule, there's a few tricks to get it working correctly.

### Building on Travis
1. Generate a new key pair, `ssh-keygen -t rsa -b 4096 -f 'travis_github_deploy_key' -N '' -m PEM`
2. Add the public key to the Alley-CI user on Github using "Project [CODE] Deploybot Key"
3. Add the private key to the Deploybot settings for your repo from the Alley user, https://travis-ci.com/organizations/alleyinteractive/repositories

### Deploybot
1. From the Deploybot project settings, add the public key to Alley-CI user.
*NOTE:* if you have already setup this project to deploy, the key may already be in use by the project repo. You'll need to remove it from the repo deploy keys at https://github.com/alleyinteractive/YOUR_REPO/settings/keys
2. Ensure the
