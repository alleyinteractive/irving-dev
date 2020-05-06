<?php
/**
 * A feed will process data from a source offering various approaches to sync
 * the feed items.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Feed class.
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
	 * Is cron enabled?
	 *
	 * @var bool
	 */
	protected $use_cron = false;

	/**
	 * Cron schedule.
	 *
	 * @var string
	 */
	protected $cron_schedule = 'daily';

	/**
	 * Cron batch size.
	 *
	 * @var integer
	 */
	protected $cron_batch_size = 10;

	/**
	 * An array of source objects to sync somehow.
	 *
	 * @var array
	 */
	protected $source_data = [];

	/**
	 * Setup this feed.
	 */
	public function __construct() {

		$this->setup_cron();

		array_map(
			function( $method_name ) {
				// Call setup functions on any included traits.
				if ( method_exists( $this, $method_name ) ) { // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
					$this->$method_name(); // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
				}
			},
			[
				'setup_endpoint',
				'setup_gui',
			]
		);
	}

	/**
	 * Load source data using a limit and offset.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return bool Loaded successfully?
	 */
	abstract protected function load_source_data_by_limit_and_offset( int $limit, int $offset ) : bool;

	/**
	 * Load source data using a unique ID.
	 *
	 * @param string $unique_id Unique ID.
	 * @return bool Loaded successfully?
	 */
	abstract protected function load_source_data_by_unique_id( string $unique_id ) : bool;

	/**
	 * Load source data using a post ID.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	public function load_source_data_by_post_id( int $post_id ) : bool {

		// Get the unique ID from this post ID.
		$unique_id = get_post_meta( $post_id, $this->feed_item_class::get_unique_id_key(), true );

		// Return the source using the unique id.
		if ( ! empty( $unique_id ) ) {
			$this->load_source_data_by_unique_id( $unique_id );
		}

		return $this->has_source_data();
	}

	/**
	 * Get the sync slug.
	 *
	 * @return string
	 */
	public function get_sync_slug() {
		return $this->sync_slug;
	}

	/**
	 * Get the feed item class name.
	 *
	 * @return string
	 */
	public function get_feed_item_class() {
		return $this->feed_item_class;
	}

	/**
	 * Is there valid source data?
	 *
	 * @return boolean
	 */
	public function has_source_data() : bool {
		return ! empty( $this->get_source_data() );
	}

	/**
	 * Get the source data.
	 *
	 * @return array
	 */
	public function get_source_data() : array {
		return (array) $this->source_data;
	}

	/**
	 * Set the source data.
	 *
	 * @param array $data Data to be set.
	 */
	public function set_source_data( array $data ) {
		$this->source_data = array_filter( $data );
	}

	/**
	 * Helper to reset the source data.
	 */
	public function reset_source_data() {
		$this->set_source_data( [] );
	}

	/**
	 * Sync the data in source_data.
	 *
	 * @return bool Was successful?
	 */
	public function sync_source_data() {

		if ( ! $this->has_source_data() ) {
			alleypack_log( 'No feed items found.' );
			return false;
		}

		array_map( [ $this, 'sync_feed_item' ], $this->get_source_data() );

		return true;
	}

	/**
	 * Initialize a new feed item and sync it.
	 *
	 * @param array $source_feed_item Source feed item.
	 */
	protected function sync_feed_item( array $source_feed_item ) {
		$feed_item = new $this->feed_item_class();
		$feed_item->load_source( $source_feed_item );
		$feed_item->sync();

		// Display message when doing a CLI sync.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::success( "Object {$feed_item->get_unique_id()} sync'd to object ID {$feed_item->get_object_id()}" );
		}
	}

	/**
	 * Get the cron key.
	 *
	 * @return string
	 */
	protected function get_cron_key() {
		return "alleypack_sync_{$this->get_sync_slug()}";
	}

	/**
	 * Setup cron job(s).
	 */
	protected function setup_cron() {

		// Remove scheduled jobs.
		if ( ! $this->use_cron ) {
			wp_clear_scheduled_hook( $this->get_cron_key(), [ $this->cron_batch_size, 0 ] );
			return;
		}

		// Schedule a job.
		if ( ! wp_next_scheduled( $this->get_cron_key(), [ $this->cron_batch_size, 0 ] ) ) {
			wp_schedule_event( time(), $this->cron_schedule, $this->get_cron_key(), [ $this->cron_batch_size, 0 ] );
		}

		add_action( $this->get_cron_key(), [ $this, 'sync_batch_by_cron' ], 10, 2 );
		add_action( $this->get_cron_key() . '_next', [ $this, 'sync_batch_by_cron' ], 10, 2 );
	}

	/**
	 * Sync batch via cron.
	 *
	 * @param int $limit  Limit.
	 * @param int $offset Offset.
	 */
	public function sync_batch_by_cron( $limit, $offset ) {

		// Load data.
		if ( ! $this->load_source_data_by_limit_and_offset( $limit, $offset ) ) {
			return;
		}

		// Sync data.
		if ( ! $this->sync_source_data() ) {
			return;
		}

		// Increment the offset by the limit.
		$offset += $limit;

		// Schedule next run for asap.
		wp_schedule_single_event( time() + 5, $this->get_cron_key() . '_next', [ $limit, $offset ] );
	}
}
