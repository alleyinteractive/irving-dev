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
function error_components( \WP_Query $wp_query ) : array {

	// Build array of components.
	$components = [];

	$components[] = Component\Component( 'error' );

	return $components;
}