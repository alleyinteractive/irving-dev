<?php
/**
 * User Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * This class is created for any object being sync'd.
 */
abstract class User_Feed_Item extends \Alleypack\Sync_Script\Feed_Item {

	/**
	 * User type.
	 *
	 * @var string
	 */
	public static $user = 'user';

	/**
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	public function get_object_id() {
		return $this->object['ID'] ?? null;
	}

	/**
	 * Attempt to load a user object.
	 */
	public function load_object() {
		alleypack_log( 'Loading unique ID key: ', $this->get_unique_id() );
		$users = (array) get_users(
			[
				'meta_key'   => $this->unique_id_key,
				'meta_value' => $this->get_unique_id(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);

		if ( empty( $users ) ) {
			return;
		}

		// Shift the first user and load it as the object.
		$this->object       = (array) array_shift( $users );
		$this->object['ID'] = $this->get_object_id();
	}

	/**
	 * Create or update the user object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() : bool {

		// Insert or update user.
		if ( $this->get_object_id() ) {
			$user_id = wp_update_user( $this->get_object_id() );
		} else {
			$user_id = wp_insert_user( $this->object );
		}

		// User ID exists?
		if ( $user_id instanceof \WP_Error ) {
			return false;
		}

		$this->object = (array) get_userdata( $user_id );
		$this->update_object_cache( $user_id );

		return true;
	}

	/**
	 * Store various meta data used for caching.
	 */
	public function update_object_cache() {
		$user_id = $this->get_object_id();
		update_user_meta( $user_id, $this->unique_id_key, $this->get_unique_id() );
		update_user_meta( $user_id, $this->hash_key, $this->get_source_hash() );
		update_user_meta( $user_id, $this->mapping_version_key, $this->mapping_version );
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

		$user_id = $this->get_object_id();

		// Get the hash and mapping version from post meta.
		$cached_hash            = get_user_meta( $user_id, $this->hash_key, true );
		$cached_mapping_version = (string) get_user_meta( $user_id, $this->mapping_version_key, true );

		// If either the hash or mapping versions do not match the cached
		// values, we should sync.
		if (
			$cached_hash !== $this->get_source_hash()
			|| $cached_mapping_version !== $this->mapping_version
		) {
			return true;
		}

		// Executing a sync won't change anything, so republish the user and
		// move on.
		return false;
	}

	/**
	 * Doing nothing.
	 */
	public static function mark_existing_content_as_syncing() {
	}

	/**
	 * Create a guest author from an existing WordPress user.
	 *
	 * @param int $user_id ID for a WordPress user.
	 * @return int|WP_Error ID for the new guest author on success, WP_Error on failure.
	 */
	public static function create_guest_author_from_user_id( $user_id ) {
		global $coauthors_plus;

		$user = get_user_by( 'id', $user_id );

		if ( ! $user ) {
			return;
		}

		$guest_author = [];
		foreach ( $coauthors_plus->guest_authors->get_guest_author_fields() as $field ) {
			$key = $field['key'];
			if ( ! empty( $user->$key ) ) {
				$guest_author[ $key ] = $user->$key;
			} else {
				$guest_author[ $key ] = '';
			}
		}

		// Don't need the old user ID.
		unset( $guest_author['ID'] );

		// Retain the user mapping and try to produce an unique user_login based on the name.
		$guest_author['linked_account'] = $guest_author['user_login'];
		if ( ! empty( $guest_author['display_name'] ) && $guest_author['display_name'] !== $guest_author['user_login'] ) {
			$guest_author['user_login'] = sanitize_title( $guest_author['display_name'] );
		} elseif ( ! empty( $guest_author['first_name'] ) && ! empty( $guest_author['last_name'] ) ) {
			$guest_author['user_login'] = sanitize_title( $guest_author['first_name'] . ' ' . $guest_author['last_name'] );
		}

		return $coauthors_plus->guest_authors->create( $guest_author );
	}
}
