<?php
/**
 * Functions for managing output of various classes.
 * Inspired by https://github.com/JedWatson/classnames.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

/**
 * Print an array of classnames.
 *
 * @param array $static_classes  Indexed array of classes to merge.
 * @param array $dynamic_classes Associative array of classes.
 *                                Keys are classes, values are booleans determining if that class should print.
 * @return void
 */
function the_classnames( array $static_classes, array $dynamic_classes = [] ) {
	$output_classes = get_classnames( $static_classes, $dynamic_classes );

	echo esc_attr( implode( ' ', $output_classes ) );
}

/**
 * Get an array of classnames.
 *
 * @param array $static_classes  Indexed array of classes to merge.
 * @param array $dynamic_classes Associative array of classes.
 *                                Keys are classes, values are booleans determining if that class should print.
 * @return array                  Array of valid classnames
 */
function get_classnames( array $static_classes, array $dynamic_classes = [] ) {
	$output_classes = [];

	// Allow user to pass only dynamic classes as first arg.
	if ( is_assoc( $static_classes ) && empty( $dynamic_classes ) ) {
		$dynamic_classes = $static_classes;
		$static_classes = [];
	}

	// Loop through static classes and add them to output array.
	if ( ! empty( $static_classes ) ) {
		foreach ( $static_classes as $classname ) {
			$output_classes[] = $classname;
		}
	}

	// Loop through dynamic classes, evaluate value, and add to output array if true.
	if ( ! empty( $dynamic_classes ) ) {
		foreach ( $dynamic_classes as $classname => $should_print ) {
			if ( $should_print ) {
				$output_classes[] = $classname;
			}
		}
	}

	return $output_classes;
}

/**
 * Check if an array is associative.
 *
 * @param array $array Array to check.
 * @return bool
 */
function is_assoc( $array ) {
	return count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
}
