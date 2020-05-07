<?php
/*
Plugin Name: MU Loader
Description: Wrapper plugin to manually require non-mu compatible plugins
Author: Alley Interactive
Version: 1.0
*/

$plugins = array(
	'/safe-redirect-manager/safe-redirect-manager.php',
	'/wp-irving/wp-irving.php',
	'/wp-components/wp-components.php',
);

// Begin the process of loading the MU Plugins
if ( is_array( $plugins ) ) {
	foreach ( $plugins as $plugin_name ) {
		if ( file_exists( WPMU_PLUGIN_DIR . $plugin_name ) ) {
			// Require if the file is found
			require_once WPMU_PLUGIN_DIR . $plugin_name;
		} else {
			// Or display an admin notice
			add_action( 'admin_notices', function() use ( $plugin_name ) {
				echo '<div class="notice notice-error"><p>';
				printf( __( 'Could not load the MU-Plugin located in /mu-plugins%1$s', 'load-mu-plugins' ), $plugin_name );
				echo '</p></div>';
			} );
		}
	}
}
