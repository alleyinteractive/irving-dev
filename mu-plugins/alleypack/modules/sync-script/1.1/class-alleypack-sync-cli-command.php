<?php
/**
 * Alleypack Sync Script CLI commands.
 *
 * @package Alleypack
 */

namespace Alleypack\Sync_Script;

use \WP_CLI;

/* phpcs:disable WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli */

/**
 * CLI Commands.
 */
class Alleypack_Sync_CLI_Command extends \WP_CLI_Command {

	/**
	 * Trigger a full sync for a given feed.
	 *
	 * ## EXAMPLE
	 *
	 *   $ wp alleypack_sync full_sync --url=site.alley.test --feed_class='\Site\\Site_Feed' --limit=5 --offset=0
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function full_sync( $args, $assoc_args ) {
		$limit      = $assoc_args['limit'] ?? 1;
		$offset     = $assoc_args['offset'] ?? 0;
		$paged      = ! empty( $assoc_args['paged'] );
		$feed_class = $assoc_args['feed_class'] ?? '';

		if ( empty( $feed_class ) ) {
			WP_CLI::error( 'No feed class value provided.' );
		}

		if ( ! class_exists( $feed_class ) ) {
			WP_CLI::error( 'Feed class does not exist.' );
		}

		// Sync the first batch.
		$has_more = ( new $feed_class() )->sync_feed_items( $limit, $offset );
		WP_CLI::success( "Synced content with limit {$limit} and offset {$offset}." );

		do {
			// Increase the offset.
			if ( $paged ) {
				$offset++;
			} else {
				$offset += $limit;
			}

			// Sync the next batch.
			$has_more = ( new $feed_class() )->sync_feed_items( $limit, $offset );
			WP_CLI::success( "Synced content with limit {$limit} and offset {$offset}." );

			// Contain memory leaks.
			if ( method_exists( $this, 'stop_the_insanity' ) ) {
				$this->stop_the_insanity();
			}
		} while ( $has_more );

		WP_CLI::success( 'Content sync complete.' );
	}

	/**
	 * Clear all of the caches for memory management.
	 */
	private function stop_the_insanity() {
		global $wpdb, $wp_object_cache;

		$wpdb->queries = [];

		if ( ! is_object( $wp_object_cache ) ) {
			return;
		}

		$wp_object_cache->group_ops      = [];
		$wp_object_cache->stats          = [];
		$wp_object_cache->memcache_debug = [];
		$wp_object_cache->cache          = [];

		if ( is_callable( $wp_object_cache, '__remoteset' ) ) {
			$wp_object_cache->__remoteset(); // Important.
		}
	}
}
WP_CLI::add_command( 'alleypack_sync', __NAMESPACE__ . '\Alleypack_Sync_CLI_Command' );
