<?php
/**
 * Post lists.
 *
 * Iterate through a WP_Query's posts.
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
		'callback' => function( Component $component ): Component {

			global $wp_query;
			$post_query = $wp_query;

			$after      = (array) ( $component->get_config( 'templates' )['after'] ?? [] );
			$wrapper    = (array) ( $component->get_config( 'templates' )['wrapper'] ?? [] );
			$item       = (array) ( $component->get_config( 'templates' )['item'] ?? [] );
			$before     = (array) ( $component->get_config( 'templates' )['before'] ?? [] );
			$no_results = (array) ( $component->get_config( 'templates' )['no_results'] ?? [ 'no results found' ] );

			$query_args = (array) $component->get_config( 'query_args' );
			if ( ! empty( $query_args ) ) {
				$post_query = new \WP_Query( $query_args );
			}

			// Set the `wp_query` for the data provider/consumers.
			$component->set_config( 'wp_query', $post_query );

			// No results.
			if ( ! $post_query->have_posts() ) {
				return $component->set_children( $no_results );
			}

			// Build the post components.
			while ( $post_query->have_posts() ) {
				$post_query->the_post();
				$component->append_children( Templates\hydrate_components( $item ) );
			}
			wp_reset_postdata();

			// Wrap the children.
			if ( ! empty( $wrapper ) ) {
				$component->set_child( ( Templates\setup_component( $wrapper[0] ) )->set_children( $component->get_children() ) );
			}

			$component->prepend_children( Templates\hydrate_components( $before ) );
			$component->append_children( Templates\hydrate_components( $after ) );

			return $component;
		},
	]
);
