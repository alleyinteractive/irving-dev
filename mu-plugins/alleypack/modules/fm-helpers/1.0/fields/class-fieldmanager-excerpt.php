<?php
/**
 * Class for custom excerpt field.
 *
 * @package Alleypack\Fieldmanager
 */

namespace Alleypack\Fieldmanager\Fields;

if ( class_exists( '\Fieldmanager_Field' ) ) :

	/**
	 * Excerpt field.
	 */
	class Fieldmanager_Excerpt extends \Fieldmanager_Field {

		/**
		 * Override field_class.
		 *
		 * @var string
		 */
		public $field_class = 'text';

		/**
		 * Construct default attributes; 50x4 textarea.
		 *
		 * @param string $label   Label.
		 * @param array  $options Options.
		 */
		public function __construct( $label = '', $options = [] ) {
			$this->attributes = [
				'cols'  => '50',
				'rows'  => '4',
				'style' => 'max-width: 750px; width: 100%;',
			];

			$this->skip_save = true;

			parent::__construct( $label, $options );
		}

		/**
		 * Form element.
		 *
		 * @param  mixed $value This gets ignored, in favor of post_excerpt.
		 * @return string HTML
		 */
		public function form_element( $value = '' ) {
			global $post;
			return sprintf(
				'<textarea class="fm-element" name="excerpt" id="%s" %s >%s</textarea>',
				esc_attr( $this->get_element_id() ),
				$this->get_element_attributes(),
				wp_kses_post( $post->post_excerpt )
			);
		}
	}

endif;
