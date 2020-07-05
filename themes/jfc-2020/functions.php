<?php
/**
 * JFC 2020 functions and definitions.
 *
 * @package JFC_2020
 */

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.IncludingFile, WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

namespace JFC_2020;

define( 'JFC_2020_PATH', dirname( __FILE__ ) );
define( 'JFC_2020_URL', get_template_directory_uri() );

// WordPress utilities.
require_once JFC_2020_PATH . '/inc/class-wp-utils.php';

// Activate and customize plugins.
require_once JFC_2020_PATH . '/inc/plugins.php';

// Admin customizations.
if ( is_admin() ) {
	require_once JFC_2020_PATH . '/inc/admin.php';
}

// wp-cli command.
if ( WP_Utils::wp_cli() ) {
	require_once JFC_2020_PATH . '/inc/cli.php';
}

// Manage static assets (js and css).
require_once JFC_2020_PATH . '/inc/assets.php';

// Media includes.
require_once JFC_2020_PATH . '/inc/media.php';

// Navigation & Menus.
require_once JFC_2020_PATH . '/inc/nav.php';

// Query modifications and manipulations.
require_once JFC_2020_PATH . '/inc/query.php';

// Rewrites.
require_once JFC_2020_PATH . '/inc/rewrites.php';

// Search.
require_once JFC_2020_PATH . '/inc/search.php';

// Theme setup.
require_once JFC_2020_PATH . '/inc/theme.php';

// Users.
require_once JFC_2020_PATH . '/inc/users.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

/* End Data Structures */
