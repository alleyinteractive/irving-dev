<?php
/**
 * Easily create and manage podcasts.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * Class defining podcasts.
 */
class Podcasts {
	use \Alleypack\Singleton;

	/**
	 * Podcast taxonomy.
	 *
	 * @var string
	 */
	private $taxonomy = 'podcast';

	/**
	 * Podcasts post type.
	 *
	 * @var string
	 */
	private $podcasts_post_type = 'podcast-post';

	/**
	 * Podcast episode post type.
	 *
	 * @var string
	 */
	private $episode_post_type = 'podcast-episode';

	/**
	 * Setup.
	 */
	public function setup() {
		// Allow for filtering of the post types and taxonomy.
		$this->taxonomy = apply_filters(
			'alleypack_podcasts_taxonomy',
			$this->taxonomy
		);

		$this->podcasts_post_type = apply_filters(
			'alleypack_podcasts_podcasts_post_type',
			$this->podcasts_post_type
		);

		$this->episode_post_type = apply_filters(
			'alleypack_podcasts_episode_post_type',
			$this->episode_post_type
		);

		// Register post type and taxonomy.
		add_action( 'init', [ $this, 'register_podcast_post_type' ] );
		add_action( 'init', [ $this, 'register_episode_post_type' ] );
		add_action( 'init', [ $this, 'register_taxonomy' ] );

		add_filter(
			'rest_prepare_taxonomy',
			[ $this, 'hide_taxonomy_selector' ],
			10,
			2
		);

		add_action(
			'fm_post_' . $this->episode_post_type,
			[ $this, 'episode_fields' ]
		);

		add_action(
			'fm_post_' . $this->podcasts_post_type,
			[ $this, 'series_fields' ]
		);

		add_filter(
			'post_type_link',
			[ $this, 'post_type_link' ],
			10,
			2
		);

		\Alleypack\create_term_post_link(
			$this->taxonomy,
			$this->podcasts_post_type
		);
	}

	/**
	 * Register taxonomy to manage podcasts.
	 */
	public function register_taxonomy() {

		// Ensure this is a new taxonomy.
		if ( taxonomy_exists( $this->taxonomy ) ) {
			return;
		}

		$args = [
			'labels'            => [
				'name'                  => __( 'Podcasts', 'alleypack' ),
				'singular_name'         => __( 'Podcast', 'alleypack' ),
				'search_items'          => __( 'Search Podcasts', 'alleypack' ),
				'popular_items'         => __( 'Popular Podcasts', 'alleypack' ),
				'all_items'             => __( 'All Podcasts', 'alleypack' ),
				'parent_item'           => __( 'Parent Podcast', 'alleypack' ),
				'parent_item_colon'     => __( 'Parent Podcast:', 'alleypack' ),
				'edit_item'             => __( 'Edit Podcast', 'alleypack' ),
				'view_item'             => __( 'View Podcast', 'alleypack' ),
				'update_item'           => __( 'Update Podcast', 'alleypack' ),
				'add_new_item'          => __( 'Add New Podcast', 'alleypack' ),
				'new_item_name'         => __( 'New Podcast Name', 'alleypack' ),
				'add_or_remove_items'   => __( 'Add or remove podcasts', 'alleypack' ),
				'choose_from_most_used' => __( 'Choose from the most used podcasts', 'alleypack' ),
				'not_found'             => __( 'No podcasts found', 'alleypack' ),
				'no_terms'              => __( 'No podcasts', 'alleypack' ),
				'items_list_navigation' => __( 'Podcasts list navigation', 'alleypack' ),
				'items_list'            => __( 'Podcasts list', 'alleypack' ),
				'back_to_items'         => __( '&larr; Back to Podcasts', 'alleypack' ),
				'menu_name'             => __( 'Podcasts', 'alleypack' ),
				'name_admin_bar'        => __( 'Podcasts', 'alleypack' ),
			],
			'show_in_menu'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'meta_box_cb'       => false,
		];

		$args = apply_filters( 'alleypack_podcasts_taxonomy_args', $args );

		register_taxonomy(
			$this->taxonomy,
			[ $this->podcasts_post_type, $this->episode_post_type ],
			$args
		);
	}

