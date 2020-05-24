<?php
/**
 * Post featured media.
 *
 * Display the post featured media.
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

			$post_id = $component->get_config( 'post_id' ) ?? get_the_ID();

			// Get and validate image url.
			$image_url = get_the_post_thumbnail_url( $post_id );
			if ( empty( $image_url ) ) {
				return $component;
			}

			$component->append_child(
				 ( new Component( 'material/card-content' ) )
				 	->set_config( 'gutter_bottom', true )
				 	->set_child(
				 		( new Component( 'material/card-media' ) )
				 			->set_config( 'image', $image_url )
				 			->set_config( 'style', [ 'height' => '450px' ] )
				 	)
			);

			$caption = wp_get_attachment_caption( get_post_thumbnail_id( $post_id ) );
			if ( ! empty( $caption ) ) {
				$component->append_child(
					( new Component( 'material/typography' ) )
						->set_config( 'color', 'textSecondary' )
						->set_config( 'variant', 'body2' )
						->set_config( 'component', 'p' )
						->append_child( $caption )
				);
			}
			return $component;
		},
	]
);
