<?php
/**
 * Single post routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Post components.
 *
 * @param  array    $response Endpoint response.
 * @param  WP_Query $query    Endpoint query.
 * @param  string   $context  Endpoint context.
 * @return array Endpoint response.
 */
function homepage_route( $response, $query, $context, $path ) {
	if ( $path === '/' || $path === '' ) {
		$response = [
			Component\admin_bar(),
			Component\post_wrapper(
				[
					'children' => homepage_components( $query ),
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
 * @param  WP_Query $wp_query WP_Query object.
 */
function modify_homepage( $wp_query ) {
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
	// $components[] = Component\jumbotron()
	// 	->set_to_post( array_shift( $wp_query->posts ) );

	// /**
	//  * Content Grid.
	//  */
	// $components[] = Component\content_grid()
	// 	->set_config( 'title', __( 'Content Grid', 'irving-dev' ) )
	// 	->set_children_by_post_ids( $wp_query->posts );


	$components[] = Component\component(
		[
			'name' => 'new-component',
			'children' => [
				'jumbotron' => Component\jumbotron(),
			],
		]
	);

	return $components;
}
