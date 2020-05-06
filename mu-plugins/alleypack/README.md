# Alleypack
Alleypack is a WordPress plugin that uses modules to load self-contained functionality that is often reusued across projects.

## Load a module
```php
\Alleypack\load_module( $slug, $version );
```

## Modules

### [Alerts](https://github.com/alleyinteractive/alleypack/blob/master/modules/alerts/1.0/readme.md)
Rich text news alerts which can be scheduled/unscheduled.

### [Attachments](https://github.com/alleyinteractive/alleypack/blob/master/modules/attachments/1.0/readme.md)
Helper functions for attachments.

### [Block Converter](https://github.com/alleyinteractive/alleypack/blob/master/modules/block-converter/1.0/readme.md)
Convert HTML into Gutenberg blocks.

### [Classnames](https://github.com/alleyinteractive/alleypack/blob/master/modules/classnames/readme.md)
Functions for managing concatenation and output of classes in your PHP templates.

### [CLI Helpers](https://github.com/alleyinteractive/alleypack/blob/master/modules/cli-helpers/readme.md)
CLI helper functions.

### [Disable Attachment Routing](https://github.com/alleyinteractive/alleypack/blob/master/modules/disable-attachment-routing/1.0/readme.md)
Remove routing for attachment pages.

### [FM Helpers](https://github.com/alleyinteractive/alleypack/blob/master/modules/fm-helpers/readme.md)
FM helper functions.

### [FM Modules](https://github.com/alleyinteractive/alleypack/blob/master/modules/fm-modules/readme.md)
Set up a modules-based page builder for editors.

### Landing Pages
A class to help easily create and manage landing pages.

### [Media Fields](https://github.com/alleyinteractive/alleypack/blob/master/modules/media-fields/readme.md)
Easily add media fields.

### [One Timer](https://github.com/alleyinteractive/alleypack/blob/master/modules/one-timer/readme.md)
Ensure something runs once and only once.

### [Page Templates](https://github.com/alleyinteractive/alleypack/blob/master/modules/page-templates/1.0/readme.md)
Allow page templates to be defined with an FM group to easily store the meta needed for that page template.

### [Partials](https://github.com/alleyinteractive/alleypack/blob/master/modules/partials/readme.md)
An advanced template loader to DRY up template code.

### [Path Dispatch](https://github.com/alleyinteractive/alleypack/blob/master/modules/path-dispatch/readme.md)
Simply and easily add a URL which fires an action, triggers a callback, and/or loads a template.

### Podcasts
Easily manage podcasts.

### [Programmatic Terms](https://github.com/alleyinteractive/alleypack/blob/master/modules/programmatic-terms/1.0/readme.md)
Manage terms programmatically.

### [Schedule Unpublish](https://github.com/alleyinteractive/alleypack/blob/master/modules/schedule-unpublish/1.0/readme.md)
Schedule posts to unpublish in the future.

### [Singleton](https://github.com/alleyinteractive/alleypack/blob/master/modules/singleton/readme.md)
Make a class into a singleton.

### [Sitemap](https://github.com/alleyinteractive/alleypack/blob/master/modules/sitemap/1.0/readme.md)
Add arbitrary URLs to your Jetpack-generated sitemap.

### [Stylesheets](modules/stylesheets)
Functions for managing hashed classnames.

### [Sync Script](https://github.com/alleyinteractive/alleypack/blob/master/modules/sync-script/readme.md)
Sync data from an external source to a WordPress object.

### [Term Post Link](https://github.com/alleyinteractive/alleypack/blob/master/modules/term-post-link/readme.md)
Easily use terms as if they were posts.

### [Unique WP Query](https://github.com/alleyinteractive/alleypack/blob/master/modules/unique-wp-query/1.0/readme.md)
Replacement for WP_Query that only returns post objects that haven't already been returned.

## Blocks

### [Callout CTA](https://github.com/alleyinteractive/alleypack/blob/master/blocks/calloutCta/README.md)
Callout with Call-to-Action Options

### [Editable Post Grid](https://github.com/alleyinteractive/alleypack/blob/master/blocks/editablePostGrid/README.md)
Editable Post Grid with InnerBlock children.

### [Editable Post Block](https://github.com/alleyinteractive/alleypack/blob/master/blocks/editablePost/README.md)
Single Editable Post Block, used as InnerBlocks.

## Running Unit Tests Locally

From the Alleypack directory, simply run `phpunit` to have Broadway connect to the VM and run the test suite.

## Setting up Alleypack as a submodule
Since Alleypack is a private submodule, there's a few tricks to get it working correctly.

### Building on Travis
1. Generate a new key pair, `ssh-keygen -t rsa -b 4096 -f 'travis_github_deploy_key' -N '' -m PEM`
2. Add the public key to the Alley-CI user on Github using "Project [CODE] Deploybot Key"
3. Add the private key to the Deploybot settings for your repo from the Alley user, https://travis-ci.com/organizations/alleyinteractive/repositories

### Deploybot
1. From the Deploybot project settings, add the public key to Alley-CI user.
*NOTE:* if you have already setup this project to deploy, the key may already be in use by the project repo. You'll need to remove it from the repo deploy keys at https://github.com/alleyinteractive/YOUR_REPO/settings/keys
