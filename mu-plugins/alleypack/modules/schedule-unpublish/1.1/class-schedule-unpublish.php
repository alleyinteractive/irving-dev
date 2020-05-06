<?php
/**
 * Schedule unpublishing of posts.
 *
 * @package Alleypack
 * @version 1.1
 */

namespace Alleypack;

/* phpcs:disable Generic.NamingConventions.ConstructorName.OldStyle */

/**
 * Class for scheduling posts to unpublish at a future date/time.
 */
class Schedule_Unpublish {

	use Singleton;

	/**
	 * Unpublish date meta key.
	 *
	 * @var string
	 */
	const DEFAULT_META_KEY = 'alleypack_schedule_unpublish';

	/**
	 * Cron hook for unpublish action.
	 *
	 * @var string
	 */
	const CRON_HOOK = 'alleypack_unpublish_post';

	/**
	 * Mapping of post types to meta keys for this module.
	 *
	 * @var array
	 */
	private $mapping = [
];

	/**
	 * Setup.
	 */
	public function setup() {

		// Allow filtering of post types and meta keys for this module.
		add_action(
			'init',
			function() {
				$this->mapping = apply_filters(
					'alleypack_schedule_unpublish_mapping',
					[
						'post' => self::DEFAULT_META_KEY,
					]
				);
			}
		);

		// Register post meta.
		add_action( 'init', [ $this, 'register_meta' ] );

		// Add the FM meta box on post types where the module is enabled.
		add_action( 'fm_post', [ $this, 'add_classic_editor_meta_field' ] );

		// Subsequently remove the meta box on post types where Gutenberg is active.
		add_action( 'add_meta_boxes', [ $this, 'maybe_remove_meta_box' ], 100 );

		// Enqueue scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'action_admin_enqueue_scripts' ] );

		// Cron hooks and action.
		add_action( 'added_post_meta', [ $this, 'schedule_unpublish' ], 10, 4 );
		add_action( 'updated_post_meta', [ $this, 'schedule_unpublish' ], 10, 4 );
		add_action( 'deleted_post_meta', [ $this, 'remove_scheduled_unpublish' ], 10, 3 );
		add_action( self::CRON_HOOK, [ $this, 'unpublish_post' ] );
	}

	/**
	 * Add the FM meta box on post types where the module is enabled.
	 * It is too early to use get_current_screen() to conditionally load for
	 * classic editor only, so we just add for all now and remove for some later.
	 *
	 * @param string $type Post type.
	 */
	public function add_classic_editor_meta_field( $type ) {

		// Bail if the post type does not use this module or does not use the
		// default meta key.
		if (
			empty( $this->mapping[ $type ] )
			|| self::DEFAULT_META_KEY !== $this->mapping[ $type ]
		) {
			return;
		}

		$fm = new \Fieldmanager_Datepicker(
			[
				'name'                      => self::DEFAULT_META_KEY,
				'use_time'                  => true,
				'description_after_element' => false,
				'description'               => __( 'Schedule this post to automatically unpublish.', 'alleypack' ),
			]
		);
		$fm->add_meta_box( __( 'Unpublish', 'alleypack' ), $type, 'side' );
	}

	/**
	 * Remove the FM meta box for post types where the block editor is active,
	 * since it would be redundant to the Gutenberg document panel implementation.
	 */
	public function maybe_remove_meta_box() {
		global $current_screen;

		if ( $current_screen->is_block_editor() ?? false ) {
			remove_meta_box( 'fm_meta_box_alleypack_schedule_unpublish', null, 'side' );
		}
	}

	/**
	 * Register meta.
	 */
	public function register_meta() {
		foreach ( $this->mapping as $post_type => $meta_key ) {
			register_post_meta(
				$post_type,
				$meta_key,
				[
					'show_in_rest' => true,
					'single'       => true,
				]
			);
		}
	}

