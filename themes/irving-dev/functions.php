<?php
/**
 * Irving Dev functions and definitions.
 *
 * @package Irving_Dev
 */

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.IncludingFile, WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

namespace Irving_Dev;

define( 'IRVING_DEV_PATH', dirname( __FILE__ ) );
define( 'IRVING_DEV_URL', get_template_directory_uri() );

echo "IRVING_DEV_PATH is " . IRVING_DEV_PATH;

// WordPress utilities.
require_once IRVING_DEV_PATH . '/inc/class-wp-utils.php';

// Activate and customize plugins.
require_once IRVING_DEV_PATH . '/inc/plugins.php';

// Admin customizations.
if ( is_admin() ) {
	require_once IRVING_DEV_PATH . '/inc/admin.php';
}

// wp-cli command.
if ( WP_Utils::wp_cli() ) {
	require_once IRVING_DEV_PATH . '/inc/cli.php';
}

// Ad integrations.
require_once IRVING_DEV_PATH . '/inc/ads.php';

// Ajax.
require_once IRVING_DEV_PATH . '/inc/ajax.php';

// Include classes used to integrate with external APIs.
require_once IRVING_DEV_PATH . '/inc/api.php';

// Manage static assets (js and css).
require_once IRVING_DEV_PATH . '/inc/assets.php';

// Authors.
require_once IRVING_DEV_PATH . '/inc/authors.php';

// Cache.
require_once IRVING_DEV_PATH . '/inc/cache.php';

// Include comments.
require_once IRVING_DEV_PATH . '/inc/comments.php';

// Customizer additions.
require_once IRVING_DEV_PATH . '/inc/customizer.php';

// This site's RSS, Atom, JSON, etc. feeds.
require_once IRVING_DEV_PATH . '/inc/feeds.php';

// Media includes.
require_once IRVING_DEV_PATH . '/inc/media.php';

// Navigation & Menus.
require_once IRVING_DEV_PATH . '/inc/nav.php';

// Query modifications and manipulations.
require_once IRVING_DEV_PATH . '/inc/query.php';

// Rewrites.
require_once IRVING_DEV_PATH . '/inc/rewrites.php';

// Routing.
require_once IRVING_DEV_PATH . '/inc/routing.php';

// Search.
require_once IRVING_DEV_PATH . '/inc/search.php';

// Shortcodes.
require_once IRVING_DEV_PATH . '/inc/shortcodes.php';

// Include sidebars and widgets.
require_once IRVING_DEV_PATH . '/inc/sidebars.php';

// Helpers.
require_once IRVING_DEV_PATH . '/inc/template-tags.php';

// Theme setup.
require_once IRVING_DEV_PATH . '/inc/theme.php';

// Users.
require_once IRVING_DEV_PATH . '/inc/users.php';

// Zoninator zones/customizations.
require_once IRVING_DEV_PATH . '/inc/zones.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

/* End Data Structures */
