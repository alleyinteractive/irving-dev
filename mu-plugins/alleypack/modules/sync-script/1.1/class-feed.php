<?php
/**
 * Feed.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Feed.
 */
abstract class Feed {

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'sync-feed';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\Alleypack\Sync_Script\Feed_Item';

	/**
	 * Keep track of the feed item offset. This esentially acts as a cursor to
	 * keep track of current sync process.
	 *
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * Batch size.
	 *
	 * @var int
	 */
	protected $batch_size = 50;

	/**
	 * Use cron instead of internal sync.
	 *
	 * @var bool
	 */
	protected $use_cron = true;

	/**
	 * Cron key.
	 *
	 * @var string
	 */
	protected $cron_key;

	/**
	 * Default cron schedule.
	 *
	 * @var string
	 */
	protected $cron_schedule = 'daily';

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $endpoint_namespace = 'alleypack/v2';

	/**
	 * Should the endpoint use pages instead of an offset?
	 *
	 * @var boolean
	 */
	protected $use_pages_instead_of_offset = false;

	/**
	 * Function defined by extended classes that load data from the feed's
	 * source.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return array
	 */
	abstract protected function load_source_feed_data( int $limit, int $offset ) : array;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// Basic initialization.
		$this->register_post_status();

		// Setup cron and endpoint.
		$this->setup_cron();
		$this->setup_endpoint();
	}

	/**
	 * Sync batch.
	 *
	 * @param int $limit  Limit.
	 * @param int $offset Offset.
	 *
	 * @return bool
	 */
	public function sync_feed_items( $limit, $offset ) : bool {

		$source_feed = $this->load_source_feed_data( $limit, $offset );

		if ( empty( $source_feed ) ) {
			alleypack_log( 'No feed items found.' );
			return false;
		}

		// Migrate content.
		array_map( [ $this, 'sync_feed_item' ], $source_feed );

		if ( count( $source_feed ) < $limit ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize a new feed item and sync it.
	 *
	 * @param array $source_feed_item Source feed item.
	 */
	protected function sync_feed_item( $source_feed_item ) {
		$feed_item = new $this->feed_item_class();
		$feed_item->load_source( $source_feed_item );
		$feed_item->sync();
	}

	/**
	 * Setup cron job(s).
	 */
	public function setup_cron() {
		$this->cron_key = "alleypack_sync_{$this->sync_slug}";

		// Remove scheduled jobs.
		if ( ! $this->use_cron ) {
			wp_clear_scheduled_hook( $this->cron_key, [ $this->batch_size, 0 ] );
			return;
		}

		// Schedule a job.
		if ( ! wp_next_scheduled( $this->cron_key, [ $this->batch_size, 0 ] ) ) {
			wp_schedule_event( time(), $this->cron_schedule, $this->cron_key, [ $this->batch_size, 0 ] );
		}

		add_action( $this->cron_key, [ $this, 'sync_batch_by_cron' ], 10, 2 );
		add_action( $this->cron_key . '_next', [ $this, 'sync_batch_by_cron' ], 10, 2 );
	}

	/**
	 * Sync batch via cron.
	 *
	 * @param int $limit  Limit.
	 * @param int $offset Offset.
	 */
	public function sync_batch_by_cron( $limit, $offset ) {
		$has_more = $this->sync_feed_items( $limit, $offset );

		if ( true === $has_more ) {
			if ( $this->use_pages_instead_of_offset ) {
				$offset++;
			} else {
				$offset += $limit;
			}
			wp_schedule_single_event( time() + 5, $this->cron_key . '_next', [ $limit, $offset ] );
		}
	}

	/**
	 * Register endpoint for triggering a sync.
	 */
	protected function setup_endpoint() {

		// Filter to disable the endpoint.
		$disable_endpoint = apply_filters( 'alleypack_sync_disable_endpoint', false );
		if ( $disable_endpoint ) {
			return;
		}

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					$this->endpoint_namespace, // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
					"/sync/{$this->sync_slug}/", // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
					[
						'methods'  => 'GET',
						'callback' => [ $this, 'do_endpoint' ], // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
						'args'     => [
							'offset' => [
								'default'           => 0,
								'type'              => 'integer',
								'sanitize_callback' => 'absint',
								'validate_callback' => 'rest_validate_request_arg',
							],
							'limit'  => [
								'default'           => $this->batch_size, // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
								'type'              => 'integer',
								'sanitize_callback' => 'absint',
								'validate_callback' => 'rest_validate_request_arg',
							],
						],
					]
				);
			}
		);
	}

	/**
	 * Execute sync whenever the endpoint is hit.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 * @return bool
	 */
	public function do_endpoint( $request ) : bool {
		$limit  = absint( $request->get_param( 'limit' ) );
		$offset = absint( $request->get_param( 'offset' ) );

		$has_more = $this->sync_feed_items( $limit, $offset );

		if ( true === $has_more ) {
			return true;
		}

		$feed_item = $this->feed_item_class;
		$feed_item::unpublish_unsynced_content();

		return false;
	}

	/**
	 * Register the syncing post status.
	 */
	protected function register_post_status() {
		register_post_status(
			'alleypack-syncing',
			[
				'label'                     => __( 'Syncing', 'alleypack' ),
				'public'                    => true,
				'show_in_admin_status_list' => true,
			]
		);
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {

		// Get screen to determine what scripts should load.
		$screen = get_current_screen();

		// Handling for posts.
		$post_type = $this->feed_item_class::$post_type ?? '';
		if (
			! empty( $post_type )
			&& 'edit' === ( $screen->base ?? '' )
			&& ( $screen->post_type ?? '' ) === $this->feed_item_class::$post_type
		) {
			$this->enqueue_object_sync_button( $post_type );
		}

		// Handling for users.
		$user_type = $this->feed_item_class::$user ?? '';
		if (
			! empty( $user_type )
			&& 'users' === ( $screen->base ?? '' )
		) {
			$this->enqueue_object_sync_button( $user_type );
		}

		$term_type = $this->feed_item_class::$taxonomy ?? '';
		if (
			! empty( $term_type )
			&& 'edit-tags' === ( $screen->base ?? '' )
			&& $term_type === $screen->taxonomy
		) {
			$this->enqueue_object_sync_button( $term_type );
		}
	}

	/**
	 * Enqueue assets for the object sync button.
	 *
	 * @param string $object_type Object type.
	 */
	public function enqueue_object_sync_button( $object_type ) {
		switch ( $object_type ) {
			case 'user':
				$plural = __( 'Users', 'alleypack' );
				break;
			case 'taxonomy':
				$plural = __( 'Terms', 'alleypack' );
				break;
			default:
				$plural = get_post_type_object( $object_type )->label ?? __( 'Posts', 'alleypack' );
				break;
		}

		// Enqueue sync button script.
		wp_enqueue_script(
			"alleypack-sync-script-{$object_type}-button-js",
			get_module_url() . '/assets/js/objectSyncButton.js',
			[
				'jquery',
			],
			'1.1',
			true
		);

		wp_enqueue_style(
			"alleypack-sync-script-{$object_type}-button-css",
			get_module_url() . '/assets/css/objectSyncButton.css',
			[],
			'1.1'
		);

		wp_localize_script(
			"alleypack-sync-script-{$object_type}-button-js",
			'alleypackSync',
			[
				'postType'     => $object_type,
				'objectPlural' => $plural,
				'endpoint'     => rest_url( $this->endpoint_namespace . "/sync/{$this->sync_slug}/" ),
				'limit'        => $this->batch_size,
				'usePages'     => $this->use_pages_instead_of_offset,
			]
		);
	}
}
