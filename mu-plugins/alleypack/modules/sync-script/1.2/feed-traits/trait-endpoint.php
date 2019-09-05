<?php
/**
 * Trait that enables an API endpoint sync option on feeds.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Endpoint trait.
 */
trait Endpoint {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $endpoint_namespace = 'alleypack/v2';

	/**
	 * Default endpoint limit.
	 *
	 * @var integer
	 */
	protected $endpoint_limit = 10;

	/**
	 * Register endpoint for triggering a sync.
	 */
	protected function setup_endpoint() {
		add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
	}

	/**
	 * Register the routes.
	 */
	public function register_rest_routes() {
		register_rest_route(
			$this->get_endpoint_namespace(),
			"/sync/{$this->get_sync_slug()}/",
			[
				'methods'  => 'GET',
				'callback' => [ $this, 'do_endpoint' ],
				'args' => [
					'offset' => [
						'default'           => 0,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => 'rest_validate_request_arg',
					],
					'limit' => [
						'default'           => $this->endpoint_limit,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => 'rest_validate_request_arg',
					],
					'post_id' => [
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => 'rest_validate_request_arg',
					],
					'unique_id' => [
						'type'              => 'string',
						'sanitize_callback' => 'esc_html',
						'validate_callback' => 'rest_validate_request_arg',
					],
					'redirect_to' => [
						'type'              => 'string',
						'sanitize_callback' => 'esc_url',
						'validate_callback' => 'rest_validate_request_arg',
					],
				],
			]
		);
	}

	/**
	 * Get the endpoint namespace.
	 *
	 * @return string
	 */
	public function get_endpoint_namespace() {
		return $this->endpoint_namespace;
	}

	/**
	 * Get the endpoint url with some url arguments applied.
	 *
	 * @param array $query_args Array of query args.
	 * @return string
	 */
	public function get_endpoint_url( $query_args = [] ) {
		return add_query_arg(
			$query_args,
			rest_url( "{$this->get_endpoint_namespace()}/sync/{$this->get_sync_slug()}/" )
		);
	}

	/**
	 * Get the number of items that should sync at a time by default when
	 * loading by limit and offset.
	 *
	 * @return int
	 */
	public function get_endpoint_limit() {
		return $this->endpoint_limit;
	}

	/**
	 * Execute sync whenever the endpoint is hit.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 * @return bool
	 */
	public function do_endpoint( $request ) : bool {

		// Get endpoint parameters.
		$limit       = $request->get_param( 'limit' );
		$offset      = $request->get_param( 'offset' );
		$post_id     = $request->get_param( 'post_id' );
		$redirect_to = $request->get_param( 'redirect_to' );
		$unique_id   = $request->get_param( 'unique_id' );

		// Determine how to load a source feed.
		switch ( true ) {
			case ! empty( $unique_id ):
				$this->load_source_data_by_unique_id( $unique_id );
				break;

			case ! empty( $post_id ):
				$this->load_source_data_by_post_id( $post_id );
				break;

			default:
				$this->load_source_data_by_limit_and_offset( $limit, $offset );
				break;
		}

		// Sync data.
		$data_synced = $this->sync_source_data();

		if ( ! empty( $redirect_to ) ) {

			// Display an admin notice indicating a sync has occurred.
			$redirect_to = add_query_arg(
				'alleypack_sync',
				true,
				urldecode( $redirect_to )
			);

			wp_safe_redirect( $redirect_to );
			exit();
		}

		return $data_synced;
	}
}
