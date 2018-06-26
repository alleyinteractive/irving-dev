<?php
/**
 * Archive routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Return the components for an archive.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function archive_components( \WP_Query $wp_query ) : array {

	$post_ids = wp_list_pluck( $wp_query->posts, 'ID' );

	// Build array of components.
	$components = [];

	$components[] = Component\content_grid()
		->set_config( 'title', __( 'Content Grid', 'irving-dev' ) )
		->set_children_by_post_ids( $post_ids );

	$components[] = Component\load_more()
		->set_pagination_vars(
			$wp_query->query_vars['paged'],
			$wp_query->max_num_pages
		);

	return $components;
}
