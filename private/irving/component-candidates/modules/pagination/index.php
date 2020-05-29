<?php
/**
 * Pagination.
 *
 * Pagination ui for an archive.
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

			// Get and validate the query.
			$wp_query = $component->get_config( 'wp_query' );
			if ( ! $wp_query instanceof \WP_Query ) {
				return $component;
			}

			$pagination_markup = get_pagination_markup_by_query( $wp_query );
			$children          = get_pagination_components_from_markup( $pagination_markup, $component->get_config( 'query_vars_to_remove' ) );

			return $component->set_children( $children );
		},
	]
);

/**
 * Build an array of link and span components from `paginate_links()` markup.
 *
 * @param string $markup HTML markup.
 * @return array Components.
 */
function get_pagination_components_from_markup( string $markup, array $query_args_to_remove = [] ): array {

	$pagination_components = [];

	// Create a DOMDocument and parse it for all tags.
	$dom = new \DOMDocument();
	@$dom->loadHTML( $markup ); // phpcs:ignore
	$nodes = $dom->getElementsByTagName( '*' );
	foreach ( $nodes as $node ) {

		// Skip non-span/a tags.
		if ( ! in_array( $node->tagName, [ 'span', 'a' ], true ) ) {
			continue;
		}

		// Add this node as a new component.
		$pagination_components[] = ( new Component( 'span' ) )

			// Set the content as a child.
			->set_child( $node->nodeValue )

			// Do a callback so it's obvious that all the logic is happening to
			// this component.
			->callback(
				function( $component ) use ( $node, $query_args_to_remove ) {

					// Set config from node attributes.
					foreach ( $node->attributes as $attribute ) {
						$component->set_config( $attribute->localName, $attribute->nodeValue );
					}

					// No link, so leave it alone.
					if ( empty( $component->get_config( 'href' ) ) ) {
						return $component;
					}

					return $component
						// Change to a link component.
						->set_name( 'irving/link' )

						// Remove query args from the lik.
						->set_config(
							'href',
							remove_query_arg(
								$query_args_to_remove,
								$component->get_config( 'href' )
							)
						);
				}
			);
	}

	return $pagination_components;
}

/**
 * Get the markup for pagination UI in the context of a WP_Query object.
 *
 * @param \WP_Query|null $pagination_query    \WP_Query context.
 * @param array          $paginate_links_args Optional. Additional arguments
 *                                            for `paginate_links()`.
 * @return string Pagination markup.
 */
function get_pagination_markup_by_query( ?\WP_Query $pagination_query = null, array $paginate_links_args = [] ): string {

	// Get global query.
	global $wp_query;

	// Store this so we can temporarily override the global.
	$global_wp_query = $wp_query;

	// Use our query instead of the global.
	if ( ! is_null( $pagination_query ) ) {
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride
		$wp_query = $pagination_query;
	}

	// Get the pagination markup.
	$markup = paginate_links(
		wp_parse_args(
			$paginate_links_args,
			[
				'base' => REST_API\Components_Endpoint::$irving_path . '%_%',
			]
		)
	);

	// Reset the global.
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride
	$wp_query = $global_wp_query;

	return (string) $markup;
}
