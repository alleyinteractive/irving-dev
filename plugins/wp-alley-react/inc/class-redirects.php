<?php
/**
 * Custom redirects.
 *
 * @package WP_Alley_React
 */

namespace Alley_React;

class Redirects {

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

		add_action( 'fm_submenu_alley_react_redirects', array( $this, 'fm_submenu_redirect_settings' ) );

		// Add submenu
		if ( function_exists( 'fm_register_submenu_page' ) ) {
			fm_register_submenu_page(
				'alley_react_redirects',
				'tools.php',
				__( 'Redirects', 'gr-2017' ),
				__( 'Redirects', 'gr-2017' ),
				'edit_posts'
			);
		}
	}

	/**
	 * Create the FM submenu
	 */
	public function fm_submenu_redirect_settings() {
		$fm = new \Fieldmanager_Group( array(
			'name' => 'alley_react_redirects',
			'add_more_label' => __( 'Add Redirect', 'gr-2017' ),
			'limit' => 50,
			'children' => array(
				'redirect' => new Fieldmanager_Redirect( array(
					'label' => false,
				) ),
			),
		) );
		$fm->activate_submenu_page();
	}

	/**
	 * Generic options endpoint
	 */
	public function get_redirects() {
		$redirects = get_option( 'alley_react_redirects' );

		$cleaned = array();
		if ( ! empty( $redirects ) && is_array( $redirects ) ) {
			foreach ( $redirects as $redirect ) {
				$cleaned[ $redirect['redirect']['from'] ] = $redirect['redirect']['to'];
			}
		}
		return $cleaned;
	}
}

/**
 * Get the endpoint instance.
 *
 * @return Redirects
 */
function redirects() {
	return Redirects::instance();
}

// Initialize the redirects module after theme setup
add_action( 'after_setup_theme', __NAMESPACE__ . '\redirects' );
