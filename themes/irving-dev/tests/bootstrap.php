<?php
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	$cwd = explode( 'wp-content', dirname( __FILE__ ) );
	define( 'WP_CONTENT_DIR', $cwd[0] . '/wp-content' );
}

// Load Core's test suite
$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( !$_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}
echo "here";

// phpcs:ignore This is only for unit testing.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Setup our environment (theme, plugins).
 */
function _manually_load_environment() {
	// Unhook actions that cause problems with running tests locally.
	remove_action( 'switch_theme', 'rri_wpcom_action_switch_theme' );

	// Set our theme.
	switch_theme( 'irving-dev' );
	echo "switched theme\n";
	$template = get_option( 'template' );
	$stylesheet = get_option( 'stylesheet' );
	echo $template . "\n";
	echo $stylesheet . "\n";
	foreach ( wp_get_active_and_valid_themes() as $theme ) {
		echo 'theme is ' . $theme . "\n";
		if ( file_exists( $theme . '/functions.php' ) ) {
			echo "it exists\n";
			// include $theme . '/functions.php';
		}
	}
	echo "template path: " . TEMPLATEPATH . "\n";

	/*
	 * Tests won't start until the uploads directory is scanned, so use the
	 * lightweight directory from the test install.
	 *
	 * @see https://core.trac.wordpress.org/changeset/29120.
	 */
	add_filter( 'pre_option_upload_path', function () {
		return ABSPATH . 'wp-content/uploads';
	} );

	// Set up plugins.
	update_option( 'active_plugins', array(
		'jetpack/jetpack.php'
	) );

	// phpcs:disable This is only for unit testing.
	if ( ! defined( 'JETPACK_DEV_DEBUG' ) ) {
		define( 'JETPACK_DEV_DEBUG', true );
	}
	// phpcs:enable

	/*
	 * If Edit Flow is enabled, the notifications module can hit errors because
	 * the unit test factory creates posts without authors.
	 */
	add_filter( 'ef_notification_status_change', '__return_false' );
}
tests_add_filter( 'muplugins_loaded', '_manually_load_environment' );

/**
 * Simple getter/setter for random values. Simplifies testing of caching.
 *
 * @param  string $new_value Some new value to set.
 * @return string The most recently set value.
 */
function _cache_test_data( $new_value = null ) {
	static $value;
	if ( isset( $new_value ) ) {
		$value = $new_value;
	}
	return $value;
}

// Include core's bootstrap
// phpcs:ignore This is only for unit testing.
require $_tests_dir . '/includes/bootstrap.php';
