<?php
/**
 * Meta.
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
		'callback' => function( Component $meta ) {
			$meta->set_config( 'content', get_post_meta( get_the_ID(), $meta->get_config( 'key' ), true ) );
			$meta->set_name( 'irving/text' );
			return $meta;
		},
	]
);
