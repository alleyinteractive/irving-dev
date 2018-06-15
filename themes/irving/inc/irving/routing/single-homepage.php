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
 * Return the homepage components.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function homepage_components( \WP_Query $wp_query ) : array {

	$post_ids = wp_list_pluck( $wp_query->posts, 'id' );

	// Build array of components.
	$components = [];

	/**
	 * Jumbotron.
	 *
	 * Use the first post in the query.
	 */
	$components[] = Component\jumbotron()
		->set_to_post( array_shift( $post_ids ) );

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
		->set_children_by_post_ids( $post_ids );

	return $components;
}

/**
 * Add additional config values for menu items.
 *
 * @param  array    $config The config array for this component.
 * @param  \WP_Post $menu_object  The menu post for this component.
 * @return array
 */
function modify_menu( array $config, \WP_Post $menu_object ) : array {
	$config['classes'] = (array) array_filter( $menu_object->classes );
	return $config;
}
add_filter( 'wp_irving_components_config_menu_item', __NAMESPACE__ . '\modify_menu', 10, 2 );
