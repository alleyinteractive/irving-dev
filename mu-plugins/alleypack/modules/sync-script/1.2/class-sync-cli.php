<?php
/**
 * Sync script CLI.
 *
 * @package Alleypack
 */

namespace Alleypack\Sync_Script;

use \WP_CLI;

/* phpcs:disable WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli */

/**
 * CLI Commands.
 */
class Sync_CLI extends \WP_CLI_Command {

	/**
	 * Trigger a sync.
	 *
	 * <sync>
	 * : Feed item sync slug.
	 *
	 * [--limit=<number>]
	 * : Total number of objects to sync.
	 * ---
	 * default: 10
	 * ---
	 *
	 * [--offset=<number>]
	 * : Offset number of objects to sync.
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--unique_id=<number>]
	 * : Unique object ID.
	 * ---
	 * default: 0
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *   $ wp alleypack sync articles --limit=5 --offset=0 --url=site.alley.test
	 *   $ wp alleypack sync articles --unique_id=2615616 --url=site.alley.test
	 *   $ wp alleypack sync story
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function sync( $args, $assoc_args ) {

		// Get the registered feed object.
		$feed_slug = $args[0] ?? '';
		$feed      = feed_manager()->get_feed( $feed_slug );
		if ( is_null( $feed ) ) {
			WP_CLI::error( "Invalid feed {$feed_slug}" );
		}

		$limit     = $assoc_args['limit'];
		$offset    = $assoc_args['offset'];
		$unique_id = $assoc_args['unique_id'];

		if ( ! empty( $unique_id ) ) {
			$feed->load_source_data_by_unique_id( $unique_id );
			$feed->sync_source_data();
			WP_CLI::success( "Syncing {$unique_id}" );
		} else {
			// Loop through all data.
			do {
				// Setup the source data.
				$feed->reset_source_data();
				$feed->load_source_data_by_limit_and_offset( $limit, $offset );

				// Display syncing message.
				$range_start = $offset + 1;
				$range_end   = $offset + $limit;
				WP_CLI::success( "Syncing {$range_start} - {$range_end}" );

				// Increase the offset.
				$offset += $limit;

				// Clean resources.
				$this->stop_the_insanity();

			} while ( $feed->sync_source_data() );
		}

		WP_CLI::success( 'Sync completed.' );
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
WP_CLI::add_command( 'alleypack', __NAMESPACE__ . '\Sync_CLI' );
