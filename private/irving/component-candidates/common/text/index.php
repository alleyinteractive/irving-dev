<?php
/**
 * Text.
 *
 * Component that serializes as a string for use as a text node.
 *
 * @package Irving_Components
 */

namespace WP_Irving;

use WP_Irving\Component;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Register the component.
 */
\WP_Irving\get_registry()->register_component_from_config( __DIR__ . '/component' );

/**
 * Output the content config value as a text node instead of a component.
 *
 * @param array $component Component as an array.
 * @return array|string
 */
function serialize_text_component( array $component ) {
	if ( 'irving/text' !== $component['name'] ?? '' ) {
		return $component;
	}

	return $component['config']->content ?? '';
}
add_filter( 'wp_irving_serialize_component_array', __NAMESPACE__ . '\serialize_text_component' );
