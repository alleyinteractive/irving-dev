<?php
/**
 * Site bloginfo.
 *
 * Easily call the bloginfo() method.
 *
 * @package Irving_Components
 *
 * @see https://developer.wordpress.org/reference/functions/get_bloginfo/
 */

namespace WP_Irving;

use WP_Irving\Component;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Register the component and callback.
 */
\WP_Irving\get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function( Component $component ): Component {
			// Set `content` and rename to irving/text so it becomes a text
			// node upon output.
			return $component
				->set_config( 'content', (string) get_bloginfo( $component->get_config( 'show' ) ) )
				->set_name( 'irving/text' );
		},
	]
);
