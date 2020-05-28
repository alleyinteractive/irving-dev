<?php
/**
 * Post byline.
 *
 * Get the post byline.
 *
 * @todo Add support for CAP and Byline Manager plugins.
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

			$component->set_config(
				'timestamp',
				get_the_date( $component->get_config( 'timestamp_format' ), $post_id )
			);

			$post = get_post( $post_id );

			$component
				->append_child(
					( new Component( 'irving/link' ) )
						->set_config( 'href', get_author_posts_url( $post->post_author ) )
						->set_child( get_the_author_meta( 'display_name', $post->post_author ) )
				);

			return $component;
		},
	]
);
