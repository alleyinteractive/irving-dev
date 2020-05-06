<?php
/**
 * Fieldmanager patterns for term management fields.
 *
 * @package Alleypack\Fieldmanager\Patterns
 */

namespace Alleypack\Fieldmanager\Patterns;

/**
 * Get an FM field to managing a term.
 *
 * @param array $args Arguments.
 * @return \Fieldmanager_Group
 */
function get_term_fields( array $args ) : \Fieldmanager_Group {

	// Validate arguments.
	$args = wp_parse_args(
		$args,
		[
			'can_select_term'  => false,
			'can_select_terms' => false,
			'field_type'       => 'checkboxes',
			'has_primary'      => false,
			'label'            => __( 'Term Name', 'alleypack' ),
			'show_group'       => true,
			'taxonomy'         => 'category',
		]
	);

	// Validate boolean values.
	$args['can_select_term']  = wp_validate_boolean( $args['can_select_term'] );
	$args['can_select_terms'] = wp_validate_boolean( $args['can_select_terms'] );
	$args['has_primary']      = wp_validate_boolean( $args['has_primary'] );
	$args['show_group']       = wp_validate_boolean( $args['show_group'] );

	if ( ! taxonomy_exists( $args['taxonomy'] ) ) {
		return [];
	}

	$children = [];

	/**
	 * Add a primary term to meta.
	 */
	if ( $args['has_primary'] ) {

		// These args are reused.
		$field_args = [
			'label'      => sprintf(
				// Translators: %1$s - Taxonomy slug.
				esc_html__( 'Primary %1$s', 'alleypack' ),
				esc_html( $args['taxonomy'] )
			),
			'datasource' => new \Fieldmanager_Datasource_Term(
				[
					'taxonomy'               => $args['taxonomy'],
					'taxonomy_save_to_terms' => false,
					'only_save_to_taxonomy'  => false,
				]
			),
		];

		// Use an autocomplete or select.
		switch ( $args['field_type'] ) {
			case 'autocomplete':
				$children[ "primary_{$args['taxonomy']}_id" ] = new \Fieldmanager_Autocomplete( $field_args );
				break;
			default:
				$children[ "primary_{$args['taxonomy']}_id" ] = new \Fieldmanager_Select( $field_args );
				break;
		}
	}

	/**
	 * Can select one or more terms.
	 */
	if ( $args['can_select_terms'] || $args['can_select_term'] ) {

		// Determine the limit.
		$limit = $args['can_select_terms'] ? 0 : 1;

		$field_args = [
			'label'              => $args['label'],
			'limit'              => $limit,
			'one_label_per_item' => false,
			'datasource'         => new \Fieldmanager_Datasource_Term(
				[
					'taxonomy'               => $args['taxonomy'],
					'taxonomy_save_to_terms' => true,
					'only_save_to_taxonomy'  => true,
				]
			),
		];

		switch ( $args['field_type'] ) {
			case 'autocomplete':
				$children[ "{$args['taxonomy']}_terms" ] = new \Fieldmanager_Autocomplete( $field_args );
				break;
			case 'select':
				$children[ "{$args['taxonomy']}_terms" ] = new \Fieldmanager_Select( $field_args );
				break;
			case 'checkboxes':
			default:
				unset( $field_args['limit'] );
				$children[ "{$args['taxonomy']}_terms" ] = new \Fieldmanager_Checkboxes( $field_args );
				break;
		}
	}

	$field_args = [
		'add_to_prefix'  => false,
		'children'       => $children,
		'collapsible'    => true,
		'label'          => $args['label'],
		'serialize_data' => false,
	];

	if ( ! $args['show_group'] ) {
		unset( $field_args['collapsible'] );
		unset( $field_args['label'] );
	}

	return new \Fieldmanager_Group( $field_args );
}