	/**
	 * Register the Podcast post type.
	 */
	public function register_podcast_post_type() {

		// Ensure this is a new post type.
		if ( post_type_exists( $this->podcasts_post_type ) ) {
			return;
		}

		$args = [
			'labels'             => [
				'name'                     => __( 'Podcasts', 'alleypack' ),
				'singular_name'            => __( 'Podcasts', 'alleypack' ),
				'add_new'                  => __( 'Add New Podcasts', 'alleypack' ),
				'add_new_item'             => __( 'Add New Podcasts', 'alleypack' ),
				'edit_item'                => __( 'Edit Podcasts', 'alleypack' ),
				'new_item'                 => __( 'New Podcasts', 'alleypack' ),
				'view_item'                => __( 'View Podcasts', 'alleypack' ),
				'view_items'               => __( 'View Podcasts', 'alleypack' ),
				'search_items'             => __( 'Search Podcasts', 'alleypack' ),
				'not_found'                => __( 'No podcast series found', 'alleypack' ),
				'not_found_in_trash'       => __( 'No podcast series found in Trash', 'alleypack' ),
				'parent_item_colon'        => __( 'Parent Podcasts:', 'alleypack' ),
				'all_items'                => __( 'All Podcasts', 'alleypack' ),
				'archives'                 => __( 'Podcasts Archives', 'alleypack' ),
				'attributes'               => __( 'Podcasts Attributes', 'alleypack' ),
				'insert_into_item'         => __( 'Insert into podcast series', 'alleypack' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this podcast series', 'alleypack' ),
				'featured_image'           => __( 'Featured Image', 'alleypack' ),
				'set_featured_image'       => __( 'Set featured image', 'alleypack' ),
				'remove_featured_image'    => __( 'Remove featured image', 'alleypack' ),
				'use_featured_image'       => __( 'Use as featured image', 'alleypack' ),
				'filter_items_list'        => __( 'Filter podcast series list', 'alleypack' ),
				'items_list_navigation'    => __( 'Podcasts list navigation', 'alleypack' ),
				'items_list'               => __( 'Podcasts list', 'alleypack' ),
				'item_published'           => __( 'Podcasts published.', 'alleypack' ),
				'item_published_privately' => __( 'Podcasts published privately.', 'alleypack' ),
				'item_reverted_to_draft'   => __( 'Podcasts reverted to draft.', 'alleypack' ),
				'item_scheduled'           => __( 'Podcasts scheduled.', 'alleypack' ),
				'item_updated'             => __( 'Podcasts updated.', 'alleypack' ),
				'menu_name'                => __( 'Podcasts', 'alleypack' ),
			],
			'public'             => true,
			'publicly_queryable' => false,
			'has_single'         => false,
			'show_in_rest'       => true,
			'show_in_menu'       => false,
			'show_in_nav_menus'  => false,
			'has_archive'        => false,
			'supports'           => [
				'revisions',
				'title',
				'thumbnail',
			],
		];

		$args = apply_filters( 'alleypack_podcasts_podcasts_post_type_args', $args );

		register_post_type( $this->podcasts_post_type, $args ); // phpcs:ignore WordPress.NamingConventions.ValidPostTypeSlug.NotStringLiteral
	}

	/**
	 * Register the Episode post type.
	 */
	public function register_episode_post_type() {

		// Ensure this is a new post type.
		if ( post_type_exists( $this->episode_post_type ) ) {
			return;
		}

		$args = [
			'labels'             => [
				'name'                     => __( 'Podcast Episodes', 'alleypack' ),
				'singular_name'            => __( 'Podcast Episode', 'alleypack' ),
				'add_new'                  => __( 'Add New Episode', 'alleypack' ),
				'add_new_item'             => __( 'Add New Episode', 'alleypack' ),
				'edit_item'                => __( 'Edit Podcast Episode', 'alleypack' ),
				'new_item'                 => __( 'New Podcast Episode', 'alleypack' ),
				'view_item'                => __( 'View Podcast Episode', 'alleypack' ),
				'view_items'               => __( 'View Podcast Episodes', 'alleypack' ),
				'search_items'             => __( 'Search Podcast Episodes', 'alleypack' ),
				'not_found'                => __( 'No podcast episodes found', 'alleypack' ),
				'not_found_in_trash'       => __( 'No podcast episodes found in Trash', 'alleypack' ),
				'parent_item_colon'        => __( 'Parent Podcast Episode:', 'alleypack' ),
				'all_items'                => __( 'All Episodes', 'alleypack' ),
				'archives'                 => __( 'Podcast Episodes Archives', 'alleypack' ),
				'attributes'               => __( 'Podcast Episodes Attributes', 'alleypack' ),
				'insert_into_item'         => __( 'Insert into podcast episode', 'alleypack' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this podcast episode', 'alleypack' ),
				'featured_image'           => __( 'Featured Image', 'alleypack' ),
				'set_featured_image'       => __( 'Set featured image', 'alleypack' ),
				'remove_featured_image'    => __( 'Remove featured image', 'alleypack' ),
				'use_featured_image'       => __( 'Use as featured image', 'alleypack' ),
				'filter_items_list'        => __( 'Filter podcast episodes list', 'alleypack' ),
				'items_list_navigation'    => __( 'Podcast episodes list navigation', 'alleypack' ),
				'items_list'               => __( 'Podcast episodes list', 'alleypack' ),
				'item_published'           => __( 'Podcast episode published.', 'alleypack' ),
				'item_published_privately' => __( 'Podcast episode published privately.', 'alleypack' ),
				'item_reverted_to_draft'   => __( 'Podcast episode reverted to draft.', 'alleypack' ),
				'item_scheduled'           => __( 'Podcast episode scheduled.', 'alleypack' ),
				'item_updated'             => __( 'Podcast episode updated.', 'alleypack' ),
				'menu_name'                => __( 'Podcasts', 'alleypack' ),
			],
			'public'             => true,
			'publicly_queryable' => true,
			'has_single'         => false,
			'show_in_rest'       => true,
			'show_in_menu'       => true,
			'has_archive'        => false,
			'menu_icon'          => 'dashicons-format-status',
			'supports'           => [
				'revisions',
				'title',
				'thumbnail',
				'editor',
				'author',
			],
			'rewrite'            => [
				'slug' => 'podcasts/%podcast%',
			],
		];

		$args = apply_filters( 'alleypack_podcasts_episode_post_type_args', $args );

		register_post_type( $this->episode_post_type, $args ); // phpcs:ignore WordPress.NamingConventions.ValidPostTypeSlug.NotStringLiteral
	}

	/**
	 * Allows the podcast series to be included in the podcast episode's URL.
	 *
	 * @param string   $post_link The post's permalink.
	 * @param \WP_Post $post     The post in question.
	 *
	 * @return string The post's permalink.
	 */
	public function post_type_link( string $post_link, \WP_Post $post ) {

		if (
			empty( $post ) ||
			$this->episode_post_type !== $post->post_type
		) {
			return $post_link;
		}

		$terms = get_the_terms( $post, $this->taxonomy );

		if ( ! empty( $terms ) ) {
			return str_replace( '%podcast%', $terms[0]->slug, $post_link );
		}

		return $post_link;
	}

	/**
	 * Disable display of Gutenberg Post Setting UI for a specific
	 * taxonomy.
	 *
	 * @see https://github.com/WordPress/gutenberg/issues/6912#issuecomment-428403380
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param object           $taxonomy The original taxonomy object.
	 */
	public function hide_taxonomy_selector( $response, $taxonomy ) {
		if ( $this->taxonomy === $taxonomy->name ) {
			$response->data['visibility']['show_ui'] = false;
		}
		return $response;
	}

	/**
	 * Set up Series meta fields.
	 */
	public function series_fields() {

		// Return if Fieldmanager is not active.
		if ( ! class_exists( '\Fieldmanager_Field' ) ) {
			return;
		}

		$series_fields = [
			'description' => new \Fieldmanager_RichTextArea(
				[
					'label'      => __( 'Podcast/Show Description', 'alleypack' ),
					'attributes' => [
						'style' => 'width: 100%',
						'rows'  => 4,
					],
				]
			),
		];

		$series_fields = apply_filters( 'alleypack_podcasts_series_fields', $series_fields );

		// Build FM fields.
		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'podcast-episode',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => $series_fields,
			]
		);

		// Add meta box.
		$fm->add_meta_box(
			__( 'Series Settings', 'alleypack' ),
			[ $this->podcasts_post_type ],
			'normal',
			'high'
		);
	}

