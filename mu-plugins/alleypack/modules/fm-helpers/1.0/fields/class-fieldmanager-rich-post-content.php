<?php
/**
 * Class for custom rich post content field.
 *
 * @package Alleypack\Fieldmanager
 */

namespace Alleypack\Fieldmanager\Fields;

if ( class_exists( '\Fieldmanager_RichTextArea' ) ) :

	/**
	 * Excerpt field.
	 */
	class Fieldmanager_Rich_Post_Content extends \Fieldmanager_RichTextArea {

		/**
		 * Initialize class.
		 *
		 * @param string $label   Label.
		 * @param array  $options Options.
		 */
		public function __construct( $label = '', $options = [] ) {
			$this->skip_save = true;
			parent::__construct( $label, $options );
		}

		/**
		 * Override default form name. Always use post content.
		 *
		 * @param string $multiple Multiple fields.
		 * @return string
		 */
		public function get_form_name( $multiple = '' ) {
			return 'content';
		}

		/**
		 * Set the value to the post content.
		 *
		 * @param mixed $value The field value, if we weren't overriding it.
		 * @return string HTML
		 */
		public function form_element( $value = '' ) {
			global $post;
			return parent::form_element( wp_kses_post( $post->post_content ) );
		}
	}

endif;
