<?php
/**
 * Single post routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Return the components for the homepage.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function homepage_components( \WP_Query $wp_query ) : array {

	// Build array of components.
	$components = [];

	/**
	 * Jumbotron.
	 *
	 * Use the first post in the query.
	 */
	$components[] = Component\jumbotron()
		->set_to_post( array_shift( $wp_query->posts ) );

	/**
	 * Jumbotron using a term.
	 *
	 * Use the first post in the query.
	 */
	$components[] = Component\jumbotron()
		->set_to_term( get_term_by( 'slug', 'uncategorized', 'category' ) );

	/**
	 * Content Grid.
	 */
	$components[] = Component\content_grid()
		->set_config( 'title', __( 'Content Grid', 'irving-dev' ) )
		->set_children_by_post_ids( $wp_query->posts );

	return $components;
}

/**
 * Return the components for a post.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function post_components( \WP_Query $wp_query ) : array {

	// Build array of components.
	$components = [];

	return $components;
}
