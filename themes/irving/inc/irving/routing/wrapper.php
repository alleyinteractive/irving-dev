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
 * Apply wrapper components for site contexts.
 *
 * @param  array     $response Endpoint response.
 * @param  \WP_Query $wp_query Endpoint query.
 * @param  string    $context  Endpoint context.
 * @return array Endpoint response.
 */
function wrapper_component_handling( array $response, \WP_Query $wp_query, string $context ) {

	// If not a `site` context, return regular response.
	if ( 'site' !== $context ) {
		return $response;
	}

	// Return response with wrapping components.
	return array_merge(
		[ Component\header() ],
		$response,
		[ Component\footer() ]
	);
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\wrapper_component_handling', 15, 3 );

/**
 * Include the admin bar in all responses.
 *
 * @param  array     $response Endpoint response.
 * @param  \WP_Query $wp_query Endpoint query.
 * @return array Endpoint response.
 */
function admin_bar_component_handling( array $response, \WP_Query $wp_query ) {
	return array_merge(
		[ Component\admin_bar()->parse_query( $wp_query ) ],
		$response
	);
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\admin_bar_component_handling', 12, 2 );
