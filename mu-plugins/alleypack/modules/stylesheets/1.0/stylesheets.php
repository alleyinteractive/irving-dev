<?php
/**
 * An implementation and manager for localized classes produced by CSS Modules
 *
 * @package Alleypack
 * @copyright 2014-2019 Alley Interactive
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 or greater,
 * as published by the Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * The license for this software can likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

// Load required classes.
require_once __DIR__ . '/class-stylesheets.php';

if ( ! function_exists( 'ai_get_classnames' ) ) :
	/**
	 * Get a string of valid classes from a provided series of arguments.
	 *
	 * @param string[] $static_classes  Indexed array of classes to merge.
	 * @param array    $dynamic_classes Associative array of classes. Keys are
	 *                                  classes, values are booleans determining
	 *                                  if that class should print.
	 * @param string   $stylesheet      Optional. Stylesheet to get classname
	 *                                  from. If not provided, will use stylesheet
	 *                                  provided via ai_use_stylesheet.
	 * @return string                   The variable or $default.
	 */
	function ai_get_classnames( $static_classes, $dynamic_classes = [], $stylesheet = false ) {
		list( $dynamic_classes, $stylesheet ) = _ai_fix_stylesheet_args( $dynamic_classes, $stylesheet );
		$valid_classes = \Alleypack\Stylesheets::instance()->get_classnames( $static_classes, $dynamic_classes, $stylesheet );
		return implode( ' ', $valid_classes );
	}
endif;

if ( ! function_exists( 'ai_get_classnames_with_global' ) ) :
	/**
	 * Print a string of valid classes from a provided series of arguments,
	 * including the input value itself.
	 *
	 * @param string[] $static_classes  Indexed array of classes to merge.
	 * @param array    $dynamic_classes Associative array of classes. Keys are
	 *                                  classes, values are booleans determining
	 *                                  if that class should print.
	 * @param string   $stylesheet      Optional. Stylesheet to get classname from.
	 *                                  If not provided, will use stylesheet provided
	 *                                  via ai_use_stylesheet.
	 */
	function ai_get_classnames_with_global( $static_classes, $dynamic_classes = [], $stylesheet = false ) {
		list( $dynamic_classes, $stylesheet ) = _ai_fix_stylesheet_args( $dynamic_classes, $stylesheet );
		$valid_classes = [];
		// '__AI_GLOBAL__' is an effort to simulate a global stylesheet name that would never exist.
		$valid_classes[] = ai_get_classnames( $static_classes, $dynamic_classes, '__AI_GLOBAL__' );
		$valid_classes[] = ai_get_classnames( $static_classes, $dynamic_classes, $stylesheet );
		return implode( ' ', array_unique( $valid_classes ) );
	}
endif;

if ( ! function_exists( 'ai_the_classnames' ) ) :
	/**
	 * Print a string of valid classes from a provided series of arguments
	 *
	 * @param string[] $static_classes  Indexed array of classes to merge.
	 * @param array    $dynamic_classes Associative array of classes. Keys are
	 *                                  classes, values are booleans determining
	 *                                  if that class should print.
	 * @param string   $stylesheet      Optional. Stylesheet to get classname from.
	 *                                  If not provided, will use stylesheet
	 *                                  provided via ai_use_stylesheet.
	 */
	function ai_the_classnames( $static_classes, $dynamic_classes = [], $stylesheet = false ) {
		list( $dynamic_classes, $stylesheet ) = _ai_fix_stylesheet_args( $dynamic_classes, $stylesheet );
		echo esc_attr( ai_get_classnames( $static_classes, $dynamic_classes, $stylesheet ) );
	}
endif;

if ( ! function_exists( 'ai_the_classnames_with_global' ) ) :
	/**
	 * Print a string of valid classes from a provided series of arguments,
	 * including the input value itself.
	 *
	 * @param string[] $static_classes  Indexed array of classes to merge.
	 * @param array    $dynamic_classes Associative array of classes. Keys are
	 *                                  classes, values are booleans determining
	 *                                  if that class should print.
	 * @param string   $stylesheet      Optional. Stylesheet to get classname from.
	 *                                  If not provided, will use stylesheet provided
	 *                                  via ai_use_stylesheet.
	 */
	function ai_the_classnames_with_global( $static_classes, $dynamic_classes = [], $stylesheet = false ) {
		list( $dynamic_classes, $stylesheet ) = _ai_fix_stylesheet_args( $dynamic_classes, $stylesheet );
		echo esc_attr( ai_get_classnames_with_global( $static_classes, $dynamic_classes, $stylesheet ) );
	}
endif;

if ( ! function_exists( 'ai_use_stylesheet' ) ) :

	/**
	 * Set the current stylesheet. This allows you to use ai_the_classnames or
	 * ai_get_classnames without explicitly providing a stylesheet each time.
	 *
	 * @param  string $stylesheet Stylesheet to set.
	 *
	 * @return void
	 */
	function ai_use_stylesheet( $stylesheet ) {
		\Alleypack\Stylesheets::instance()->use_stylesheet( $stylesheet );
	}
endif;

if ( ! function_exists( '_ai_fix_stylesheet_args' ) ) {

	/**
	 * Sort out `$dynamic_classes` and `$stylesheet` for all of the stylesheet-
	 * related functions.
	 *
	 * `$dynamic_classes` comes before `$stylesheet` in the argument order, but
	 * should be optional.
	 * This helper determines if `$dynamic_classes` was actually provided or not.
	 *
	 * @access private
	 *
	 * @param  array  $dynamic_classes Technically, `$name` should be a string
	 *                                 or null. However, because it's optional,
	 *                                 it might be string. In that case, it will
	 *                                 be reset to an empty arry and its value
	 *                                 transferred to `$stylesheet`.
	 * @param  string $stylesheet      Variables to pass to template partials.
	 * @return array                   In the format: `array( $dynamic_classes, $stylesheet )`.
	 *                                 This can be used with `list()` very easily.
	 */
	function _ai_fix_stylesheet_args( $dynamic_classes, $stylesheet ) {
		if ( is_string( $dynamic_classes ) ) {
			$stylesheet      = $dynamic_classes;
			$dynamic_classes = [];
		}

		return [ $dynamic_classes, $stylesheet ];
	}
}
