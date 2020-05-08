<?php
/**
 * Routing.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev;

// Filter the namespace for autoloading.
add_filter(
	'wp_components_theme_components_namespace',
	function () {
		return 'Irving_Dev\Components';
	}
);

// Filter the theme component path for autoloading.
add_filter(
	'wp_components_theme_components_path',
	function ( $path, $class, $dirs, $filename ) {
		// Remove last $dirs entry since we don't nest our components in an extra folder.
		array_pop( $dirs );
		return get_template_directory() . '/components/' . implode( '/', $dirs ) . "/class-{$filename}.php";
	},
	10,
	4
);

/**
 * Execute components based on routing.
 *
 * @param array            $data     Data for response.
 * @param \WP_Query        $wp_query WP_Query object corresponding to this
 *                                   request.
 * @param string           $context  The context for this request.
 * @param string           $path     The path for this request.
 * @param \WP_REST_Request $request  WP_REST_Request object.
 * @return  array Data for response.
 */
function build_components_endpoint(
	array $data,
	\WP_Query $wp_query,
	string $context,
	string $path,
	\WP_REST_Request $request
): array {
	// Build defaults.
	if ( 'site' === $context ) {
		$data['defaults'] = [
			new \WP_Components\Head(),
			new Components\Header\Header(),
			new \WP_Components\Body(),
			new Components\Footer\Footer(),
		];
	}

	// Begin building a head instance for this page.
	$head = new \WP_Components\Head();

	// Build page.
	switch ( true ) {

		/**
		 * Homepage.
		 */
		case '/' === $path:
			// Found a landing page post.
			$head->set_post( $wp_query->post );
			$template = new Components\Templates\Homepage();
			break;

		/**
		 * Article.
		 */
		case $wp_query->is_single():
			$head->set_post( $wp_query->post );
			$template = ( new Components\Templates\Article() )->set_post( $wp_query->post );
			break;

		/**
		* Error page.
		*/
		case $wp_query->is_404():
		default:
			$head->set_query( $wp_query );
			$template = ( new Components\Templates\Error() )->set_query( $wp_query );
			break;
	}

	// Set up context providers.
	$data['providers'] = [];

	// Setup the page data based on routing.
	$data['page'] = $template->to_array()['children'];

	// Unshift the head to the top.
	array_unshift(
		$data['page'],
		apply_filters( 'irving_dev_head', $head )
	);

	return $data;
}
add_filter( 'wp_irving_components_route', __NAMESPACE__ . '\build_components_endpoint', 10, 5 );
