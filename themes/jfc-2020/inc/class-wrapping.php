<?php
/**
 * Class file for Wrapping.
 *
 * @package JFC_2020
 */

namespace JFC_2020;

/**
 * DRY up theme templates, just a little bit. Based on "Theme Wrappers" from
 * {@see http://scribu.net/wordpress/theme-wrappers.html}.
 */
class Wrapping {

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 *
	 * @var string
	 */
	public static $base;

	/**
	 * Filters the path of a template to wrap it in wrapper.php.
	 *
	 * @param  string $template The path of the template to wrap.
	 * @return mixed            Template filename (@see locate_template()) or void.
	 */
	public static function wrap( $template ) { // phpcs:ignore WordPressVIPMinimum.Hooks.AlwaysReturnInFilter.VoidReturn
		/**
		 * Filter to force skip wrapping the template. To skip wrapping the
		 * template in wrapper.php, simply return true.
		 *
		 * @param bool $skip_theme_wrapper Template won't be wrapped if true.
		 *                                 Defaults to false.
		 * @param string $template The template being loaded.
		 */
		if (
			false === strpos( $template, 'wp-content/themes/' )
			|| false !== strpos( $template, 'themes/vip/plugins/' )
			|| apply_filters( 'jfc_2020_skip_theme_wrapper', false, $template )
		) {
			return $template;
		}

		// Ensure $template is valid and does not contain dir traversal.
		if ( 0 !== validate_file( $template ) ) {
			return;
		}

		self::$base = substr( basename( $template ), 0, -4 );

		$templates = array( 'wrapper.php' );

		if ( 'index' !== self::$base ) {
			array_unshift( $templates, sprintf( 'wrapper-%s.php', self::$base ) );
		}

		return locate_template( $templates );
	}
}
add_filter( 'template_include', array( __NAMESPACE__ . '\Wrapping', 'wrap' ), 99 );
