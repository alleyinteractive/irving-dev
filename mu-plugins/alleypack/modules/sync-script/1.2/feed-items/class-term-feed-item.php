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
	 * Helper that gets a term by the unique ID.
	 *
	 * @param string $unique_id Unique ID.
	 * @return null|WP_Term
	 */
	public static function get_object_by_unique_id( $unique_id ) {

		alleypack_log( 'Loading term by unique ID: ', $unique_id );

		// Return from mapping if available.
		if ( isset( static::$mapping[ $unique_id ] ) ) {
			return static::$mapping[ $unique_id ];
		}

		// Get and validate term.
		$terms = (array) get_terms(
			[
				'hide_empty' => false,
				'meta_key'   => static::$unique_id_key,
				'meta_value' => $unique_id,
				'taxonomy'   => static::$taxonomy,
			]
		);

		if ( empty( $terms ) ) {
			return null;
		}

		// Shift the first term and load it as the object.
		$first_term = array_shift( $terms );

		// Add to mapping.
		static::$mapping[ $unique_id ] = $first_term;

		return $first_term;
	}

	/**
	 * Create or update the term object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() : bool {

		// Set correct taxonomy.
		$this->object['taxonomy'] = static::$taxonomy;

		// Object does not exist.
		if ( is_null( $this->get_object_id() ) ) {

			// Attempt to insert the term.
			$inserted_term = wp_insert_term( $this->object['name'], static::$taxonomy, $this->object );

			// Term already existed.
			if (
				$inserted_term instanceof \WP_Error
				&& isset( $inserted_term->error_data['term_exists'] )
			) {
				$this->object = (array) get_term( $inserted_term->error_data['term_exists'], static::$taxonomy );
			}

			// Term was created.
			if (
				is_array( $inserted_term )
				&& isset( $inserted_term['term_id'] )
			) {
				$this->object = (array) get_term( $inserted_term['term_id'], static::$taxonomy );
			}
		} else {
			wp_update_term(
				$this->get_object_id(),
				static::$taxonomy,
				$this->object
			);
		}

		$this->update_object_cache();

		return true;
	}

	/**
	 * Store various meta data used for caching.
	 */
	public function update_object_cache() {
		$term_id = $this->get_object_id();
		update_term_meta( $term_id, self::get_unique_id_key(), $this->get_unique_id() );
		update_term_meta( $term_id, static::get_hash_key(), $this->get_source_hash() );
		update_term_meta( $term_id, static::get_mapping_version_key(), $this->get_mapping_version() );
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
		$cached_hash            = get_term_meta( $term_id, static::get_hash_key(), true );
		$cached_mapping_version = (string) get_term_meta( $term_id, static::get_mapping_version_key(), true );

		// If either the hash or mapping versions do not match the cached
		// values, we should sync.
		if (
			$cached_hash !== $this->get_source_hash()
			|| $cached_mapping_version !== $this->get_mapping_version()
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