	/**
	 * Set up Episode meta fields.
	 */
	public function episode_fields() {

		// Return if Fieldmanager is not active.
		if ( ! class_exists( '\Fieldmanager_Field' ) ) {
			return;
		}

		$episode_fields = [
			'podcast_series'         => new \Fieldmanager_Select(
				__( 'Podcast Series', 'alleypack' ),
				[
					'datasource' => new \Fieldmanager_Datasource_Term(
						[
							'taxonomy' => $this->taxonomy,
						]
					),
				]
			),
			'feed_item_title'        => new \Fieldmanager_TextField( __( 'Title', 'alleypack' ) ),
			'feed_item_subtitle'     => new \Fieldmanager_TextField( __( 'Subtitle', 'alleypack' ) ),
			'feed_item_description'  => new \Fieldmanager_TextArea(
				[
					'label'      => __( 'Description', 'alleypack' ),
					'attributes' => [
						'style' => 'width: 100%',
						'rows'  => 4,
					],
				]
			),
			'feed_item_summary'      => new \Fieldmanager_TextArea(
				[
					'label'      => __( 'Summary', 'alleypack' ),
					'attributes' => [
						'style' => 'width: 100%',
						'rows'  => 4,
					],
				]
			),
			'feed_item_type'         => new \Fieldmanager_TextField(
				[
					'label'       => __( 'Type', 'alleypack' ),
					'description' => __( '`serial` or `episodic`', 'alleypack' ),
				]
			),
			'feed_item_episode_type' => new \Fieldmanager_TextField(
				[
					'label'       => __( 'Episode Type', 'alleypack' ),
					'description' => __( '`trailer`, `full`, or `bonus`', 'alleypack' ),
				]
			),
			'feed_item_episode'      => new \Fieldmanager_TextField( __( 'Episode', 'alleypack' ) ),
			'feed_item_season'       => new \Fieldmanager_TextField( __( 'Season', 'alleypack' ) ),
		];

		$episode_fields = apply_filters( 'alleypack_podcasts_episode_fields', $episode_fields );

		// Build FM fields.
		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'podcast-episode',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => $episode_fields,
			]
		);

		// Add meta box.
		$fm->add_meta_box(
			__( 'Feed Settings', 'alleypack' ),
			[ $this->episode_post_type ],
			'normal',
			'high'
		);
	}
}
