<?php
/**
 * Post content.
 *
 * Get the post concept.
 *
 * @todo Add support for template context.
 * @todo Update the output to handle classic HTML and Gutenberg blocks.
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

			$post_content = get_post( $post_id )->post_content ?? '';

			return $component
				->set_config( 'content', html_entity_decode( $post_content ) )
				// Temporarily map this to irving/text so it gets converted to
				// a text dom node upon render.
				->set_name( 'irving/text' );
		},
	]
);
