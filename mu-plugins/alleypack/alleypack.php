<?php
/**
 * Plugin Name:     Alleypack
 * Description:     Sort of like Jetpack, but for Alley.
 * Author:          alleyinteractive, jameswburke
 * Author URI:      https://alley.co
 * Text Domain:     alleypack
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Alleypack
 */

namespace Alleypack;

/**
 * Current version of Alleypack.
 */
define( 'ALLEYPACK_VERSION', '1.0.0' );

/**
 * Filesystem path to Alleypack.
 */
define( 'ALLEYPACK_PATH', dirname( __FILE__ ) );

/**
 * Load a module.
 *
 * @param  string $slug    Module slug.
 * @param  string $version Module version.
 */
function load_module( string $slug, string $version = '1.0' ) {

	// Build path to module entry file.
	$module_entry = ALLEYPACK_PATH . "/modules/{$slug}/{$version}/{$slug}.php";

	// Load if it exists, or display an admin notice.
	if ( file_exists( $module_entry ) ) {
		require_once $module_entry; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	} else {
		add_action(
			'admin_notices',
			function() use ( $slug ) {
				echo '<div class="notice notice-error"><p>';
				printf(
					/* translators: module slug */
					esc_html__( 'Could not load module %1$s', 'alleypack' ),
					esc_html( $slug )
				);
				echo '</p></div>';
			}
		);
	}
}
