<?php
/**
 * Material link.
 *
 * @package Irving_Components
 */

namespace WP_Irving;

use WP_Irving\Component;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Register the component and callback.
 */
get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function( Component $component ): Component {

			// A link has already been set.
			if ( ! empty( $component->get_config( 'href' ) ) ) {
				return $component;
			}

			$post_id = get_the_ID();

			return $component->set_config( 'href', get_the_permalink( $post_id ) );
		},
	]
);
