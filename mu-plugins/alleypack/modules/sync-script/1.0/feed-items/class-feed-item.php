<?php
/**
 * Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * This class is created for any object being sync'd.
 */
abstract class Feed_Item {

	/**
	 * Source data for this item from the feed.
	 *
	 * @var array
	 */
	protected $source = [];

	/**
	 * Object data in WordPress.
	 *
	 * @var array
	 */
	protected $object = [];

	/**
	 * Version track the mapping logic. If you modify `map_post`, `post_save`,
	 * or any other mapping logic, bump this version.
	 *
	 * @var string
	 */
	protected $mapping_version = '1.0';

	/**
	 * Meta key for storing unique id.
	 *
	 * @var string
	 */
	protected $unique_id_key = 'alleypack_sync_script_unique_id';

	/**
	 * Meta key for storing caching hash id.
	 *
	 * @var string
	 */
	protected $hash_key = 'alleypack_sync_script_hash_key';

	/**
	 * Meta key for storing mapping version key.
	 *
	 * @var string
	 */
	protected $mapping_version_key = 'alleypack_sync_script_mapping_version';

	/**
	 * Static function that is called when the feed is setup. Use this function
	 * for code that should be ran on feed setup.
	 *
	 * @param object $feed The feed being setup.
	 */
	public static function feed_setup( $feed ) {
		// Silence is golden.
	}

	/**
	 * Load object data.
	 */
	abstract public function load_object();

	/**
	 * Load object data.
	 */
	abstract public function save_object();

	/**
	 * Should object sync.
	 */
	abstract public function should_object_sync();

	/**
	 * Map source to object.
	 */
	abstract public function map_source_to_object();

	/**
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	abstract public function get_object_id();

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	abstract public function get_unique_id();

	/**
	 * Store various meta data used for caching.
	 *
	 * @return string|bool
	 */
	abstract public function update_object_cache();

	/**
	 * Mark all previously synced feed items as pending an update.
	 */
	abstract public static function mark_existing_content_as_syncing();

	/**
	 * Get the md5 hash of the JSON encoded source data. This is used as a
	 * caching key to detect when the source data has been updated.
	 *
	 * @return string|bool
	 */
	protected function get_source_hash() {
		if ( empty( $this->source ) ) {
			return false;
		}

		return md5( wp_json_encode( $this->source ) );
	}

	/**
	 * Load source data.
	 *
	 * @param array $data Source data.
	 */
	public function load_source( array $data ) {
		$this->source = (array) $data;
	}

	/**
	 * Sync this item.
	 */
	public function sync() {

		// Attempt to load the existing object.
		$this->load_object();

		alleypack_log( 'Object loaded. Should object sync:', $this->should_object_sync() );

		// Determine if we need to sync the object.
		if ( $this->should_object_sync() ) {

			// Map from source data to object.
			$this->map_source_to_object();

			// Object successfully saved.
			if ( $this->save_object() ) {
				$this->post_object_save();
			}
		}
	}

	/**
	 * Placeholder function to extend knowing that a valid item exists and has
	 * already been saved.
	 */
	public function post_object_save() {
	}
}
