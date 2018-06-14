<?php
/**
 * Setup wrapper for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Apply wrapper components for site contexts.
 *
 * @param  array    $response Endpoint response.
 * @param  WP_Query $query    Endpoint query.
 * @param  string   $context  Endpoint context.
 * @return array Endpoint response.
 */
function wrapper_component_handling( $response, $query, $context ) {

	// If not a `site` context, return regular response.
	if ( 'site' !== $context ) {
		return $response;
	}

	// Return wrapper components as well.
	return Component\component(
		[
			'name'     => 'site',
			'children' => [
				Component\header(),
				$response,
				Component\footer(),
			],
		]
	);
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\wrapper_component_handling', 15, 3 );

