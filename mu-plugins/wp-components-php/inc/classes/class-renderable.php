<?php
/**
 * Renderable class.
 *
 * @package WP_Component_PHP
 */

namespace WP_Component\PHP;

/**
 * Renderable.
 */
class Renderable {

	/**
	 * Component instance
	 *
	 * @var array
	 */
	public $component_instance = array();

	/**
	 * Should the partial be output (false) or returned (true).
	 *
	 * @var bool Default false.
	 */
	public $return = false;

	/**
	 * Slug of template to load
	 *
	 * @var bool Default false.
	 */
	public $template_slug = '';

	/**
	 * Class constructor.
	 *
	 * @param \Wp_Component\Component $component_instance Instance of a component (or template) to render
	 */
	public function __construct( $component_instance, $return = false ) {
		$this->component_instance = $component_instance;
		$this->return = $return;
	}

	/**
	 * Load the partial.
	 *
	 * @param string $template_slug Slug of template to load.
	 */
	public function render( $template_slug = 'index' ) {
		$this->template_slug = $template_slug ?? $this->template_slug;

		if ( $this->return ) {
			return $this->get_contents();
		} else {
			$this->require();
		}
	}

	/**
	 * Return the partial, instead of outputting it.
	 *
	 * @return string
	 */
	public function get_contents() {
		ob_start();
		$this->require();
		return ob_get_clean();
	}

	/**
	 * Require the partial
	 *
	 * @return string
	 */
	public function require() {
		$partial = $this->locate_component_partial();

		if ( ! empty( $partial ) ) {
			require( $partial );
		}
	}

	/**
	 * Return the partial, instead of outputting it.
	 *
	 * @return string
	 */
	public function locate_component_partial() {
		$theme_components_path = apply_filters(
			'wp_components_php_component_path',
			get_stylesheet_directory() . '/inc'
		);
		$component_partial_path = '/components/' . $this->component_instance->name . '/template-parts/' . $this->template_slug . '.php';

		if ( defined( 'WP_COMPONENTS_PATH' ) && file_exists( WP_COMPONENTS_PATH . $component_partial_path ) ) {
			require( WP_COMPONENTS_PATH . $component_partial_path );
		} else if ( file_exists( $theme_components_path . $component_partial_path ) ) {
			require( $theme_components_path . $component_partial_path );
		}
	}

	/**
	 * Resolve and render a component's CSS
	 *
	 * @param string $name Component name.
	 */
	public function render_css() {
		$name = $this->component_instance->name;
		$css = apply_filters( 'wp_components_php_resolve_asset', $name, 'css' );

		if ( $name !== $css ) {
			printf(
				'<link rel="stylesheet" href="%1$s" class="%2$s" />',
				esc_url( $css ),
				esc_attr( $name . '-component-css' )
			);
		}
	}

	/**
	 * Resolve and render a component's JS
	 *
	 * @param string $name Component name.
	 */
	public function render_js() {
		$name = $this->component_instance->name;
		$javascript = apply_filters( 'wp_components_php_resolve_asset', $name, 'js' );

		if ( $name !== $javascript ) {
			printf(
				'<script src="%1$s" class="%2$s" type="text/javascript" async></script>',
				esc_url( $javascript ),
				esc_attr( $name . '-component-js' )
			);
		}
	}
}
