<?php
/**
 * Single post routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Routing for the homepage.
 *
 * @param  array     $response Endpoint response.
 * @param  \WP_Query $wp_query Endpoint WP_Query object.
 * @param  string    $context  Endpoint context.
 * @param  string    $path     Endpoint path.
 * @return array Endpoint response.
 */
function homepage_route( array $response, \WP_Query $wp_query, string $context, string $path ) {
	if ( '/' === $path || '' === $path ) {
		$response = [
			Component\component_wrapper(
				[
					'config' => [
						'classes' => [ 'post__wrapper' ],
					],
					'children' => homepage_components( $wp_query ),
				]
			),
		];
	}
	return $response;
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\homepage_route', 10, 4 );

/**
 * Modify homepage query.
 *
 * @param  \WP_Query $wp_query WP_Query object.
 */
function modify_homepage( \WP_Query $wp_query ) {
	$wp_query->set( 'fields', 'ids' );
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\modify_homepage' );


/**
 * Return the homepage components.
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
