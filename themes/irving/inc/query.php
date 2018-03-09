<?php
/**
 * One-off query modifications and manipulations (e.g. through pre_get_posts).
 * Modifications tied to a larger features should reside with the rest of the
 * code for that feature.
 *
 * @package Irving
 */

namespace Irving;

/**
 * Add custom query vars.
 *
 * @param array $vars Array of current query vars.
 * @return array $vars Array of query vars.
 */
function query_vars( $vars ) {
	// Add a query var to enable hot reloading.
	$vars[] = 'irving-dev';

	return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\query_vars' );
