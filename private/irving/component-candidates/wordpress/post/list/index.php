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
use function WP_Irving\Templates\setup_component;
use function WP_Irving\Templates\hydrate_components;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

$wp_irving_post_list_exclude_ids = [];

/**
 * Register the component and callback.
 */
get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function( Component $component ) use ( &$wp_irving_post_list_exclude_ids ): Component {

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
					$query_args['post__not_in'] = $wp_irving_post_list_exclude_ids;
				}

				$query_args['fields'] = 'ids';

				$post_query = new \WP_Query( $query_args );
			}

			// No results.
			if ( ! $post_query->have_posts() ) {
				return $component->set_children( $no_results );
			}

			$wp_irving_post_list_exclude_ids = array_merge( $wp_irving_post_list_exclude_ids, $post_query->posts );

			$items = [];
			foreach ( $post_query->posts as $post_id ) {

				$items[] = [
					'name' => 'irving/post',
					'config' => [
						'post_id' => $post_id,
					],
					'children' => $item,
				];
			}

			$component->set_children( hydrate_components( $items ) );

			// Wrap the children.
			if ( ! empty( $wrapper ) ) {
				$component->set_child( ( setup_component( $wrapper[0] ) )->set_children( $component->get_children() ) );
			}

			$component->prepend_children( hydrate_components( $before ) );
			$component->append_children( hydrate_components( $after ) );

			return $component;
		},
	]
);
