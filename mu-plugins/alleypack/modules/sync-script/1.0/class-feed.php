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
	use \Alleypack\Singleton;

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
	 * Keep track of how many items have been processed.
	 *
	 * @var int
	 */
	protected $processed = 0;

	/**
	 * Maximum number of items to process during one sync. This is set to an
	 * arbritrarily high number by default, but it is useful to override for
	 * debugging where you only want to process a handful of items at a time.
	 *
	 * @var int
	 */
	protected $max = 100000;

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
	 * Default cron schedule.
	 *
	 * @var string
	 */
	protected $cron_schedule = 'daily';

	/**
	 * Display some way of manually triggering a sync?
	 *
	 * @var bool
	 */
	protected $allow_manual_sync = true;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $endpoint_namespace = 'alleypack/v1';

	/**
	 * Array of source feed items.
	 *
	 * @var array
	 */
	protected $source_feed = [];

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
	 * Setup function for singleton instance.
	 */
	protected function setup() {
		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// Modify heartbeat to track syncing status.
		add_filter( 'heartbeat_received', [ $this, 'sync_heartbeat' ], 10, 2 );

		// Basic initialization.
		$this->register_post_status();
		$this->load_offset();

		// Setup cron and endpoint.
		$this->setup_cron();
		$this->setup_endpoint();
	}

	/**
	 * Kickoff an entire sync.
	 */
	public function sync_feed() {

		// Sync is already in progress.
		if ( $this->is_syncing() ) {
			return;
		}

		// Flag feed as syncing.
		$this->set_is_syncing();

		// Update all existing content as pending update.
		$this->feed_item_class::mark_existing_content_as_syncing();

		// Load a page of results.
		$this->source_feed = $this->load_source_feed_data( $this->batch_size, $this->offset );

		// Process results and loop through additional pagination.
		while ( ! empty( $this->source_feed ) && $this->processed < $this->max ) {

			// Loop through each source feed data item.
			array_map( [ $this, 'sync_source_feed_item' ], $this->source_feed );

			// Save the offset value.
			$this->save_offset();

			// Load next page of results.
			$this->source_feed = $this->load_source_feed_data( $this->batch_size, $this->offset );
		}

		// Sync is complete.
		$this->set_offset( 0 );
		$this->set_is_syncing( false );
		$this->set_last_synced_timestamp();

		$feed_item = $this->feed_item_class;
		$feed_item::unpublish_unsynced_content();
	}

	/**
	 * Initialize a new feed item and sync it.
	 *
	 * @param array $sync_source_feed_item Source feed item.
	 */
	protected function sync_source_feed_item( $sync_source_feed_item ) {
		$feed_item = new $this->feed_item_class();
		$feed_item->load_source( $sync_source_feed_item );
		$feed_item->sync();

		// Increment offset every time a post has synced successfully.
		$this->offset++;
		$this->processed++;
	}

	/**
	 * Get the key for the option that tracks cursor position.
	 *
	 * @return string
	 */
	protected function get_last_synced_timestamp_option_key() {
		return "alleypack_sync_script_{$this->sync_slug}_last_synced_timestamp";
	}

	/**
	 * Get the unix timestamp when the feed was last synced.
	 *
	 * @return int
	 */
	protected function get_last_synced_timestamp() {
		return absint( get_option( $this->get_last_synced_timestamp_option_key() ) );
	}

	/**
	 * Update the last synced timestamp to now.
	 */
	protected function set_last_synced_timestamp() {
		update_option( $this->get_last_synced_timestamp_option_key(), date( 'U' ) );
	}

	/**
	 * Get the key for the option that tracks if a sync is in progress.
	 *
	 * @return string
	 */
	protected function get_is_syncing_option_key() {
		return "alleypack_sync_script_{$this->sync_slug}_is_syncing";
	}

	/**
	 * Is the feed currently syncing?
	 *
	 * @return bool
	 */
	public function is_syncing() {
		// Force sync when debugging.
		if ( debugging_sync() ) {
			return false;
		}

		// If it's been more than 10 minutes since the last sync and we're
		// still in progress, we've probably timed out and need to reset the
		// is_syncing flag.
		if ( time() > ( $this->get_last_synced_timestamp() + MINUTE_IN_SECONDS * 10 ) ) {
			$this->set_is_syncing( false );
		}

		return (bool) get_option( $this->get_is_syncing_option_key() );
	}

	/**
	 * Set the feed's sync status.
	 *
	 * @param bool $is_syncing Value to set as `is_syncing`.
	 */
	public function set_is_syncing( bool $is_syncing = true ) {
		update_option( $this->get_is_syncing_option_key(), $is_syncing );
	}

	/**
	 * Get the key for the option that tracks the synced position.
	 *
	 * @return string
	 */
	protected function get_offset_option_key() {
		return "alleypack_sync_script_{$this->sync_slug}_offset_position";
	}

	/**
	 * Load the cursor position.
	 */
	protected function load_offset() {
		$this->offset = absint( get_option( $this->get_offset_option_key() ) );
	}

	/**
	 * Load the cursor position.
	 */
	protected function save_offset() {
		update_option( $this->get_offset_option_key(), absint( $this->offset ) );
	}

	/**
	 * Update the offset.
	 *
	 * @param int $new_offset New offset value.
	 */
	protected function set_offset( int $new_offset ) {
		$this->offset = absint( $new_offset );
		$this->save_offset();
	}

	/**
	 * Increment the offset.
	 *
	 * @param int $additional_offset Offset value to increment.
	 */
	protected function increment_offset( int $additional_offset ) {
		$this->set_offset( $additional_offset + $this->offset );
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
	 * Setup sync as a cron job.
	 */
	public function setup_cron() {
		$cron_key = "alleypack_sync_{$this->sync_slug}";

		// Remove scheduled jobs.
		if ( ! $this->use_cron ) {
			wp_clear_scheduled_hook( $cron_key );
			return;
		}

		// Schedule a job.
		if ( ! wp_next_scheduled( $cron_key ) ) {
			wp_schedule_event( time(), $this->cron_schedule, $cron_key );
		}

		add_action( $cron_key, [ $this, 'sync_feed' ] );
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
					$this->endpoint_namespace,
					"/sync/{$this->sync_slug}/",
					[
						'methods'  => 'GET',
						'callback' => [ $this, 'do_endpoint' ],
					]
				);
			}
		);
	}

	/**
	 * Execute sync whenever the endpoint is hit.
	 */
	public function do_endpoint() {

		// Sync is already in progress.
		if ( $this->is_syncing() ) {
			return [
				'message' => __( 'Feed is already syncing.', 'alleypack' ),
				'reload'  => false,
			];
		}

		// Kickoff a new sync.
		$this->sync_feed();
		return [
			'message' => __( 'Successfully synced.', 'alleypack' ),
			'reload'  => true,
		];
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {

		// Get screen to determine what scripts should load.
		$screen = get_current_screen();

		// Attempt to get post type.
		$post_type = $this->feed_item_class::$post_type ?? '';

		// Handling for posts.
		if (
			! empty( $post_type )
			&& 'edit' === ( $screen->base ?? '' )
			&& ( $screen->post_type ?? '' ) === $this->feed_item_class::$post_type
		) {
			$this->enqueue_post_sync_button();
		}

		// @todo logic for other types of synced content like terms and users.
	}

	/**
	 * Enqueue assets for the post sync button.
	 */
	public function enqueue_post_sync_button() {

		// Get post type.
		$post_type = $this->feed_item_class::$post_type;

		// Enqueue sync button script.
		wp_enqueue_script(
			'alleypack-sync-script-post-button-js',
			get_module_url() . '/assets/js/postSyncButton.js',
			[
				'jquery',
			],
			'1.0',
			true
		);

		wp_enqueue_style(
			'alleypack-sync-script-post-button-css',
			get_module_url() . '/assets/css/postSyncButton.css',
			[],
			'1.0'
		);

		wp_localize_script(
			'alleypack-sync-script-post-button-js',
			'alleypackSync',
			[
				'postType'     => $post_type,
				'objectPlural' => get_post_type_object( $post_type )->label ?? 'Posts',
				'endpoint'     => rest_url( $this->endpoint_namespace . "/sync/{$this->sync_slug}/" ),
				'isSyncing'    => $this->is_syncing(),
				'heartbeatKey' => 'alleyppackSyncingJob',
			]
		);
	}

	/**
	 * Receive Heartbeat data and respond.
	 *
	 * Processes data received via a Heartbeat request, and returns additional data to pass back to the front end.
	 *
	 * @param array $response Heartbeat response data to pass back to front end.
	 * @param array $data Data received from the front end (unslashed).
	 */
	public function sync_heartbeat( $response, $data ) {
		$response['alleyppackSyncingJob'] = $this->is_syncing();
		return $response;
	}
}
