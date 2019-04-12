<?php
/**
 * Single post routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

/**
 * Return the components for the homepage.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function homepage_components( \WP_Query $wp_query ) : array {
	// Get only post ids.
	$post_ids = wp_list_pluck( $wp_query->posts, 'ID' );

	// Build array of components.
	$components = [];

	$components[] = ( new \WP_Components\Image() )
		->set_post_id( array_shift( $post_ids ) )
		->set_config_for_size( 'feature', true );

	/**
	 * Jumbotron.
	 *
	 * Use the first post in the query.
	 */
	$components[] = ( new Components\Jumbotron() )
		->set_post( array_shift( $post_ids ) );

	/**
	 * Jumbotron using a term.
	 *
	 * Use the first post in the query.
	 */
	$components[] = ( new Components\Jumbotron() )
		->set_term( get_term_by( 'slug', 'uncategorized', 'category' ) );

	/**
	 * Content Grid.
	 */
	$components[] = ( new Components\Content_Grid() )
		->set_config( 'title', __( 'Content Grid', 'irving-dev' ) );

	return $components;
}

/**
 * Return the components for a post.
 *
 * @param  \WP_Query $wp_query WP_Query for this route.
 * @return array Components.
 */
function post_components( \WP_Query $wp_query ) : array {
	// Build array of components.
	$components = [
		( new Component\Content() )
			->set_post( $wp_query->post ),
	];

	return $components;
}

/**
 * Add additional config values for menu items.
 *
 * @param  array    $config The config array for this component.
 * @param  \WP_Post $menu_object  The menu post for this component.
 * @return array
 */
function modify_menu( array $config, \WP_Post $menu_object ) : array {
	$config['classes'] = (array) array_filter( $menu_object->classes );
	return $config;
}
add_filter( 'wp_irving_components_config_menu_item', __NAMESPACE__ . '\modify_menu', 10, 2 );
