<?php
/**
 * Trait file for FM_Module.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * Helper functions for FM_Modules.
 */
trait FM_Module {

	/**
	 * Get FM label.
	 *
	 * @return string
	 */
	public function get_label() : string {
		// If a label's been specified, use that.
		if ( ! empty( $this->label ) ) {
			return $this->label;
		}

		// Get a label from the component name.
		$label = str_replace( [ '-', '_' ], ' ', $this->name ?? '' );
		return ucwords( $label );
	}

	/**
	 * Get a label macro for a component
	 *
	 * @return false|array Fieldmanager fields or false.
	 */
	public function get_label_macro() {
		$macro_token = $this->get_macro_token();

		if ( ! empty( $macro_token ) ) {
			return [ $this->get_label() . ': %s', $macro_token ];
		}

		return false;
	}

	/**
	 * Get the token for the FM label format.
	 *
	 * @return string
	 */
	public function get_macro_token() {
		// If a token's been specified, use that.
		if ( isset( $this->macro_token ) ) {
			return $this->macro_token;
		}

		// Use content of first available Fieldmanager field if no explicit token is provided.
		$fields = $this->get_fm_fields();
		if ( empty( $fields ) ) {
			return false;
		}
		$first_field_key = key( $fields );

		// Don't use the field if it's Rich Text.
		if ( 'Fieldmanager_RichTextArea' !== get_class( $fields[ $first_field_key ] ) ) {
			return $first_field_key;
		}

		// Fall back to no macro.
		return false;
	}

	/**
	 * Get FM fields.
	 *
	 * @return array
	 */
	public function get_fm_fields() : array {
		return [];
	}

	/**
	 * Parse FM fields.
	 *
	 * @param  array $fm_data Fieldmanager data for this module.
	 * @return self
	 */
	public function parse_from_fm_data( array $fm_data ) : self {
		return $this;
	}
}
