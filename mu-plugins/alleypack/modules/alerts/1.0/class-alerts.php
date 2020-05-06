<?php
/**
 * Easily create and manage breaking news alerts.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * Class defining breaking news alerts.
 */
class Alerts {

	use \Alleypack\Singleton;

	/**
	 * Alert post type.
	 *
	 * @var string
	 */
	private $post_type = 'alert';

	/**
	 * Alert location taxonomy.
	 *
	 * @var string
	 */
	private $taxonomy = 'alert-location';

	/**
	 * Setup.
	 */
	public function setup() {

		// Allow for filtering of the post type and taxonomy.
		$this->post_type = apply_filters( 'alleypack_alert_post_type', $this->post_type );
		$this->taxonomy  = apply_filters( 'alleypack_alert_location_taxonomy', $this->taxonomy );

		// Register post type and taxonomy.
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'init', [ $this, 'register_taxonomy' ] );

		// Use classic editor.
		add_filter( 'use_block_editor_for_post_type', [ $this, 'disable_gutenberg_editor' ], 10, 2 );

		// Setup this taxonomy with the programmatic terms module.
		create_programmatic_taxonomy( $this->taxonomy );

		// Add location options.
		add_filter( 'alleypack_programmatic_terms_options', [ $this, 'get_location_options' ], 10, 2 );

