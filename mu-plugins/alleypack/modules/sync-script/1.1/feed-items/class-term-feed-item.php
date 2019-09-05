<?php
/**
 * Term Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Term Feed Item Class.
 */
abstract class Term_Feed_Item extends \Alleypack\Sync_Script\Feed_Item {

	/**
	 * Object Taxonomy.
	 *
	 * @var string
	 */
	public static $taxonomy = 'category';

	/**
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	public function get_object_id() {
		return $this->object['term_id'] ?? null;
	}

	/**
	 * Attempt to load a term object.
	 */
	public function load_object() {
		alleypack_log( 'Loading unique ID key: ', $this->get_unique_id() );
		$terms = (array) get_terms(
			[
				'taxonomy'   => static::$taxonomy,
				'meta_key'   => $this->unique_id_key,
				'meta_value' => $this->get_unique_id(),
			]
		);

		if ( empty( $terms ) ) {
			return;
		}

		// Shift the first term and load it as the object.
		$this->object = (array) array_shift( $terms );
	}

	/**
	 * Create or update the term object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() : bool {

		// Set correct taxonomy.
		$this->object['taxonomy'] = static::$taxonomy;

		// Insert or update term.
		if ( $this->get_object_id() ) {
			$term_id = wp_update_term( $this->get_object_id(), static::$taxonomy );
		} else {
			$term_id = wp_insert_term( $this->object['name'], static::$taxonomy );
		}

		// Term ID exists?
		if ( $term_id instanceof \WP_Error ) {
			return false;
		}

		$this->object = (array) get_term( $term_id, static::$taxonomy );
		$this->update_object_cache( $term_id );

		return true;
	}

	/**
	 * Store various meta data used for caching.
	 */
	public function update_object_cache() {
		$term_id = $this->get_object_id();
		update_term_meta( $term_id, $this->unique_id_key, $this->get_unique_id() );
		update_term_meta( $term_id, $this->hash_key, $this->get_source_hash() );
		update_term_meta( $term_id, $this->mapping_version_key, $this->mapping_version );
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

		$term_id = $this->get_object_id();

		// Get the hash and mapping version from term meta.
		$cached_hash            = get_term_meta( $term_id, $this->hash_key, true );
		$cached_mapping_version = (string) get_term_meta( $term_id, $this->mapping_version_key, true );

		// If either the hash or mapping versions do not match the cached
		// values, we should sync.
		if (
			$cached_hash !== $this->get_source_hash()
			|| $cached_mapping_version !== $this->mapping_version
		) {
			return true;
		}

		// Executing a sync won't change anything, so republish the term and
		// move on.
		return false;
	}

	/**
	 * Doing nothing for now.
	 */
	public static function mark_existing_content_as_syncing() {
	}
}
