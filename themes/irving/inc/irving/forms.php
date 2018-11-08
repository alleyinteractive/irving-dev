<?php
/**
 * Irving implementation.
 *
 * @package Irving
 */

namespace Irving;


/**
 * Setup form endpoints and callbacks
 *
 * @param  array $form_endpoints Form endpoint slugs and callback functions.
 * @return array Form endpoints with Conensys forms merged in.
 */
function form_endpoints( $form_endpoints ) {
	$form_endpoints[] = [
		'slug' => 'testform',
		'callback' => [ __NAMESPACE__ . '\Contact_Form', 'get_route_response' ],
	];

	return $form_endpoints;
}
add_filter( 'wp_irving_form_endpoints',  __NAMESPACE__ . '\form_endpoints', 10, 1 );
