<?php
/**
 * Manage feeds.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * Feed_Manager class.
 */
class Feed_Manager {

	use \Alleypack\Singleton;

	/**
	 * Array of all registered feed instances, mapped by feed sync slug.
	 *
	 * @var array
	 */
	protected $feeds = [];

	/**
	 * Register a new post/term/user sync class.
	 *
	 * @param string $feed_class Post Sync class to register.
	 */
	public function register_feed( string $feed_class ) {

		// Hook into init after post types and taxonomies have been registered.
		add_action(
			'init',
			function() use ( $feed_class ) {

				// Check if class actually exists, or die.
				if ( ! class_exists( $feed_class ) ) {
					wp_die(
						sprintf(
							// translators: %1$s feed class.
							esc_html__( 'Class %1$s not found.', 'alleypack' ),
							esc_html( $feed_class )
						)
					);
				}

				// Initialize a new instance.
				$feed = new $feed_class();
				$feed->get_feed_item_class()::set_feed( $feed );

				// Add the feed to the manager for furture reference.
				\Alleypack\Sync_Script\feed_manager()->add_feed( $feed );
			},
			11
		);
	}

	/**
	 * Add a feed instance to the feeds array.
	 *
	 * @param object $feed Feed class instance.
	 */
	protected function add_feed( $feed ) {
		$this->feeds[ $feed->get_sync_slug() ] = $feed;
	}

	/**
	 * Get a feed instance from the feeds array.
	 *
	 * @param string $feed Feed slug.
	 * @return null|object
	 */
	public function get_feed( $feed ) {
		return $this->feeds[ $feed ] ?? null;
	}
}
