<?php
/**
 * Site menu item.
 *
 * @package Irving_Components
 */

use WP_Irving\Component;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Register the component and callback.
 */
\WP_Irving\get_registry()->register_component_from_config( __DIR__ . '/component' );
