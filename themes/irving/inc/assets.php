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
 * @param string $name Entry point name.
 * @param string $type Asset type, usually one of 'js' or 'css'.
 * @return string Path to asset relative to build directory
 */
function ai_get_versioned_asset_path( $name, $type ) {
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

	$path = $asset_map[ $name ][ $type ] ?? '';

	if ( ! empty( $path ) ) {
		return get_template_directory_uri() . '/client/build/' . $path;
	}

	return $path;
}

/**
 * Get the version for a given asset.
 *
 * @param string $path Default path for component assets.
 * @param string $name Entry point name.
 * @param string $type Asset type, usually one of 'js' or 'css'.
 * @return string Path to asset relative to build directory
 */
function component_asset_path( $path, $name, $type ) {
	return ai_get_versioned_asset_path( $name, $type );
}
add_filter( 'wp_components_php_resolve_asset', __NAMESPACE__ . '\component_asset_path', 10, 3 );

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
		wp_enqueue_script( 'irving-common-js', ai_get_versioned_asset_path( 'common', 'js' ), array( 'jquery' ), '1.0' );
		wp_enqueue_style( 'irving-common-css', ai_get_versioned_asset_path( 'common', 'css' ), array(), '1.0' );

		if ( is_home() ) {
			wp_enqueue_style( 'irving-home', ai_get_versioned_asset_path( 'home', 'css' ), array(), '1.0' );
			wp_enqueue_script( 'irving-home-js', ai_get_versioned_asset_path( 'home', 'js' ), array( 'irving-common-js' ), '1.0' );
		}

		if ( is_single() ) {
			wp_enqueue_script( 'irving-article-js', ai_get_versioned_asset_path( 'article', 'js' ), array( 'irving-common-js' ), '1.0' );
			wp_enqueue_style( 'irving-article-css', ai_get_versioned_asset_path( 'article', 'css' ), array(), '1.0' );
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
