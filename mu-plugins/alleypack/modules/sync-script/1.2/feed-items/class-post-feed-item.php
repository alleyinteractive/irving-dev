<?php
/**
 * Post Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * This class is created for any object being sync'd.
 */
abstract class Post_Feed_Item extends \Alleypack\Sync_Script\Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'post';

	/**
	 * Get the post type.
	 *
	 * @return string
	 */
	public static function get_post_type() {
		return static::$post_type;
	}

	/**
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	public function get_object_id() {
		return $this->object['ID'] ?? null;
	}

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

		$posts = (array) get_posts(
			[
				'meta_key'    => static::get_unique_id_key(),
				'meta_value'  => $unique_id,
				'post_status' => [ 'any', 'trash' ],
				'post_type'   => static::get_post_type(),
			]
		);

		if ( empty( $posts ) ) {
			return null;
		}

		// Shift the first post and load it as the object.
		$first_post = array_shift( $posts );

		// Add to mapping.
		static::$mapping[ $unique_id ] = $first_post;

		return $first_post;
	}

	/**
	 * Create or update the post object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() {

		// Set correct post type.
		$this->object['post_type'] = static::get_post_type();

		// Insert or update post.
		$post_id = wp_insert_post( $this->object );

		// Post ID exists?
		if ( $post_id instanceof \WP_Error ) {
			return false;
		}

		$this->object = (array) get_post( $post_id );
		$this->update_object_cache( $post_id );

		return true;
	}

	/**
	 * Store various meta data used for caching.
	 */
	public function update_object_cache() {
		$post_id = $this->get_object_id();
		update_post_meta( $post_id, static::get_unique_id_key(), $this->get_unique_id() );
		update_post_meta( $post_id, static::get_hash_key(), $this->get_source_hash() );
		update_post_meta( $post_id, static::get_mapping_version_key(), static::get_mapping_version() );
	}

	/**
	 * Does the item need to be sync'd?
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {

		// Debug mode.
		if ( debugging_sync() ) {
			return true;
		}

		// First time parsing this item.
		if ( is_null( $this->get_object_id() ) ) {
			return true;
		}

		$post_id = $this->get_object_id();

		// Get the hash and mapping version from post meta.
		$cached_hash            = get_post_meta( $post_id, static::get_hash_key(), true );
		$cached_mapping_version = (string) get_post_meta( $post_id, static::get_mapping_version_key(), true );

		// If either the hash or mapping versions do not match the cached
		// values, we should sync.
		if (
			$cached_hash !== $this->get_source_hash()
			|| static::get_mapping_version() !== $cached_mapping_version
		) {
			return true;
		}

		// Executing a sync won't change anything, so republish the post and
		// move on.
		return false;
	}
}
