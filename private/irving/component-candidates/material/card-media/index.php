<?php
/**
 * Material card media.
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

			// An image has already been set.
			if ( ! empty( $component->get_config( 'image' ) ) ) {
				return $component;
			}

			// Get the post ID from a context provider, or fallback to the global.
			$post_id = $component->get_config( 'post_id' );
			if ( 0 === $post_id ) {
				$post_id = get_the_ID();
			}

			// Get and validate image url.
			$image_url = get_the_post_thumbnail_url( $post_id );
			if ( empty( $image_url ) ) {
				$component->set_config( 'image', 'https://source.unsplash.com/random/300x300' );
				return $component;
			}

			$component->set_config( 'image', $image_url );

			return $component;
		},
	]
);
