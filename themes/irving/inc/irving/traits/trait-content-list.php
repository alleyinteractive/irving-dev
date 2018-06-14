<?php
/**
 * Trait for defining a content list.
 *
 * @package Irving
 */

namespace Irving;

/**
 * Trait for a content list.
 */
trait Content_List {

	/**
	 * Set this content list to a post.
	 *
	 * @param null|int|WP_Post $post Post ID, WP_Post object, or null;
	 * @return instance of this class.
	 */
	function set_children_by_post_ids( array $post_ids ) {

		$this->children = array_map( function( $post_id ) {
			return \WP_Irving\Component\content_card()->set_to_post( $post_id );
		}, $post_ids );

		return $this;
	}
}
