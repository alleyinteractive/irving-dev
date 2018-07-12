<?php
/**
 * Handle Irving responses for the `site` context. Wrap other responses in
 * global elements for intial page loads.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Setup defaults for the app.
 *
 * @param Array            $data     Data for this response.
 * @param \WP_Query        $wp_query WP_Query object corresponding to this
 *                                   request.
 * @param string           $context  The context for this request.
 * @param string           $path     The path for this request.
 * @param \WP_REST_Request $request  WP_REST_Request object.
 * @return array Endpoint response.
 */
function routing( array $data, \WP_Query $wp_query, string $context, string $path, \WP_REST_Request $request ) : array {

	// Build array of components.
	$components = [];

	// Get components based on path and $wp_query.
	switch ( true ) {

		// Homepage.
		case '' === $path:
		case '/' === $path:
			$components = homepage_components( $wp_query );
			break;

		// Single post type.
		case $wp_query->is_single():
			$components = post_components( $wp_query );
			break;

		case $wp_query->is_archive():
			$components = archive_components( $wp_query );
			break;

		// Errors.
		default:
			$components = [
				new Component\Component( 'error' ),
			];

			// Apply 404 status.
			add_filter( 'wp_irving_components_route_status', function( $status ) {
				return 404;
			} );
	}

	// Return the full data.
	$data['page'] = [
		( new Component\Admin_Bar() )->parse_query( $wp_query )->to_array(),
		( new Component\Component(
			'body',
			[ 'body__wrapper' ],
			$components
		) )->to_array(),
	];

	return $data;
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\routing', 10, 5 );
