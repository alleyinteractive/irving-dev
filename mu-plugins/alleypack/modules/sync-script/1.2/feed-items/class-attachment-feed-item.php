<?php
/**
 * Attachment Feed Item class.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Feed item used for creating attachments. Uses the Alleypack attachments module.
 */
abstract class Attachment_Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'attachment';

	/**
	 * Get the original source URL for this attachment.
	 *
	 * @return null|string
	 */
	abstract public function get_source_url() : ?string;

	/**
	 * Create or update the post object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() {

		// Create attachment for the first time.
		if ( is_null( $this->get_object_id() ) ) {

			$attachment_id = \Alleypack\create_attachment_from_url( $this->get_source_url() );

			// Was there an error?
			if ( $attachment_id instanceof \WP_Error ) {
				return false;
			}

			// Inject the newly created attachment ID so we can update the
			// post object.
			$this->object['ID'] = $attachment_id;
		}

		// Update attachment.
		$attachment_id = wp_update_post( $this->object );

		$this->object = (array) get_post( $attachment_id );
		$this->update_object_cache( $attachment_id );

		return true;
	}
}
