<?php
/**
 * Singleton module.
 *
 * @package Alleypack
 * @version 1.1.0
 * @see readme.md
 */

namespace Alleypack\Sync_Script;

// Load classes.
require_once 'class-feed.php';
require_once 'feed-items/class-feed-item.php';
require_once 'feed-items/class-post-feed-item.php';
require_once 'feed-items/class-term-feed-item.php';
require_once 'feed-items/class-user-feed-item.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once 'class-alleypack-sync-cli-command.php';
}

/**
 * Register a new post/term/user sync class.
 *
 * @param string $feed_class Post Sync class to register.
 */
function register_feed( string $feed_class ) {
	// Hook into init after post types and taxonomies have been registered.
	add_action(
		'init',
		function() use ( $feed_class ) {
			if ( ! class_exists( $feed_class ) ) {
				wp_die(
					sprintf(
						// translators: %1$s feed class.
						esc_html__( 'Class %1$s not found.', 'alleypack' ),
						esc_html( $feed_class )
					)
				);
			}
			new $feed_class();
		},
		11
	);
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
