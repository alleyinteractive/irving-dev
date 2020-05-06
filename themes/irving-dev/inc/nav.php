<?php
/**
 * Add any nav menu manipulations here
 *
 * @package Irving_Dev
 */

namespace Irving_Dev;

/**
 * Add the `aria-current` attribute to the current nav menu item.
 *
 * @param array   $atts Nav menu item attributes.
 * @param WP_Post $item Nav menu item data object.
 * @return array
 */
function menu_item_attributes( $atts, $item ) {
	$atts['id'] = wp_unique_id( 'menu-link-' );

	if ( $item->current ) {
		$atts['aria-current'] = 'page';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', __NAMESPACE__ . '\menu_item_attributes', 10, 2 );
