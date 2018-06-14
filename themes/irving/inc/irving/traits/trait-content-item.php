<?php
/**
 * Trait for defining a content item.
 *
 * @package Irving
 */

namespace Irving;

/**
 * Trait for a content item.
 */
trait Content_Item {

	/**
	 * Set this content item to a post.
	 *
	 * @param null|int|WP_Post $post Post ID, WP_Post object, or null;
	 * @return instance of this class.
	 */
	function set_to_post( $post = null ) {

		// If null, attempt to get a post ID from the loop.
		if ( is_null( $post ) ) {
			$post = get_the_ID();
		}

		// If post is an integer, assume it's the post ID.
		if ( 0 !== absint( $post ) ) {
			$post = get_post( $post );
		}

		// Valid post object. Map fields.
		if ( $post instanceof \WP_Post ) {
			$this->set_config( 'object', $post );
			$this->set_config( 'type', 'post' );
		}

		return $this;
	}
}
