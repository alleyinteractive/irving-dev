<?php
/**
 * Entry point for the plugin.
 *
 * Plugin Name: WP Alley React
 * Description: Enables WP functionality for the Alley React Starter
 * Author: Alley Interactive, James Burke
 * Version: 0.3
 * Author URI: http://alleyinteractive.com
 *
 * @package WP_Alley_React
 */

define( 'ALLEY_REACT_PATH', dirname( __FILE__ ) );

// Load singleton
require_once( ALLEY_REACT_PATH . '/inc/trait-singleton.php' );

// Load modules
require_once( ALLEY_REACT_PATH . '/inc/class-cleanup.php' );
require_once( ALLEY_REACT_PATH . '/inc/class-endpoints.php' );
require_once( ALLEY_REACT_PATH . '/inc/class-landing-pages.php' );
require_once( ALLEY_REACT_PATH . '/inc/class-redirects.php' );

add_action( 'after_setup_theme', function() {
	if ( defined( 'FM_VERSION' ) ) {
		require_once( ALLEY_REACT_PATH . '/inc/class-fieldmanager-redirect.php' );
	}
} );
