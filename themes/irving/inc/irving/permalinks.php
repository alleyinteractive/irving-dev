<?php
/**
 * Setup Irving redirects and other url related helpers.
 *
 * @package Irving
 */

namespace Irving;

/**
 * Convert any URL to an app url.
 *
 * @param  string $url Old URL.
 * @return string      App URL.
 */
function convert_wp_url_to_app_url( $url ) {

	// Ensure we have an app url.
	if ( defined( 'IRVING_APP_URL' ) && IRVING_APP_URL ) {

		// Parse url.
		$url_parts = wp_parse_url( $url );
		if ( isset( $url_parts['path'] ) ) {
			$url = IRVING_APP_URL . $url_parts['path'];
		} else {
			$url = IRVING_APP_URL;
		}
	}
	return $url;
}

/**
 * Modify post links to use the app domain.
 *
 * @param  string  $permalink Old WP permalink.
 * @param  WP_Post $post      Post object.
 * @return string             New app permalink.
 */
function modify_permalinks( $permalink, $post ) {
	// Hardcode `/blog/` to post permalinks.
	if (
		$post instanceof \WP_Post &&
		'post' === $post->post_type &&
		(
			'publish' === $post->post_status ||
			'archiveless' === $post->post_status
		)
	) {
		$permalink = str_replace( home_url(), home_url( '/blog' ), $permalink );
	}
	return convert_wp_url_to_app_url( $permalink );
}
add_filter( 'post_link', __NAMESPACE__ . '\modify_permalinks', 10, 2 );
add_filter( 'page_link', __NAMESPACE__ . '\modify_permalinks', 10, 2 );
add_filter( 'post_type_link', __NAMESPACE__ . '\modify_permalinks', 10, 2 );
