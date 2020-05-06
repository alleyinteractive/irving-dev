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
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	public function get_object_id() {
		return $this->object['ID'] ?? null;
	}

	/**
	 * Attempt to load a post object.
	 */
	public function load_object() {
		alleypack_log( 'Loading unique ID key: ', $this->get_unique_id() );
		$posts = (array) get_posts( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
			[
				'meta_key'         => $this->unique_id_key,
				'meta_value'       => $this->get_unique_id(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'post_status'      => [ 'any', 'trash' ],
				'post_type'        => static::$post_type,
				'suppress_filters' => false,
			]
		);

		if ( empty( $posts ) ) {
			return;
		}

		// Shift the first post and load it as the object.
		$this->object = (array) array_shift( $posts );

		// Republish post.
		wp_publish_post( $this->get_object_id() );

		// Delete duplicate posts.
		foreach ( array_values( $posts ) as $post ) {
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	 * Update all previously synced posts as pending an update.
	 */
	public static function mark_existing_content_as_syncing() {
		global $wpdb;

		$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->posts,
			[
				'post_status' => 'alleypack-syncing',
			],
			[
				'post_status' => 'publish',
				'post_type'   => static::$post_type,
			]
		);
	}

	/**
	 * Trash any content that wasn't found during the sync.
	 */
	public static function unpublish_unsynced_content() {
		global $wpdb;

		$post_ids = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			"
			SELECT ID
			FROM   $wpdb->posts
			WHERE  post_status = 'alleypack-syncing'
			"
		);

		array_map( 'wp_trash_post', $post_ids );
	}

	/**
	 * Create or update the post object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() {

		// Set correct post type.
		$this->object['post_type'] = static::$post_type;

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
		update_post_meta( $post_id, $this->unique_id_key, $this->get_unique_id() );
		update_post_meta( $post_id, $this->hash_key, $this->get_source_hash() );
		update_post_meta( $post_id, $this->mapping_version_key, $this->mapping_version );
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
		$cached_hash            = get_post_meta( $post_id, $this->hash_key, true );
		$cached_mapping_version = (string) get_post_meta( $post_id, $this->mapping_version_key, true );

		// If either the hash or mapping versions do not match the cached
		// values, we should sync.
		if (
			$cached_hash !== $this->get_source_hash()
			|| $cached_mapping_version !== $this->mapping_version
		) {
			return true;
		}

		// Executing a sync won't change anything, so republish the post and
		// move on.
		return false;
	}
}
