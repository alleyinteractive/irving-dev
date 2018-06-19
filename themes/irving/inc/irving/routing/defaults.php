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
 * Return default components.
 *
 * @param Array     $data     Data for this response.
 * @param \WP_Query $wp_query WP_Query object corresponding to this
 *                            request.
 * @param string    $context  The context for this request.
 * @return array Endpoint response.
 */
function wp_irving_default_components( array $data, \WP_Query $wp_query, string $context ) : array {

	// If not a `site` context, return regular response.
	if ( 'site' !== $context ) {
		return $data;
	}

	$data['defaults'] = [
		new Component\Header(),
		new Component\Admin_Bar(),
		new Component\Component( 'body' ),
		new Component\Footer(),
	];

	return $data;
}
add_action( 'wp_irving_components_route', __NAMESPACE__ . '\wp_irving_default_components', 10, 3 );
