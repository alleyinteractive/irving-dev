<?php
/**
 * Class for custom content field. Output sanitized output easily.
 *
 * @package Alleypack\Fieldmanager
 */

namespace Alleypack\Fieldmanager\Fields;

if ( class_exists( '\Fieldmanager_Field' ) ) :

	/**
	 * Content field.
	 */
	class Fieldmanager_Content extends \Fieldmanager_Field {

		/**
		 * Do not save this field. This class is purely informational.
		 *
		 * @var bool
		 */
		public $skip_save = true;

		/**
		 * Allow HTML in the description.
		 *
		 * @var array
		 */
		public $escape = [
			'description' => 'wp_kses_post',
		];

		/**
		 * Do not dislay any form fields.
		 *
		 * @param mixed $value The current value.
		 * @return string HTML string.
		 */
		public function form_element( $value = '' ) {
			return '';
		}
	}

endif;
