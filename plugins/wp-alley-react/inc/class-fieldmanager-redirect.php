<?php
/**
 * Redirect FM field.
 *
 * @package Fieldmanager
 */

namespace Alley_React;

class Fieldmanager_Redirect extends \Fieldmanager_Field {

	/**
	 * Construct default attributes
	 *
	 * @param string $label
	 * @param array  $options
	 */
	public function __construct( $label, $options = array() ) {
		parent::__construct( $label, $options );
	}

	/**
	 * Validate the submitted field value before save.
	 *
	 * @param array $value
	 *
	 * @return string
	 */
	public function presave( $value, $current_value = array() ) {

		// If either value doesn't exist or is empty, return a blank string
		if ( empty( $value['from'] ) || empty( $value['to'] ) ) {
			return '';
		}

		return $value;
	}

	/**
	 * Render the redirect form element.
	 *
	 * @param mixed $value
	 *
	 * @return string HTML
	 */
	public function form_element( $value = array() ) {

		$from = empty( $value['from'] ) ? '' : $value['from'];
		$to = empty( $value['to'] ) ? '' : $value['to'];

		return sprintf(
			__( '<label for="%2$s">%1$s</label><input class="fm-dateofbirth" name="%2$s" value="%3$s"> <label for="%5$s">%4$s</label><input class="fm-dateofbirth" name="%5$s" value="%6$s">', 'alley-react' ),
			esc_html__( 'From:', 'alley-react' ),
			esc_attr( $this->get_form_name() . '[from]' ),
			esc_attr( $from ),
			esc_html__( 'To:', 'alley-react' ),
			esc_attr( $this->get_form_name() . '[to]' ),
			esc_attr( $to )
		);
	}
}
