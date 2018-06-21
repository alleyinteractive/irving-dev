<?php
/**
 * Irving functions and definitions.
 *
 * @package Irving
 */

namespace Irving;

define( 'IRVING_PATH', dirname( __FILE__ ) );
define( 'IRVING_URL', get_template_directory_uri() );

// WordPress utilities.
require_once IRVING_PATH . '/inc/class-wp-utils.php';

// Activate and customize plugins.
require_once IRVING_PATH . '/inc/plugins.php';

// Admin customizations.
if ( is_admin() ) {
	require_once IRVING_PATH . '/inc/admin.php';
}

// wp-cli command.
if ( WP_Utils::wp_cli() ) {
	require_once IRVING_PATH . '/inc/cli.php';
}

// WP Irving.
if ( defined( 'WP_IRVING_VERSION' ) && WP_IRVING_VERSION ) {
	// Irving components.
	require_once IRVING_PATH . '/inc/irving/components/class-content-card.php';
	require_once IRVING_PATH . '/inc/irving/components/class-content-grid.php';
	require_once IRVING_PATH . '/inc/irving/components/class-jumbotron.php';

	// Irving routing.
	require_once IRVING_PATH . '/inc/irving/routing/defaults.php';
	require_once IRVING_PATH . '/inc/irving/routing/routing.php';
	require_once IRVING_PATH . '/inc/irving/routing/single.php';
}


// Ad integrations.
require_once IRVING_PATH . '/inc/ads.php';

// Ajax.
require_once IRVING_PATH . '/inc/ajax.php';

// Include classes used to integrate with external APIs.
require_once IRVING_PATH . '/inc/api.php';

// Manage static assets (js and css).
require_once IRVING_PATH . '/inc/assets.php';

// Authors.
require_once IRVING_PATH . '/inc/authors.php';

// Cache.
require_once IRVING_PATH . '/inc/cache.php';

// Include comments.
require_once IRVING_PATH . '/inc/comments.php';

// Customizer additions.
require_once IRVING_PATH . '/inc/customizer.php';

// This site's RSS, Atom, JSON, etc. feeds.
require_once IRVING_PATH . '/inc/feeds.php';

// Media includes.
require_once IRVING_PATH . '/inc/media.php';

// Navigation & Menus.
require_once IRVING_PATH . '/inc/nav.php';

// Query modifications and manipulations.
require_once IRVING_PATH . '/inc/query.php';

// Rewrites.
require_once IRVING_PATH . '/inc/rewrites.php';

// Search.
require_once IRVING_PATH . '/inc/search.php';

// Shortcodes.
require_once IRVING_PATH . '/inc/shortcodes.php';

// Include sidebars and widgets.
require_once IRVING_PATH . '/inc/sidebars.php';

// Helpers.
require_once IRVING_PATH . '/inc/template-tags.php';

// Theme setup.
require_once IRVING_PATH . '/inc/theme.php';

// Users.
require_once IRVING_PATH . '/inc/users.php';

// Zoninator zones/customizations.
require_once IRVING_PATH . '/inc/zones.php';

// Loader for partials.
require_once IRVING_PATH . '/inc/partials/partials.php';

// Template loader.
require_once IRVING_PATH . '/inc/class-wrapping.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

/* End Data Structures */
