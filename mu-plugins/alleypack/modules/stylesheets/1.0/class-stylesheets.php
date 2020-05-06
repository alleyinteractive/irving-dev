<?php
/**
 * Stylesheets class file
 *
 * @copyright 2014-2019 Alley Interactive
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package Alleypack
 */

namespace Alleypack;

/**
 * Template partials controller.
 */
class Stylesheets {
	/**
	 * JSON for classnames.
	 *
	 * @var integer
	 */
	private $classname_json_filepath = false;

	/**
	 * Full classname manifest.
	 *
	 * @var array
	 */
	public $classname_manifest = [];

	/**
	 * Currently active stylesheet name.
	 *
	 * @var string
	 */
	public $current_stylesheet;

	/**
	 * Currently active stylesheet classnames array.
	 *
	 * @var array
	 */
	public $current_stylesheet_classnames = [];

	/**
	 * Holds references to the singleton instances.
	 *
	 * @var array
	 */
	private static $instance;

	/**
	 * Save the previous stylesheet in case we need to restore it.
	 *
	 * @var string
	 */
	public $preserved_stylesheet;

	/**
	 * Unused.
	 */
	private function __construct() {
		// Don't do anything, needs to be initialized via instance() method.
	}

	/**
	 * Get an instance of the class.
	 *
	 * @return Partials
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	/**
	 * Set filepath of classnames manifest and read it in.
	 *
	 * @param mixed $json_filepath The JSON file path.
	 * @return void
	 */
	public function setup( $json_filepath = false ) {
		if ( empty( $json_filepath ) ) {
			$this->set_json_classname_filepath( get_stylesheet_directory() . '/classnames.json' );
		} else {
			$this->set_json_classname_filepath( $json_filepath );
		}

		$this->classname_manifest = $this->get_classname_manifest();

		// Don't print the JSON for AMP pages.
		if ( ! ( function_exists( '\is_amp_endpoint' ) && \is_amp_endpoint() ) ) {
			$this->print_json();
		}

		add_action( 'ai_partials_before_load_template', [ $this, 'before_load_template' ], 10, 1 );
		add_action( 'ai_partials_after_load_template', [ $this, 'after_load_template' ], 10, 1 );
	}

	/**
	 * Set stylesheet to use for this context.
	 *
	 * @param  string $stylesheet Stylesheet to get classnames from.
	 * @return void
	 */
	public function use_stylesheet( $stylesheet ) {
		$this->current_stylesheet = $stylesheet;
		if ( ! empty( $this->classname_manifest[ $stylesheet ] ) ) {
			$this->current_stylesheet_classnames = $this->classname_manifest[ $stylesheet ];
		} else {
			$this->current_stylesheet_classnames = [];
		}
	}

	/**
	 * Output classname mappings as JSON for use in javascript components.
	 *
	 * @return void
	 */
	private function print_json() {
		$camel_mapping = [];
		foreach ( $this->classname_manifest as $stylesheet => $classnames ) {
			$camel_classnames = [];
			foreach ( $classnames as $classname => $local ) {
				$camel_classnames[ $this->camel_case( $classname ) ] = $local;
			}
			$camel_mapping[ $stylesheet ] = $camel_classnames;
		}

		printf( '<script class="cssmodules-class-mappings" type="text/javascript">window.cssModulesClassnames = %1$s</script>', wp_json_encode( $camel_mapping ) );
	}

	/**
	 * Convert a hyphenated string to camelCase
	 *
	 * @param  string $string String to convert to camelCase.
	 * @return string
	 */
	private function camel_case( $string ) {
		$words           = explode( '-', $string );
		$uppercase_words = array_map( 'ucfirst', $words );
		return lcfirst( implode( '', $uppercase_words ) );
	}

	/**
	 * Get a localized classname from this template part's stylesheet
	 *
	 * @param  string $classname The classname to get.
	 * @return string            The localized version of the provided classname
	 *                           if it exists, otherwise the unmodified classname.
	 */
	public function get_classname( $classname ) {
		if ( array_key_exists( $classname, $this->current_stylesheet_classnames ) ) {
			$classes             = $this->current_stylesheet_classnames[ $classname ];
			$composed_class_list = array_map( 'sanitize_html_class', explode( ' ', $classes ) );

			return implode( ' ', $composed_class_list );
		}

		return $classname;
	}

