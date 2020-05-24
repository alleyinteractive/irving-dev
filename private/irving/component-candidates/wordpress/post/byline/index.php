<?php
/**
 * Post byline.
 *
 * Get the post byline.
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
			$post_id = get_the_ID();

			$component->set_config(
				'timestamp',
				get_the_date( $component->get_config( 'timestamp_format' ), $post_id )
			);

			$post = get_post( $post_id );

			$component->append_child(
				( new Component( 'irving/text' ) )
					->set_config( 'content', get_the_author_meta( 'display_name', $post->post_author ) )
			)->append_child(
				( new Component( 'irving/text' ) )
					->set_config( 'content', 'James Burke' )
			// )->append_child(
			// 	( new Component( 'irving/text' ) )
			// 		->set_config( 'content', get_the_author_meta( 'display_name', 'Owie Stoie' ) )
			);

			return $component;
		},
	]
);
