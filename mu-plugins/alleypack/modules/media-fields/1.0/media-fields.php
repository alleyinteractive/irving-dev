<?php
/**
 * Class file for Media Fields.
 *
 * @package Alleypack
 */

namespace Alleypack\Media_Fields;

add_filter( 'attachment_fields_to_edit', __NAMESPACE__ . '\add_media_fields', 10, 2 );
add_filter( 'attachment_fields_to_save', __NAMESPACE__ . '\save_media_fields', 10, 2 );

/**
 * Get the fields to add to attachments.
 *
 * @return array $fields The attachment custom fields.
 */
function get_fields() : array {
	/**
	 * Filters the media fields to be added to attachments.
	 *
	 * @var array $fields The custom media fields.
	 */
	return \apply_filters( 'alleypack_get_media_fields', [] );
}

/**
 * Filters the attachment fields to edit.
 *
 * @param array    $form_fields An array of attachment form fields.
 * @param \WP_Post $post        The WP_Post attachment object.
 * @return array   $form_fields An array of attachment form fields.
 */
function add_media_fields( $form_fields, $post ) : array {
	// Get the fields.
	$fields = get_fields();

	// No fields.
	if ( empty( $fields ) ) {
		return $form_fields;
	}

	foreach ( $fields as $field => $args ) {
		// Get the current value.
		$current_value = \get_post_meta( $post->ID, $field, true );

		$args['value'] = $current_value;

		// Update any input types as needed.
		switch ( $args['input'] ) {
			case 'link':
				$args['input'] = 'text';
				break;
			case 'checkbox':
			case 'checkboxes':
			case 'select':
				$args['html']  = get_options_field_html( $field, $args, $post->ID );
				$args['input'] = 'html';
				break;
			default:
				break;
		}

		// Set the field.
		$form_fields[ $field ] = $args;
	}

	return $form_fields;
}

/**
 * Get the HTML for a options field.
 *
 * @param string $field The field name.
 * @param array  $args  The field args.
 * @param int    $attachment_id The attachment ID.
 * @return string The HTML.
 */
function get_options_field_html( string $field, array $args, int $attachment_id ) : string {
	$html = '';

	switch ( $args['input'] ) {
		case 'checkbox':
			$html = '<input type="checkbox" ' . get_id_name_attributes( $field, $attachment_id ) . ' ' . \checked( 1, $args['value'], false ) . ' />';
			break;
		case 'checkboxes':
			foreach ( $args['options'] as $value => $label ) {
				$html .= '<input type="checkbox" ' . get_id_name_attributes( $field, $attachment_id, true, $value ) .
					' value="' . \esc_attr( $value ) . '" ' .
					( is_array( $args['value'] ) && \in_array( $value, $args['value'], true ) ? 'checked="checked"' : '' )
					. ' />';
				$html .= '<label>' . \esc_html( $label ) . '</label><br />';
			}
			break;
		case 'select':
			$html .= '<select ' . get_id_name_attributes( $field, $attachment_id ) . '>';

			foreach ( $args['options'] as $value => $label ) {
				$html .= '<option value="' . \esc_attr( $value ) . '" ' . \selected( $value, $args['value'], false ) . '>' . \esc_html( $label ) . '</option>';
			}

			$html .= '</select>';
			break;
		default:
			break;
	}

	return $html;
}

/**
 * Get the id and name attributes for custom HTML fields.
 *
 * @param  string $field         The field name.
 * @param  int    $attachment_id The attachment ID.
 * @param  bool   $array         Whether or not this is an array field.
 * @param  string $value         The current value.
 * @return string                The attribute HTML.
 */
function get_id_name_attributes( string $field, int $attachment_id, bool $array = false, string $value = '' ) : string {
	if ( $array ) {
		$id   = 'id="attachments-' . \absint( $attachment_id ) . '-' . \esc_attr( $field ) . '-' . \esc_attr( $value ) . '"';

		/**
		 * The media modal does not properly pass an array value via the AJAX request
		 * to save the data. Therefore, we need to have different input names for
		 * each possible value in the array, instead of just adding `[]`.
		 *
		 * When saving the data we will just check that a combination of the field
		 * name and option value exists.
		 */
		$name = 'name= "attachments[' . \absint( $attachment_id ) . '][' . \esc_attr( $field ) . '-' . \esc_attr( $value ) . ']"';
	} else {
		$id   = 'id="attachments-' . \absint( $attachment_id ) . '-' . \esc_attr( $field ) . '"';
		$name = 'name="attachments[' . \absint( $attachment_id ) . '][' . \esc_attr( $field ) . ']"';
	}

	return $id . ' ' . $name;
}

/**
 * Filters the attachment fields to be saved.
 *
 * @param array $post       An array of post data.
 * @param array $attachment An array of attachment metadata.
 * @return array $post      An array of post data.
 */
function save_media_fields( $post, $attachment ) : array {
	// Get the fields.
	$fields = get_fields();

	// No fields.
	if ( empty( $fields ) ) {
		return $post;
	}

	foreach ( $fields as $field => $args ) {
		// Field not found in data, so lets delete it.
		if ( 'checkboxes' !== $args['input'] && ! \array_key_exists( $field, $attachment ) ) {
			\delete_post_meta( $post['ID'], $field );
			continue;
		}

		// Sanitize the data.
		$sanitized_field = null;
		switch ( $args['input'] ) {
			case 'checkbox':
				$sanitized_field = (bool) $attachment[ $field ];
				break;
			case 'checkboxes':
				$sanitized_field = [];

				/**
				 * Check for the array index to order to determine of the option
				 * was properly set.
				 */
				foreach ( $args['options'] as $key => $label ) {
					if ( ! empty( $attachment[ $field . '-' . $key ] ) ) {
						$sanitized_field[] = \sanitize_text_field( $attachment[ $field . '-' . $key ] );
					}
				}
				break;
			default:
				$sanitized_field = \sanitize_text_field( $attachment[ $field ] );
				break;
		}

		// Save the data.
		\update_post_meta( $post['ID'], $field, $sanitized_field );
	}

	return $post;
}
