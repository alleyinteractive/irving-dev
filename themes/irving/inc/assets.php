<?php
/**
 * Manage static assets.
 *
 * @package Irving
 */

namespace Irving;

/**
 * Get the version for a given asset.
 *
 * @param string $asset_path Entry point and asset type separated by a '.'.
 * @return string The asset version.
 */
function ai_get_versioned_asset( $asset_path ) {
	static $asset_map;

	if ( ! isset( $asset_map ) ) {
		$asset_map_file = IRVING_PATH . '/client/build/assetMap.json';

		if ( file_exists( $asset_map_file ) && 0 === validate_file( $asset_map_file ) ) {
			ob_start();
			include $asset_map_file;
			$asset_map = json_decode( ob_get_clean(), true );
		} else {
			$asset_map = [];
		}
	}

	/*
	 * Appending a '.' ensures the explode() doesn't generate a notice while
	 * allowing the variable names to be more readable via list().
	 */
	list( $entrypoint, $type ) = explode( '.', "$asset_path." );

	return isset( $asset_map[ $entrypoint ][ $type ] ) ? $asset_map[ $entrypoint ][ $type ] : '';
}

/**
 * Enqueues scripts and styles for the frontend
 */
function enqueue_assets() {
	// Dev-specific scripts.
	if (
		false !== strpos( get_site_url(), '.dev' )
		&& get_query_var( 'irving-dev', false )
	) {
		wp_enqueue_script(
			'dev',
			'//localhost:8080/client/build/js/dev.bundle.js',
			array(),
			false,
			false
		);
	} else {
		wp_enqueue_script( 'irving-common-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'common.js' ), array( 'jquery' ), '1.0' );
		wp_enqueue_style( 'irving-common-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'common.css' ), array(), '1.0' );

		if ( is_home() ) {
			wp_enqueue_style( 'irving-home', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'home.css' ), array(), '1.0' );
			wp_enqueue_script( 'irving-home-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'home.js' ), array( 'irving-common-js' ), '1.0' );
		}

		if ( is_single() ) {
			wp_enqueue_script( 'irving-article-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'article.js' ), array( 'irving-common-js' ), '1.0' );
			wp_enqueue_style( 'irving-article-css', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'article.css' ), array(), '1.0' );
		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );

/**
 * Enqueues scripts and styles for admin screens
 */
function enqueue_admin() {
	wp_enqueue_script( 'irving-admin-js', get_template_directory_uri() . '/client/build/js/admin.bundle.js', array(), '1.0', true );
	wp_enqueue_style( 'irving-admin-css', get_template_directory_uri() . '/client/build/css/admin.css', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin' );

/**
 * Removes scripts that could potentially cause style conflicts
 */
function dequeue_scripts() {
	wp_dequeue_style( 'jetpack-slideshow' );
	wp_dequeue_style( 'jetpack-carousel' );
}
add_action( 'wp_print_scripts', __NAMESPACE__ . '\dequeue_scripts' );
