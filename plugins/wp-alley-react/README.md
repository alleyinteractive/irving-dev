# WP Alley React

WP Alley React is a plugin that works closely with the [React Starter App](https://github.com/alleyinteractive/react-starter-app). When enabled, this plugin will provide functionality that extend the WP Rest API, amongst other features.

## Using WP Alley React

While mostly standalone, this plugin does have features that rely on [Fieldmanager](https://github.com/alleyinteractive/wordpress-fieldmanager).

## Features

**Base URL:** /wp-json/alley-react/v1/

### Endpoints

**/options/** - This endpoint contains information about the site. Filterable with `alley_react_options`. This is great for adding any global information about your site, as it is one of the first things loaded by the client.

* info - General site information
* postTypes - Registered post types (filtered with `alley_react_post_types`)
* menus - All registered menus (filtered with `alley_react_menus`)
* taxonomies - All registered taxonomies (filtered with `alley_react_taxonomies`)
* landingPages - Landing pages created with the landing pages module.
* redirects - Redirects created with the redirects module.

**/landing-pages/** - This endpoint returns any given landing page. See the landing pages module for more information.

**/menu/** - Returns information about menus.

### Redirects Module
This module uses a custom FM field to easily create redirects that the client side app will listen for.

### Landing Pages Module
This module allows you to easily create FM submenu pages, modify their output, and deliver them to the client as stand-alone pages. The goal is to move this code out of the plugin, and become part of the WP Starter Theme.

The landing page endpoints don't actually need to be submenu pages, using filters, anything can be output as a landing page.

### Misc Functions

**Entity Decoding**

By default, WordPress will return results that are HTML entity encoded. We decode some fields so that they don't have to be done on the client side.

Title decoding can be disabled with,
`add_filter( 'alley_react_decode_title', '__return_false' );`

**Template Redirecting**

It's assumed that using this plugin means you will not be using the theme part of WordPress. As such, all non-admin, non-feed urls to the site are redirected to the admin. This can be disabled with,
`add_filter( 'alley_react_redirect_template_calls', '__return_false' );`

**RSS Feed Fixing**

Because the feed is routed through WordPress still, it doesn't use the client app's URL. We automatically fix that with this plugin.

It can be disabled with,
`add_filter( 'alley_react_rss_feed_fixes', '__return_false' );`
