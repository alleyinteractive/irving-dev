<?php
/**
 * Post excerpt.
 *
 * Get the post excerpt.
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

			// @todo setup the context.
			$post_id = get_the_ID();

			return $component
				->set_config( 'content', html_entity_decode( get_the_excerpt( $post_id ) ) )
				->set_name( 'irving/text' );
		},
	]
);
