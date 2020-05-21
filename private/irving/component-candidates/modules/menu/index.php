<?php
/**
 * Menu.
 *
 * Output links as menu items.
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
		'callback' => function( Component $component ) {

			// Get the menu items for a given location.
			$menu_items = (array) wp_get_nav_menu_items(
				get_nav_menu_locations()[ $component->get_config( 'location' ) ] ?? 0
			);

			// Recursively build the children components.
			$component->set_children( convert_menu_to_components( $menu_items ) );

			return $component;
		},
	]
);

/**
 * Recursively build a menu component with all the menu items.
 *
 * @param array   $menu_items Array of \WP_Post menu items.
 * @param integer $parent_id  Parent ID of the menu item we're iterating on.
 * @return array
 */
function convert_menu_to_components( array $menu_items, $parent_id = 0 ) {

	$menu = [];

	foreach ( $menu_items as $menu_item ) {

		// Convert the menu class instance into a simpler array format.
		$menu_item = new Component(
			'irving-modules/menu-item',
			[
				'config' => [
					'attribute' => (string) $menu_item->attr_title,
					'classes'   => array_filter( (array) $menu_item->classes ),
					'id'        => absint( $menu_item->ID ),
					'parent_id' => absint( $menu_item->menu_item_parent ),
					'target'    => (string) $menu_item->target,
					'title'     => (string) $menu_item->title,
					'url'       => (string) $menu_item->url ?? get_the_permalink( $menu_item ),
				]
			]
		);

		// If the parent ID matches this loop, recursively build the children.
		if ( $menu_item->get_config( 'parent_id' ) === $parent_id ) {
			$menu_item->set_children( convert_menu_to_components( $menu_items, $menu_item->get_config( 'id' ) ) );
			$menu[] = $menu_item;
		}
	}

	return $menu;
}
