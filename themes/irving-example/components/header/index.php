<?php
/**
 * Theme header.
 *
 * @package Irving_Components
 */

use WP_Irving\Component;
use function WP_Irving\Templates\locate_template_part;
use function WP_Irving\Templates\prepare_data_from_template;
use function WP_Irving\Templates\traverse_components;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}
/**
 * Register the component and callback.
 */
\WP_Irving\get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function ( Component $component ): Component {

			$template = get_option( 'irving-example-settings' )['templates']['header']['layout'] ?? 'header-left';

			$component->set_children( traverse_components( [ prepare_data_from_template( locate_template_part( $template ) ) ] ) );

			$component->set_name( '' );

			return $component;
		},
	]
);
