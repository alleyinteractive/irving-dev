<?php
/**
 * Singleton module.
 *
 * @package Alleypack
 * @version 1.1.0
 * @see readme.md
 */

namespace Alleypack\Sync_Script;

// Load Alleypack dependencies.
\Alleypack\load_module( 'attachments', '1.0' );
\Alleypack\load_module( 'singleton', '1.0' );

// Load traits.
require_once __DIR__ . '/feed-traits/trait-endpoint.php';
require_once __DIR__ . '/feed-traits/trait-gui.php';

// Load classes.
require_once __DIR__ . '/class-feed-manager.php';
require_once __DIR__ . '/class-feed.php';
require_once __DIR__ . '/feed-items/class-feed-item.php';
require_once __DIR__ . '/feed-items/class-post-feed-item.php';
require_once __DIR__ . '/feed-items/class-attachment-feed-item.php';
require_once __DIR__ . '/feed-items/class-guest-author-feed-item.php';
require_once __DIR__ . '/feed-items/class-term-feed-item.php';
require_once __DIR__ . '/feed-items/class-user-feed-item.php';

// Include CLI script.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/class-sync-cli.php';
}

/**
 * Get the feed manager instance.
 *
 * @return Feed_Manager
 */
function feed_manager() : Feed_Manager {
	return Feed_Manager::instance();
}

/**
 * Easily register a feed with the feed manager.
 *
 * @param string $feed_string Feed namespace.
 * @return mixed
 */
function register_feed( $feed_string ) {
	return feed_manager()->register_feed( $feed_string );
}

/**
 * Get the module path
 *
 * @return string
 */
function get_module_path() {
	return __DIR__;
}

/**
 * Get the module URL.
 *
 * @return string
 */
function get_module_url() {
	$parts = explode( '/wp-content/', get_module_path() );
	return content_url( end( $parts ) );
}

/**
 * Is debug mode active?
 *
 * @return bool Whether debug mode is active.
 */
function debugging_sync() {
	return defined( 'SYNC_DEBUG' ) && SYNC_DEBUG;
}

/**
 * Log data for debugging purposes.
 *
 * @param string                 $message Message to log.
 * @param array|bool|string|null $value   Value to log.
 */
function alleypack_log( string $message = '', $value = '' ) {
	if ( ! debugging_sync() ) {
		return;
	}

	if ( is_array( $value ) || is_object( $value ) ) {
		$value = wp_json_encode( $value );
	} elseif ( is_bool( $value ) ) {
		$value = true === $value ? '(bool) true' : '(bool) false';
	} elseif ( is_null( $value ) ) {
		$value = '(null)';
	}

	error_log( "{$message} {$value}" ); // phpcs:ignore
}
