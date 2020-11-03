<?php
/**
 * Plugin Name: Alley MU Loader
 * Description: Wrapper plugin to manually require non-mu compatible plugins
 * Author: Alley Interactive
 * Version: 1.0
 * Text Domain: alley-mu-loader
 *
 * @package WordPress
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
namespace Alley_MU_Loader;

// Run the plugin loader.
load_plugins(
	[
		'/jwt-auth/jwt-auth.php',
		'/wp-irving/wp-irving.php',
	]
);

/**
 * Load must use plugins.
 *
 * @param array $plugins A list of plugins to load.
 */
function load_plugins( array $plugins ) {
	// Begin the process of loading the MU Plugins.
	if ( is_array( $plugins ) ) {
		foreach ( $plugins as $plugin_path ) {
			if ( file_exists( WPMU_PLUGIN_DIR . $plugin_path ) ) {
				// Require if the file is found.
				require_once WPMU_PLUGIN_DIR . $plugin_path;
			} else {
				// Or display an admin notice.
				add_action(
					'admin_notices',
					function() use ( $plugin_path ) {
						printf(
							'<div class="notice notice-error"><p>%1$s <strong>%2$s</strong></p></div>',
							esc_html__( 'Could not load the MU-Plugin:', 'alley-mu-loader' ),
							esc_html( $plugin_path )
						);
					}
				);
			}
		}
	}
}
