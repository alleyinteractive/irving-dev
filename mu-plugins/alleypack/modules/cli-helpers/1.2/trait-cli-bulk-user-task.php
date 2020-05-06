<?php
/**
 * CLI bulk user task trait.
 *
 * @package Alleypack.
 */

namespace Alleypack;

use \WP_User_Query;

/**
 * Chunk up the task when you need to iterate over many users.
 *
 * For instance, to iterate over every post on the site and add post meta:
 *
 *     $this->bulk_task( function( $user ) {
 *         update_user_meta( $user->ID, 'some_meta', 'some value' );
 *     } );
 *
 * To do the same thing, but only the "Administrator" role, you might:
 *
 *     $this->bulk_task( [ 'role' => 'Administrator' ], function( $post ) {
 *         update_user_meta( $user->ID, 'some_meta', 'some value' );
 *     } );
 *
 * Users are iterated by ID, so changing data is relatively safe. For instance,
 *
 *     $this->bulk_task( 'wp_delete_user' );
 *
 * If your class has a method `stop_the_insanity()` available to prevent memory
 * leaks, it will be called after each chunk. For an example, see
 * {link https://github.com/Automattic/vip-mu-plugins-public/blob/master/vip-helpers/vip-wp-cli.php#L5-L23}
 *
 * @author Matthew Boynes, Alley Interactive
 * @license GPLv2
 * @codingStandardsIgnoreFile
 */
trait CLI_Bulk_User_Task {
	use CLI_Bulk_Task;

	/**
	 * Loop through any number of users efficiently with a callback, and output
	 * the progress.
	 *
	 * @param  array $args {
	 *     Optional. WP_User_Query args. Some have overridden defaults, and some are
	 *     fixed. Anything not mentioned below will operate as normal.
	 *
	 *     @type int $number Defaults to 100.
	 *     @type int $paged Always 1.
	 *     @type string $orderby Always 'ID'.
	 *     @type string $order Always 'ASC'.
	 * }
	 * @param  callable $callable Required. Callback function to invoke for each
	 *                            post. The callable will be passed a WP_Post
	 *                            object.
	 */
	protected function bulk_task( $args, $callable = null ) {
		// $args is optional, so if it's callable, assume it replaces $callable.
		if ( is_callable( $args ) ) {
			$callable = $args;
			$args = array();
		}

		// Ensure that we have a callable.
		if ( ! is_callable( $callable ) ) {
			WP_CLI::error( 'You must pass a callable to `bulk_task()`' );
		}

		$args = wp_parse_args( $args, [
			'meta_query' => [],
			'number'     => 100,
		] );

		// Force some arguments and don't let them get overridden.
		$args['paged']               = 1;
		$args['orderby']             = 'ID';
		$args['order']               = 'ASC';

		// Ensure $bulk_task_min_id always starts at 0.
		$this->bulk_task_min_id = 0;
		$current_page = 0;
		$posts_per_page = $args['number'];

		// Output the empty status.
		$this->do_bulk_status();
		echo "\n";

		// All systems go.
		do {

			$args['offset'] = $posts_per_page * $current_page;
			// Build the query object, but don't run it without the object hash.
			$query = new WP_User_Query( $args );

			// Ensure offset is set on each run for pagination
			$query->set('query_where', "AND {$GLOBALS['wpdb']->users}.ID > {$this->bulk_task_min_id}");

			// Run the query
			$users = $query->get_results();

			// Invoke the callable over every post
			array_walk( $users, $callable );

			// Update our min ID for the next query
			$user_ids = wp_list_pluck( $users, 'ID' );
			$this->bulk_task_min_id = ! empty( $user_ids ) ? max( $user_ids ) : null;

			// Contain memory leaks
			if ( method_exists( $this, 'stop_the_insanity' ) ) {
				$this->stop_the_insanity();
			}

			// WP_User_Query doesn't provide a `max_num_pages` property.
			$max_num_pages = $query->get_total() / $posts_per_page;

			// Update the status
			$this->do_bulk_status( ++$current_page, $max_num_pages);

		} while ( ! empty( $users ) && $max_num_pages > 1 && $current_page !== $max_num_pages );
		echo "\n";

		$this->bulk_task_min_id = null;
	}
}
