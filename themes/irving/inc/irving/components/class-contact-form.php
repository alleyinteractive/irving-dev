<?php
/**
 * Class file for the Contact_Form component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the Contact_Form component for Irving.
 */
class Contact_Form extends \WP_Components\Component {
	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'contact-form';

	/**
	 * Label for FM fields.
	 *
	 * @var string
	 */
	public $label = 'Contact Form';

	/**
	 * Define the default config of a contact.
	 *
	 * @return array Default config values for this component.
	 */
	public function default_config(): array {
		// These config values could be used for sending back validation information,
		// but they aren't at the moment.
		return [
			'title'        => '',
			'name'         => '',
			'email'        => '',
			'message'      => '',
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() {
		return [
			'title'   => new \Fieldmanager_Textfield( __( 'Title', 'wp-irving' ) ),
		];
	}

	/**
	 * Parse data from a saved FM field.
	 *
	 * @param array $fm_data Array of FM data for this component.
	 * @return FeaturedVideo Instance of this class.
	 */
	public function parse_from_fm_data( array $fm_data ) : self {
		$this->set_config( 'title', $fm_data['title'] ?? 'Contact Us' );

		return $this;
	}

	/**
	 * Callback for the route.
	 *
	 * @param  WP_REST_Request $request Request object.
	 * @return array
	 */
	public static function get_route_response( $request ) {

		// Get params.
		$name    = $request->get_param( 'name' ) ?? '';
		$message = $request->get_param( 'message' ) ?? '';

		// Create the response object.
		$params = $request->get_params();

		// All of these params are required and validated on the client side.
		$success = wp_mail(
			'info@alley.co',
			esc_html( "{$name}: 'test message'" ),
			esc_html( $message )
		);

		// Set up response.
		if ( $success ) {
			$response = new \WP_REST_Response(
				[
					'ok'       => true,
					'redirect' => home_url( '/test' ),
				]
			);
			$response->set_status( 307 );
		} else {
			$response = new \WP_REST_Response( [ 'ok' => false ] );
			$response->set_status( 403 );
		}

		return $response;
	}
}