	/**
	 * Enqueue scripts in the admin.
	 */
	public function action_admin_enqueue_scripts() {

		global $current_screen;

		// Bail if this isn't Gutenberg.
		if ( ! ( $current_screen->is_block_editor() ?? false ) ) {
			return;
		}

		// Bail if the post type does not use this module or does not use the
		// default meta key.
		if (
			empty( $this->mapping[ $current_screen->post_type ?? '' ] )
			|| self::DEFAULT_META_KEY !== $this->mapping[ $current_screen->post_type ?? '' ]
		) {
			return;
		}

		wp_enqueue_script(
			'schedule-unpublish',
			plugins_url( '1.1/build/scheduleUnpublish.js', __DIR__ ),
			[ 'wp-i18n', 'wp-edit-post' ],
			'1.1.0',
			true
		);
		$this->inline_locale_data( 'schedule-unpublish' );
	}

	/**
	 * Creates a new Jed instance with specified locale data configuration.
	 *
	 * @param string $to_handle The script handle to attach the inline script to.
	 */
	public function inline_locale_data( string $to_handle ) {

		// Define locale data for Jed.
		$locale_data = [
			'' => [
				'domain' => 'alleypack',
				'lang'   => is_admin() ? get_user_locale() : get_locale(),
			],
		];

		// Pass the Jed configuration to the admin to properly register i18n.
		wp_add_inline_script(
			$to_handle,
			'wp.i18n.setLocaleData( ' . wp_json_encode( $locale_data ) . ", 'alleypack' );"
		);
	}

	/**
	 * Unpublish the post.
	 *
	 * @param int $post_id Post ID.
	 */
	public function unpublish_post( $post_id ) {

		// Bail if the post is not currently published.
		if ( 'publish' !== get_post_status( $post_id ) ) {
			return;
		}

		wp_update_post(
			[
				'ID'          => $post_id,
				'post_status' => 'draft',
			]
		);
	}

	/**
	 * Schedule the unpublish cron when the unschedule meta is added/updated.
	 *
	 * @param int    $meta_id    ID of updated metadata entry.
	 * @param int    $object_id  Object ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $meta_value Meta value.
	 */
	public function schedule_unpublish( $meta_id, $object_id, $meta_key, $meta_value ) {

		// Get the unpublish key for this post type.
		$unpublish_key = $this->mapping[ get_post_type( $object_id ) ] ?? '';

		// Bail if this isn't the right key.
		if ( $unpublish_key !== $meta_key ) {
			return;
		}

		// Check if there's an existing event for this post and remove it if so.
		$this->remove_scheduled_unpublish( [], $object_id, $meta_key );

		// Nothing to schedule.
		if ( empty( $meta_value ) ) {
			return;
		}

		// Convert to a timestamp.
		$timestamp = strtotime( get_gmt_from_date( $meta_value ) . ' GMT' );

		// If the meta is numeric, we know it's from FM, which is stored as
		// a timestamp in UTC. Convert to local time by using the site's GMT offset.
		if ( is_numeric( $meta_value ) ) {
			$timestamp = $meta_value - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		}

		// Schedule the unpublish action.
		wp_schedule_single_event( $timestamp, self::CRON_HOOK, [ $object_id ] );
	}

	/**
	 * Remove any previously scheduled unpublish events when the unschedule
	 * meta is removed.
	 *
	 * @param array  $meta_ids  An array of deleted metadata entry IDs.
	 * @param int    $object_id Object ID.
	 * @param string $meta_key  Meta key.
	 */
	public function remove_scheduled_unpublish( $meta_ids, $object_id, $meta_key ) {

		// Get the unpublish key for this post type.
		$unpublish_key = $this->mapping[ get_post_type( $object_id ) ] ?? '';

		// Bail if this isn't the right key.
		if ( $unpublish_key !== $meta_key ) {
			return;
		}

		wp_clear_scheduled_hook( self::CRON_HOOK, [ $object_id ] );
	}
}
