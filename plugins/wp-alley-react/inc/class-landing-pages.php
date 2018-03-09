<?php
/**
 * Landing page management.
 *
 * @package WP_Alley_React
 */

namespace Alley_React;

class Landing_Pages {

	use \Alley_React\Traits\Singleton;

	/**
	 * Landing page module configuration
	 *
	 * @var array
	 */
	public $config = [];

	/**
	 * Landing pages
	 *
	 * @var array
	 */
	public $landing_pages = [];

	/**
	 * Initalize this class and setup landing page module
	 */
	function setup() {

		if ( ! defined( 'FM_VERSION' ) ) {
			add_action( 'admin_notices', array( $this, 'missing_fm' ) );
			return;
		}

		// Get and store landing pages
		$this->landing_pages = apply_filters( 'alley_react_landing_pages', $this->landing_pages );

		// Parse the module configuration
		$this->parse_config();

		// Create the FM submenus
		$this->create_submenus();

		// Validate that we want to create landing pages
		if ( ! empty( $this->landing_pages ) ) {

			// Create top level admin menu
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			// Remove default submenu created
			add_action( 'admin_menu', array( $this, 'remove_default_submenu' ), 20 );
		}
	}

	/**
	 * Sets default config values
	 */
	public function parse_config() {

		// Allow config to be customized
		$this->config = apply_filters( 'alley_react_landing_page_config', $this->config );

		// Setup admin menu defaults, and override using config
		$this->config = wp_parse_args( $this->config, array(
			'page_title' => __( 'Landing Pages', 'alley-react' ),
			'menu_title' => __( 'Landing Pages', 'alley-react' ),
			'capability' => 'manage_options',
			'menu_slug' => 'landing_pages',
			'function' => '__return_false',
			'icon_url' => 'dashicons-layout',
			'position' => 4,
		) );
	}

	/**
	 * Creates the top level admin menu.
	 */
	public function admin_menu() {

		// Turn the config into a bunch of variables
		extract( $this->config );

		// Add menu page
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}

	/**
	 * Get landing pages array
	 */
	public function get_landing_pages() {
		return array_keys( $this->landing_pages );
	}

	/**
	 * Create the submenus from the landing page filter
	 */
	public function create_submenus() {

		// Add submenu pages for each landing page
		foreach ( $this->landing_pages as $key => $landing_page_config ) {

			if ( function_exists( 'fm_register_submenu_page' ) ) {

				// Build group name
				$group_name = $this->config['menu_slug'] . '_' . $key;

				// Default parent slug
				$parent_slug = $this->config['menu_slug'];

				if ( ! empty( $landing_page_config['parent_slug'] ) ) {
					$parent_slug = $landing_page_config['parent_slug'];
				}

				// Register submenu page
				fm_register_submenu_page(
					$group_name,
					$parent_slug,
					$landing_page_config['title'],
					$landing_page_config['title'],
					'manage_options'
				);

				// Only activate the submenu we're on
				$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : null;

				// If page is null, or we're on that page
				if ( $page === $group_name || null === $page ) {
					$this->activate_submenu_page( array(
						'name' => $group_name,
						'tabbed' => ( isset( $landing_page_config['tabbed'] ) ) ? $landing_page_config['tabbed'] : false,
						'fields' => $landing_page_config['fields'],
					) );
				}
			}
		}
	}

	/**
	 * Setup the submenu fields
	 *
	 * @param  string $options {
	 *     @type string $name    What is the name of the landing page
	 *     @type string $tabbed  Should the landing page be tabbed
	 *     @type array  $fields  An array of FM fields to render
	 * }
	 */
	public function activate_submenu_page( $options ) {

		// Extract options
		extract( $options );

		// Build base FM group
		$fm = new \Fieldmanager_Group( array(
			'name' => $name,
			'tabbed' => $tabbed,
			'children' => $fields,
		) );
		$fm->activate_submenu_page();
	}

	/**
	 * Removes the top level submenu automatically created by `add_menu_page()`
	 */
	public function remove_default_submenu() {

		// Remove top level submenu from landing pages
		global $submenu;
		$remove_top_levels = [
			$this->config['menu_slug'],
		];

		// Loop through and remove any submenus that match our array
		foreach ( $remove_top_levels as $slug ) {
			if ( isset( $submenu[ $slug ] ) ) {
				array_shift( $submenu[ $slug ] );
			}
		}
	}

	/**
	 * Display error message when Fieldmanager is not available
	 */
	public function missing_fm() {
		$message = __( 'Fieldmanager is required for the Alley React Landing Pages module.', 'alley-react' );
		printf( '<div class="notice notice-error"><p>%1$s</p></div>', esc_html( $message ) );
	}
}

/**
 * Get the landing page instance.
 *
 * @return Landing_Pages
 */
function landing_pages() {
	return Landing_Pages::instance();
}

// Initialize the landing pages module after theme setup
add_action( 'init', __NAMESPACE__ . '\landing_pages', 11 );
