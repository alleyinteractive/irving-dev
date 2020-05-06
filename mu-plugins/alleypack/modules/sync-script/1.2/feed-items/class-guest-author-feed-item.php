<?php
/**
 * Guest Author Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * This class is created for any object being sync'd.
 */
abstract class Guest_Author_Feed_Item extends Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'guest-author';

	/**
	 * Helper that gets a post by the unique ID.
	 *
	 * @param string $unique_id Unique ID.
	 * @return null|WP_Post
	 */
	public static function get_object_by_unique_id( $unique_id ) {

		alleypack_log( 'Loading post by unique ID: ', $unique_id );

		// Return from mapping if available.
		if ( isset( static::$mapping[ $unique_id ] ) ) {
			return static::$mapping[ $unique_id ];
		}

		$users = (array) get_posts( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
			[
				'meta_key'         => static::get_unique_id_key(),
				'meta_value'       => $unique_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'post_status'      => [ 'any', 'trash' ],
				'post_type'        => static::get_post_type(),
				'suppress_filters' => false,
			]
		);

		if ( empty( $users ) ) {
			return null;
		}

		// Shift the first post and load it as the object.
		$first_user = array_shift( $users );

		// Add to mapping.
		static::$mapping[ $unique_id ] = $first_user;

		return $first_user;
	}

	/**
	 * Create or update the post object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() {

		global $coauthors_plus;

		$this->object['post_status'] = 'publish';

		// Create the guest author.
		if ( is_null( $this->get_object_id() ) ) {
			$author_id = $coauthors_plus->guest_authors->create( $this->object );
		} else {

			// Insert or update post.
			$author_id = wp_insert_post( $this->object );

			// Post ID exists?
			if ( $author_id instanceof \WP_Error ) {
				return false;
			}
		}

		// Post ID exists?
		if ( $author_id instanceof \WP_Error ) {
			return false;
		}

		$this->object = (array) get_post( $author_id );
		$this->update_object_cache( $author_id );

		return true;
	}
}
