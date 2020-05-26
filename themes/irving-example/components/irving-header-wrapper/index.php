<?php
/**
 * Theme header.
 *
 * @package Irving_Components
 */

namespace WP_Irving;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}
/**
 * Register the component and callback.
 */
get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function ( Component $component ): Component {

			// Get the template name from site settings.
			$template = get_option( 'irving-example-settings' )['templates']['header']['layout'] ?? 'header-center';

			// Ensure children are component objects.
			$component->hydrate_children();

			// Remove children that aren't the right template.
			foreach ( $component->get_children() as $child ) {
				if ( "template-parts/$template" === $child->get_name() ) {
					$component->set_child( $child );
				}
			}

			return $component;
		},
	]
);
