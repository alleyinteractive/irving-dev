<?php
/**
 * Class to expose underwriter data to the Irving data endpoint.
 *
 * @package irving;
 */

namespace Irving\Data;

/**
 * Component_Data data endpoint.
 */
class Component_Data {

	use \Alleypack\Singleton;

	/**
	 * The underwriter data for async loading.
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Get the data endpoint settings.
	 *
	 * @return array
	 */
	public function get_endpoint_settings() : array {
		return [
			'slug'     => 'asyncComponentData',
			'callback' => [ $this, 'get_data' ],
		];
	}

	/**
	 * Get the data for this endpoint.
	 *
	 * @return array
	 */
	public function get_data() : array {
		if ( ! empty( $this->data ) ) {
			return $this->data;
		}

		$this->data = [
			'description' => 'this is some data coming from an additional endpoint at process.env.API_ROOT_URL + /data/asyncComponentData',
		];

		return $this->data;
	}
}

// Expose underwriter data to endpoint.
add_filter(
	'wp_irving_data_endpoints',
	function( $endpoints ) {
		$endpoints[] = \Irving\Data\Component_Data::instance()->get_endpoint_settings();
		return $endpoints;
	}
);
