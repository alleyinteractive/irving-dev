<?php
/**
 * Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * This class is created for any object being synced.
 */
abstract class Feed_Item {

	/**
	 * Version track the mapping logic. If you modify `map_source_to_object`,
	 * `save_object`, or any other mapping logic, bump this version.
	 *
	 * @var string
	 */
	protected static $mapping_version = '1.0';

	/**
	 * Meta key for storing mapping version key.
	 *
	 * @var string
	 */
	protected static $mapping_version_key = 'alleypack_sync_script_mapping_version';

	/**
	 * Meta key for storing unique id.
	 *
	 * @var string
	 */
	protected static $unique_id_key = 'alleypack_sync_script_unique_id';

	/**
	 * Meta key for storing caching hash id.
	 *
	 * @var string
	 */
	protected static $hash_key = 'alleypack_sync_script_hash_key';

	/**
	 * Build a mapping of unique ids to WordPress objects.
	 *
	 * @var array
	 */
	protected static $mapping = [];

	/**
	 * Reference to the feed instance which is syncing this item.
	 *
	 * @var null
	 */
	protected static $feed = null;

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
	 * Load object data.
	 */
	public function load_object() {

		// Get object by the unique id.
		$object = static::get_object_by_unique_id( $this->get_unique_id() );
		if ( is_null( $object ) ) {
			return;
		}

		// Shift the first object and load it as the object.
		$this->object = (array) $object;
	}

	/**
	 * Get object by the unique id.
	 *
	 * @param string|int $unique_id Unique id.
	 * @return mixed Object.
	 */
	abstract public static function get_object_by_unique_id( $unique_id );

	/**
	 * Get or create the object using the source feed.
	 *
	 * @param array $source Source data.
	 * @return mixed|null Object.
	 */
	public static function get_or_create_object_from_source( array $source ) {

		// Validate source.
		if ( empty( $source ) ) {
			return null;
		}

		// Initialize a new instance and load the source data.
		$item = new static();
		$item->load_source( $source );

		// Attempt to get an existing object.
		$object = static::get_object_by_unique_id( $item->get_unique_id() );
		if ( ! is_null( $object ) ) {
			return $object;
		}

		// Create and retry getting the object.
		$item->sync();
		$object = static::get_object_by_unique_id( $item->get_unique_id() );
		if ( ! is_null( $object ) ) {
			return $object;
		}

		// Something went wrong.
		return null;
	}

	/**
	 * Save object data.
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
	 * Get the mapping version.
	 *
	 * @return string
	 */
	public static function get_mapping_version() {
		return static::$mapping_version;
	}

	/**
	 * Get the mapping version key.
	 *
	 * @return string
	 */
	public static function get_mapping_version_key() {
		return static::$mapping_version_key;
	}

	/**
	 * Get the unique id key.
	 *
	 * @return string
	 */
	public static function get_unique_id_key() {
		return static::$unique_id_key;
	}

	/**
	 * Get the hash key.
	 *
	 * @return string
	 */
	public static function get_hash_key() {
		return static::$hash_key;
	}

	/**
	 * Set the feed registered to this item class.
	 *
	 * @param object $feed Feed class instance for this item.
	 */
	public static function set_feed( $feed ) {
		static::$feed = $feed;
	}

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

	/**
	 * Placeholder function to easily render a gui interface for a feed item.
	 */
	public static function setup_gui() {
	}
}
