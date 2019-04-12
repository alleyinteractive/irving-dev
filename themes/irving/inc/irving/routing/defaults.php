<?php
/**
 * Handle Irving responses for the `site` context. Wrap other responses in
 * global elements for intial page loads.
 *
 * @package Irving
 */

namespace Irving\Components;

use WP_Components;

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
		( new Header() )
			->set_children(
				[
					( new Menu() )->set_menu( 'header-left' )->parse_wp_menu(),
					( new Menu() )->set_menu( 'header-right' )->parse_wp_menu(),
				]
			),
		new \WP_Components\Component( 'body' ),
		new Footer(),
	];

	return $data;
}
add_filter( 'wp_irving_components_route', __NAMESPACE__ . '\wp_irving_default_components', 10, 3 );