		// Enable the schedule-unpublish module for the alert post type.
		add_filter( 'alleypack_schedule_unpublish_post_types', [ $this, 'enable_unpublish_of_alerts' ] );
	}

	/**
	 * Register a new post type for alerts.
	 */
	public function register_post_type() {

		// Ensure this is a new post type.
		if ( post_type_exists( $this->post_type ) ) {
			return;
		}

		$args = [
			'labels'             => [
				'name'                     => __( 'Alerts', 'alleypack' ),
				'singular_name'            => __( 'Alert', 'alleypack' ),
				'add_new'                  => __( 'Add New Alert', 'alleypack' ),
				'add_new_item'             => __( 'Add New Alert', 'alleypack' ),
				'edit_item'                => __( 'Edit Alert', 'alleypack' ),
				'new_item'                 => __( 'New Alert', 'alleypack' ),
				'view_item'                => __( 'View Alert', 'alleypack' ),
				'view_items'               => __( 'View Alerts', 'alleypack' ),
				'search_items'             => __( 'Search Alerts', 'alleypack' ),
				'not_found'                => __( 'No alerts found', 'alleypack' ),
				'not_found_in_trash'       => __( 'No alerts found in Trash', 'alleypack' ),
				'parent_item_colon'        => __( 'Parent Alert:', 'alleypack' ),
				'all_items'                => __( 'All Alerts', 'alleypack' ),
				'archives'                 => __( 'Alert Archives', 'alleypack' ),
				'attributes'               => __( 'Alert Attributes', 'alleypack' ),
				'insert_into_item'         => __( 'Insert into alert', 'alleypack' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this alert', 'alleypack' ),
				'featured_image'           => __( 'Featured Image', 'alleypack' ),
				'set_featured_image'       => __( 'Set featured image', 'alleypack' ),
				'remove_featured_image'    => __( 'Remove featured image', 'alleypack' ),
				'use_featured_image'       => __( 'Use as featured image', 'alleypack' ),
				'filter_items_list'        => __( 'Filter alerts list', 'alleypack' ),
				'items_list_navigation'    => __( 'Alerts list navigation', 'alleypack' ),
				'items_list'               => __( 'Alerts list', 'alleypack' ),
				'item_published'           => __( 'Alert published.', 'alleypack' ),
				'item_published_privately' => __( 'Alert published privately.', 'alleypack' ),
				'item_reverted_to_draft'   => __( 'Alert reverted to draft.', 'alleypack' ),
				'item_scheduled'           => __( 'Alert scheduled.', 'alleypack' ),
				'item_updated'             => __( 'Alert updated.', 'alleypack' ),
				'menu_name'                => __( 'Alerts', 'alleypack' ),
			],
			'public'             => true,
			'publicly_queryable' => false,
			'has_single'         => false,
			'show_in_rest'       => true,
			'show_in_menu'       => true,
			'has_archive'        => false,
			'menu_icon'          => 'dashicons-megaphone',
			'supports'           => [
				'editor',
				'revisions',
				'title',
			],
		];

		$args = apply_filters( 'alleypack_alert_post_type_args', $args );

		register_post_type( $this->post_type, $args ); // phpcs:ignore WordPress.NamingConventions.ValidPostTypeSlug.NotStringLiteral
	}

	/**
	 * Register taxonomy to manage alert locations.
	 */
	public function register_taxonomy() {

		// Ensure this is a new taxonomy.
		if ( taxonomy_exists( $this->taxonomy ) ) {
			return;
		}

		$args = [
			'labels'            => [
				'name'                  => __( 'Alert Locations', 'alleypack' ),
				'singular_name'         => __( 'Alert Location', 'alleypack' ),
				'search_items'          => __( 'Search Alert Locations', 'alleypack' ),
				'popular_items'         => __( 'Popular Alert Locations', 'alleypack' ),
				'all_items'             => __( 'All Alert Locations', 'alleypack' ),
				'parent_item'           => __( 'Parent Alert Location', 'alleypack' ),
				'parent_item_colon'     => __( 'Parent Alert Location:', 'alleypack' ),
				'edit_item'             => __( 'Edit Alert Location', 'alleypack' ),
				'view_item'             => __( 'View Alert Location', 'alleypack' ),
				'update_item'           => __( 'Update Alert Location', 'alleypack' ),
				'add_new_item'          => __( 'Add New Alert Location', 'alleypack' ),
				'new_item_name'         => __( 'New Alert Location Name', 'alleypack' ),
				'add_or_remove_items'   => __( 'Add or remove alert locations', 'alleypack' ),
				'choose_from_most_used' => __( 'Choose from the most used alert locations', 'alleypack' ),
				'not_found'             => __( 'No alert locations found', 'alleypack' ),
				'no_terms'              => __( 'No alert locations', 'alleypack' ),
				'items_list_navigation' => __( 'Alert Locations list navigation', 'alleypack' ),
				'items_list'            => __( 'Alert Locations list', 'alleypack' ),
				'back_to_items'         => __( '&larr; Back to Alert Locations', 'alleypack' ),
				'menu_name'             => __( 'Alert Locations', 'alleypack' ),
				'name_admin_bar'        => __( 'Alert Locations', 'alleypack' ),
			],
			'show_in_menu'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		];

		$args = apply_filters( 'alleypack_alert_location_taxonomy_args', $args );

		register_taxonomy(
			$this->taxonomy,
			$this->post_type,
			$args
		);
	}

	/**
	 * Disable Gutenberg block editor for alert post type.
	 *
	 * @param bool   $current_status The status of the post.
	 * @param string $post_type      The post type.
	 * @return bool
	 */
	public function disable_gutenberg_editor( $current_status, $post_type ): bool {
		if ( $post_type === $this->post_type ) {
			return false;
		}
		return $current_status;
	}

	/**
	 * Enable the schedule-unpublish module for the alert post type.
	 *
	 * @param array $post_types Post types where the schedule-unpublish module
	 *                          is activated.
	 * @return array
	 */
	public function enable_unpublish_of_alerts( array $post_types ): array {
		$post_types[] = $this->post_type;
		return $post_types;
	}

	/**
	 * Get location options.
	 *
	 * @param array  $options  Array of term options ( [ slug => name ] ).
	 * @param string $taxonomy Taxonomy.
	 * @return array
	 */
	public function get_location_options( array $options, string $taxonomy ): array {

		if ( $taxonomy !== $this->taxonomy ) {
			return $options;
		}

		return apply_filters( 'alleypack_alert_location_term_options', $options );
	}

	/**
	 * Get the most recent alert by location(s).
	 *
	 * @param array $locations Locations.
	 * @return string|null
	 */
	public function get_alert_by_locations( array $locations ): ?string {

		$alert_post = $this->get_alert_post_by_locations( $locations );

		if ( empty( $alert_post ) ) {
			return null;
		}

		return apply_filters( 'the_content', $alert_post->post_content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Get the most recent alert post by location(s).
	 *
	 * @param array $locations Locations.
	 * @return \WP_Post|null
	 */
	public function get_alert_post_by_locations( array $locations ): ?\WP_Post {

		$alerts = new \WP_Query(
			[
				'post_type' => $this->post_type,
				'tax_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $this->taxonomy,
						'field'    => 'slug',
						'terms'    => $locations,
					],
				],
			]
		);

		return $alerts->post ?? null;
	}
}
