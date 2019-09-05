<?php
/**
 * Unique WP Query Manager is the brains behind Unique_WP_Query.
 *
 * Keeps track of which posts have already appeared and removes them from
 * future Unique_WP_Query objects.
 *
 * @package Alleypack
 * @version 1.0.0
 */

namespace Alleypack;

/**
 * Unique WP Query Manager class.
 */
class Unique_WP_Query_Manager {

	/**
	 * Keep track of which post_ids have already been used.
	 *
	 * @var array
	 */
	public static $used_post_ids = [];

	/**
	 * Takes the posts and post count of a WP_Query object and
	 * ensures that it removes posts that have already been used,
	 * and then trims it to the necessary size.
	 *
	 * @param  array $posts                   Posts.
	 * @param  int   $post_count              Number of returned posts.
	 * @param  int   $original_posts_per_page The original posts_per_page value.
	 */
	public static function process_posts( &$posts, &$post_count, $original_posts_per_page ) {

		// Keep track of how many posts are acceptable to return.
		$current_accepted_post_count = 0;

		// Make sure we have posts.
		if ( empty( (array) $posts ) ) {
			return;
		}

		// Loop through all the found posts.
		foreach ( (array) $posts as $key => $post ) {

			// If we have enough acceptable posts, break the loop. Otherwise,
			// determine if we've already used this post.
			if ( $original_posts_per_page > $current_accepted_post_count ) {

				// Handle WP_Post or integers.
				if ( $post instanceof \WP_Post ) {
					$post_id = $post->ID;
				} else {
					$post_id = $post;
				}

				// Has this post already been used?
				if ( in_array( $post_id, self::$used_post_ids, true ) ) {

					// Remove from $posts.
					unset( $posts[ $key ] );

					// And update count.
					$post_count--;

				} else {
					// If not, add it to the list of used ids.
					self::$used_post_ids[] = $post_id;

					// Update how many accepted posts we have.
					$current_accepted_post_count++;
				}
			} else {
				// If we have enough acceptable posts, break the foreach.
				// This prevents extra results from accidentally being added to $used_post_ids.
				break;
			}
		}

		// Reindex the array correctly.
		$posts = array_values( $posts );

		// Trim any excess posts and update $post_count.
		if ( count( $posts ) > $original_posts_per_page ) {

			// Remove extra posts.
			$posts = array_slice( $posts, 0, $original_posts_per_page );

			// Update post count to our new value.
			$post_count = $original_posts_per_page;
		}
	}

	/**
	 * Return the number of post ids that have been used.
	 *
	 * @return integer
	 */
	public static function count_used_post_ids() {
		return count( self::$used_post_ids );
	}

	/**
	 * Explicitly set the used post ids.
	 *
	 * @param array $used_ids Used ids.
	 */
	public static function set_used_post_ids( $used_ids ) {
		self::$used_post_ids = $used_ids;
	}

	/**
	 * Add used post ids to the tracker array.
	 *
	 * @param integer|array $used_ids Used ids.
	 */
	public static function add_used_post_ids( $used_ids ) {

		// Convert a single value into an array.
		if ( ! is_array( $used_ids ) ) {
			$used_ids = [ $used_ids ];
		}

		// Clean all entries.
		$used_ids = array_map( 'absint', $used_ids );

		// Merge with existing ids.
		self::$used_post_ids = array_merge(
			self::$used_post_ids,
			$used_ids
		);
	}
}

if ( ! function_exists( 'unique_wp_query_pre_get_posts' ) ) {
	/**
	 * Increase the posts_per_page value by the number of posts that have
	 * already been used. Executes as the last pre_get_posts action.
	 *
	 * @param  WP_Query $query The query.
	 */
	function unique_wp_query_pre_get_posts( &$query ) {

		// Only apply to Unique_WP_Query objects.
		if ( true === $query->get( 'unique_wp_query' ) ) {

			// Check our upward bound of posts_per_page.
			$posts_per_page = $query->get( 'posts_per_page' );

			if ( $posts_per_page <= 200 ) {

				// Increase posts_per_page by the amount of used post_ids.
				$query->set( 'original_posts_per_page', $posts_per_page );
				$query->set( 'posts_per_page', $posts_per_page + Unique_WP_Query_Manager::count_used_post_ids() );
			} else {

				// Max out at 200 posts_per_page.
				$query->set( 'original_posts_per_page', 200 );
				$query->set( 'posts_per_page', 200 );
			}
		}
	}

	// Ensure it's always the last pre_get_posts action.
	add_action( 'pre_get_posts', __NAMESPACE__ . '\unique_wp_query_pre_get_posts', PHP_INT_MAX );
}
