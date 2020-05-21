<?php
/**
 * Fragment.
 *
 * Render children in a React fragment or any other HTML tag.
 *
 * @package Irving_Components
 */

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Register the component.
 */
\WP_Irving\get_registry()->register_component_from_config( __DIR__ . '/component' );
