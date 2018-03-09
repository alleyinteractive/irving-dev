<?php
/**
 * Custom endpoints.
 *
 * @package WP_Alley_React
 */

namespace Alley_React;

class Endpoints {

	use \Alley_React\Traits\Singleton;

	/**
	 * Route base path
	 *
	 * @var string
	 */
	public $route_base = 'alley-react/v1';

	/**
	 * Initalize this class and setup landing page module
	 */
	function setup() {

		// Resolve CORS issues
		add_filter( 'allowed_http_origin', '__return_true' );
		header( 'Access-Control-Allow-Origin: *' );

		// Register routes
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Generic options endpoint
	 */
	public function register_routes() {
		/**
		 * Options
		 */
		register_rest_route( $this->route_base, '/options/', array(
			'methods' => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'options' ),
		) );

		/**
		 * Landing pages
		 */
		register_rest_route( $this->route_base, '/landing-page/(?P<slug>[a-zA-Z0-9-_]+)', array(
			'methods' => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'landing_page' ),
			'args' => array(
				'slug' => array(
					'validate_callback' => function( $param, $request, $key ) {
						// Is the slug a valid landing page?
						return in_array( $param, landing_pages()->get_landing_pages(), true );
					}
				),
			),
		) );

		/**
		 * Menus
		 * The <mode> of the route will be either "name" (the name of the menu)
		 * or "location" (the location registered with register_nav_menus()).
		 *
		 */
		register_rest_route( $this->route_base, '/menu/(?P<mode>.+)/(?P<value>.+)', array(
			'methods' => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_menu' ),
		) );

		/**
		 * Multiple post types
		 */
		register_rest_route( $this->route_base, '/multiple/', array(
			'methods' => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_multiple' ),
		) );
	}

	/**
	 * Array for the options.
	 *
	 * @return array
	 */
	public function options() {

		// Build an array of options
		$options = array(
			'info' => array(
				'title' => get_bloginfo( 'name' ),
			),
			'postTypes' => apply_filters( 'alley_react_post_types', array_keys( get_post_types() ) ),
			'taxonomies' => apply_filters( 'alley_react_taxonomies', array_keys( get_taxonomies() ) ),
			'menus' => apply_filters( 'alley_react_menus', get_registered_nav_menus() ),
			'landingPages' => landing_pages()->get_landing_pages(),
			'redirects' => redirects()->get_redirects(),
		);

		return apply_filters( 'alley_react_options', $options );
	}

	public function register_fields() {

		// Build an array of options
		$options = array(
			'info' => array(
				'title' => get_bloginfo( 'name' ),
			),
			'postTypes' => apply_filters( 'alley_react_post_types', array_keys( get_post_types() ) ),
			'taxonomies' => apply_filters( 'alley_react_taxonomies', array_keys( get_taxonomies() ) ),
			'menus' => apply_filters( 'alley_react_menus', get_registered_nav_menus() ),
			'landingPages' => landing_pages()->get_landing_pages(),
		);

		return apply_filters( 'alley_react_options', $options );
	}

	/**
	 * Get a landing page option.
	 *
	 * @return array
	 */
	public function landing_page( $data ) {
		$results = array(
			'modules' => array(),
		);
		$key = "landing_pages_{$data['slug']}";
		$landing_page = get_option( $key );
		if ( ! empty( $landing_page ) ) {
			$results['modules'] = $landing_page;
		}

		return apply_filters( "alley_react_endpoint_landing_page_{$data['slug']}", $results );
	}

	/**
	 * Get the menu data.
	 *
	 * @param \WP_REST_Request $request REST request data.
	 *
	 * @return mixed A menu object.
	 */
	public function get_menu( $request ) {
		if ( 'name' === $request['mode'] ) {
			// Use menu slug.
			$requested_menu_slug = $request['value'];
		} else {
			// Use menu location.
			$theme_locations = get_nav_menu_locations();
			if ( empty( $theme_locations[ $request['value'] ] ) ) {
				return rest_ensure_response( false );
			}
			$menu_obj            = get_term( $theme_locations[ $request['value'] ], 'nav_menu' );
			if ( $menu_obj instanceof \WP_Term ) {
				$requested_menu_slug = $menu_obj->name;
			}
		}

		if ( empty( $requested_menu_slug ) ) {
			return rest_ensure_response( false );
		}

		$menu_items_from_slug = wp_get_nav_menu_items( $requested_menu_slug );

		return rest_ensure_response( $menu_items_from_slug );
	}

	/**
	 * Get multiple post types.
	 *
	 * @param \WP_REST_Request $request REST request data.
	 *
	 * @return array
	 */
	public function get_multiple( $request ) {
		$page       = $request['page'] ? intval( $request['page'] ) : 1;
		$per_page   = $request['per_page'] ? intval( $request['per_page'] ) : 10;
		$post_types = $request['type'] ? $request['type'] : array();
		if ( empty( $post_types ) || ! is_array( $post_types ) ) {
			$post_types = apply_filters( 'alley_react_multiple', array() );
		}

		$args = array(
			'post_type'      => $post_types,
			'paged'          => $page,
			'posts_per_page' => $per_page,
		);

		$query = new \WP_Query( $args );
		$posts = $query->posts;
		$response = new \WP_REST_Response( $posts );
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return rest_ensure_response( $response );
	}
}

/**
 * Get the endpoint instance.
 *
 * @return Endpoints
 */
function endpoints() {
	return Endpoints::instance();
}

// Initialize the landing pages module after theme setup
add_action( 'after_setup_theme', __NAMESPACE__ . '\endpoints' );
