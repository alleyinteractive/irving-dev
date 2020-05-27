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

$irving_post_list_exclude_ids = [];

/**
 * Register the component and callback.
 */
get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function( Component $component ) use ( &$irving_post_list_exclude_ids ): Component {

			global $wp_query;
			$post_query = $wp_query;

			$after      = (array) ( $component->get_config( 'templates' )['after'] ?? [] );
			$wrapper    = (array) ( $component->get_config( 'templates' )['wrapper'] ?? [] );
			$item       = (array) ( $component->get_config( 'templates' )['item'] ?? [] );
			$before     = (array) ( $component->get_config( 'templates' )['before'] ?? [] );
			$no_results = (array) ( $component->get_config( 'templates' )['no_results'] ?? [ 'no results found' ] );

			$query_args = (array) $component->get_config( 'query_args' );

			if ( ! empty( $query_args ) ) {

				if ( wp_validate_boolean( $query_args['exclude'] ?? false ) ) {
					$query_args['post__not_in'] = $irving_post_list_exclude_ids;
				}

				$post_query = new \WP_Query( $query_args );
			}

			// No results.
			if ( ! $post_query->have_posts() ) {
				return $component->set_children( $no_results );
			}

			// Build the post components.
			while ( $post_query->have_posts() ) {
				$post_query->the_post();
				$irving_post_list_exclude_ids[] = get_the_ID();
				$component->append_child(
					( new Component( 'irving/post' ) )
						->set_config( 'post_id', get_the_ID() )
						->append_children( Templates\hydrate_components( $item ) )
				);
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
