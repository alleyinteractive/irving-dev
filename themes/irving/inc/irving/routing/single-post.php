<?php
/**
 * Single post routing for Irving.
 *
 * @package Irving
 */

namespace Irving;

use WP_Irving\Component;

function post_components( $response, $query, $context ) {
	if ( $query->is_single() || $query->is_page() ) {
		return [
			Component\admin_bar()->parse_query( $query )->json(),
			Component\post_wrapper(
				[
					'children' => [
						Component\component(
							[
								'name' => 'jumbotron',
							]
						),
					]
				]
			)
		];
	}
}
add_action( 'wp_irving_route', __NAMESPACE__ . '\post_components', 10, 3 );