	/**
	 * Get an array of classnames,
	 *
	 * @param string[] $static_classes  Indexed array of classes to merge.
	 * @param array    $dynamic_classes Associative array of classes.
	 *                                  Keys are classes, values are booleans
	 *                                  determining if that class should print.
	 * @param string   $stylesheet      Optional. Stylesheet to get classname from.
	 *                                  If not provided, will use $this->current_stylesheet.
	 * @return array                    Array of valid classnames
	 */
	public function get_classnames( array $static_classes, array $dynamic_classes = [], $stylesheet = false ) {
		$output_classes = [];

		// If explicit stylesheet name is provided, preserve currently set stylesheet.
		if ( $stylesheet ) {
			$this->preserved_stylesheet = $this->current_stylesheet;
			$this->use_stylesheet( $stylesheet );
		}

		// Loop through static classes and add them to output array.
		if ( ! empty( $static_classes ) ) {
			foreach ( $static_classes as $classname ) {
				$output_classes[] = $this->get_classname( $classname );
			}
		}

		// Loop through dynamic classes, evaluate value, and add to output array if true.
		if ( ! empty( $dynamic_classes ) ) {
			foreach ( $dynamic_classes as $classname => $should_print ) {
				if ( $should_print ) {
					$output_classes[] = $this->get_classname( $classname );
				}
			}
		}

		// If explicit stylesheet name is provided, restore currently set stylesheet.
		if ( $stylesheet && ! empty( $this->preserved_stylesheet ) ) {
			$this->use_stylesheet( $this->preserved_stylesheet );
			$this->preserved_stylesheet = '';
		}

		return $output_classes;
	}

	/**
	 * Get a localized classname from this template part's stylesheet.
	 *
	 * @param string $path set directory/location for json classname manifests.
	 * @return void
	 */
	public function set_json_classname_filepath( $path ) {
		if ( 0 === validate_file( $path ) ) {
			$this->classname_json_filepath = $path;
		}
	}

	/**
	 * Get a localized classname from this template part's stylesheet.
	 *
	 * @return array
	 */
	public function get_classname_manifest() {
		if ( file_exists( $this->classname_json_filepath ) ) {
			ob_start();
			include $this->classname_json_filepath;
			return json_decode( ob_get_clean(), true );
		}

		return [];
	}

	/**
	 * Get the stylesheet argument passed to ai_partial.
	 *
	 * @param array $args Options for loading the partial.
	 * @return string     The stylesheet argument value, if any.
	 */
	public function get_partials_stylesheet_arg( $args ) {
		/**
		 * When calling `ai_partial` directly, the 'stylesheet' index will be in
		 * the $args root. Otherwise 'stylesheet' will be in $args['variables'].
		 */
		if ( ! empty( $args['stylesheet'] ) ) {
			return $args['stylesheet'];
		} elseif ( ! empty( $args['variables']['stylesheet'] ) ) {
			return $args['variables']['stylesheet'];
		}

		return '';
	}

	/**
	 * Preserve the current stylesheet and instantiate the stylesheet passed to ai_partial.
	 * @see ai_partials_before_load_template
	 *
	 * @param array $args Options for loading the partial.
	 */
	public function before_load_template( $args ) {
		$partials_stylesheet_arg = $this->get_partials_stylesheet_arg( $args );

		if ( ! empty( $partials_stylesheet_arg ) ) {
			$this->preserved_stylesheet = $this->current_stylesheet;
			$this->use_stylesheet( $partials_stylesheet_arg );
		}
	}

	/**
	 * When finished rendering the partial, reset the stylesheet to what it was before.
	 * @see ai_partials_after_load_template
	 *
	 * @param array $args Options for loading the partial.
	 */
	public function after_load_template( $args ) {
		$partials_stylesheet_arg = $this->get_partials_stylesheet_arg( $args );

		if ( ! empty( $partials_stylesheet_arg ) && ! empty( $this->preserved_stylesheet ) ) {
			$this->use_stylesheet( $this->preserved_stylesheet );
		}
	}
}
