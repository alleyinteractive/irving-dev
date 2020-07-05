<?php
/**
 * Manage static assets.
 *
 * @package JFC_2020
 */

namespace JFC_2020;

/**
 * The main theme asset map.
 *
 * @var array
 */
define( 'AI_ASSET_MAP', ai_read_asset_map( JFC_2020_PATH . '/client/build/assetMap.json' ) );

/**
 * The main theme asset build mode.
 *
 * @var string
 */
define( 'AI_ASSET_MODE', AI_ASSET_MAP['mode'] ?? 'production' );

/**
 * Decode the asset map at the given file path.
 *
 * @param string $path File path.
 * @return array
 */
function ai_read_asset_map( string $path ) {
	if ( file_exists( $path ) && 0 === validate_file( $path ) ) {
		ob_start();
		include $path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.IncludingFile, WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		return json_decode( ob_get_clean(), true );
	}

	return [];
}

/**
 * Get a property for a given asset.
 *
 * @param string $asset Entry point and asset type separated by a '.'.
 * @param string $prop The property to get from the entry object.
 * @return string|null The asset property based on entry and type.
 */
function ai_get_asset_property( $asset, $prop ) {
	/*
	 * Appending a '.' ensures the explode() doesn't generate a notice while
	 * allowing the variable names to be more readable via list().
	 */
	list( $entrypoint, $type ) = explode( '.', "$asset." );

	$asset_property = AI_ASSET_MAP[ $entrypoint ][ $type ][ $prop ] ?? null;

	return $asset_property ? $asset_property : null;
}

/**
 * Get the path for a given asset.
 *
 * @param string $asset Entry point and asset type separated by a '.'.
 * @return string The asset version.
 */
function ai_get_asset_path( $asset ) {
	$asset_property = ai_get_asset_property( $asset, 'path' );

	if ( $asset_property ) {
		// Create public path.
		$base_path = AI_ASSET_MODE === 'development' ?
			'https://8080-httpsproxy.alley.test/client/build/' :
			JFC_2020_URL . '/client/build/';

		return $base_path . $asset_property;
	}

	return null;
}

/**
 * Get the contentHash for a given asset.
 *
 * @param string $asset Entry point and asset type separated by a '.'.
 * @return string The asset's hash.
 */
function ai_get_asset_hash( $asset ) {
	$asset_property = ai_get_asset_property( $asset, 'hash' );

	return $asset_property ?? AI_ASSET_MAP['hash'] ?? '1.0.0';
}

/**
 * Enqueues scripts and styles for the frontend
 */
function enqueue_assets() {
	wp_enqueue_style(
		'jfc-2020-global-css',
		ai_get_asset_path( 'global.css' ),
		[],
		ai_get_asset_hash( 'global.css' )
	);

	wp_enqueue_script(
		'jfc-2020-global-js',
		ai_get_asset_path( 'global.js' ),
		[],
		ai_get_asset_hash( 'global.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );

/**
 * Enqueues scripts and styles for admin screens
 */
function enqueue_admin() {
	wp_enqueue_script(
		'jfc-2020-admin-js',
		ai_get_asset_path( 'admin.js' ),
		[],
		ai_get_asset_hash( 'admin.js' ),
		true
	);

	wp_enqueue_style(
		'jfc-2020-admin-css',
		ai_get_asset_path( 'admin.css' ),
		[],
		ai_get_asset_hash( 'admin.css' ),
	);
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
