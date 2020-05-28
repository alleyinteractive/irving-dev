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

			// Get the post ID from a context provider, or fallback to the global.
			$post_id = $component->get_config( 'post_id' );
			if ( 0 === $post_id ) {
				$post_id = get_the_ID();
			}

			$post_content = apply_filters( 'the_content', str_replace( ']]>', ']]&gt;', get_the_content( null, false, $post_id ) ) );

			return $component
				->set_config( 'content', $post_content )
				// Temporarily map this to irving/text so it gets converted to
				// a text dom node upon render.
				->set_name( 'irving/html' );
		},
	]
);
