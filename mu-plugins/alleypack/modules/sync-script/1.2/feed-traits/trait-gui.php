<?php
/**
 * Trait that enables a GUI sync option on feeds.
 *
 * @package Alleypack.
 */

namespace Alleypack\Sync_Script;

/**
 * GUI trait.
 */
trait GUI {

	/**
	 * Setup the GUI for the feed using this trait.
	 */
	public function setup_gui() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// Display success message.
		add_filter( 'query_vars', [ $this, 'add_gui_query_vars' ] );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {
		if ( apply_filters( 'alleypack_sync_enable_gui', '__return_false' ) ) {
			$this->enqueue_post_sync_button();
			$this->enqueue_term_sync_button();
			$this->enqueue_user_sync_button();
		}
	}

	/**
	 * Helper to register the post sync button.
	 */
	public function enqueue_post_sync_button() {

		// Get screen to determine what scripts should load.
		$screen = get_current_screen();

		// Handling for posts.
		$post_type = $this->get_feed_item_class()::$post_type ?? '';

		// Post list screen.
		if (
			! empty( $post_type )
			&& 'edit' === ( $screen->base ?? '' )
			&& ( $screen->post_type ?? '' ) === $this->feed_item_class::$post_type
		) {
			$this->enqueue_object_sync_button( $post_type );
			add_filter( 'post_row_actions', [ $this, 'post_row_actions' ], 10, 2 );
			add_filter( 'page_row_actions', [ $this, 'post_row_actions' ], 10, 2 );

			// Display sync success.
			add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		}
	}

	/**
	 * Helper to register the term sync button.
	 */
	public function enqueue_term_sync_button() {

		// Get screen to determine what scripts should load.
		$screen = get_current_screen();

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
	 * Helper to register the user sync button.* @return string
	 */
	public function enqueue_user_sync_button() {

		$screen = get_current_screen();

		$user_type = $this->feed_item_class::$user ?? '';

		if (
			! empty( $user_type )
			&& 'users' === ( $screen->base ?? '' )
		) {
			$this->enqueue_object_sync_button( $user_type );
		}
	}

	/**
	 * Enqueue assets for the object sync button.
	 *
	 * @param string $object_type Object type.
	 */
	public function enqueue_object_sync_button( $object_type ) {

		// Determine the button label.
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

		// Enqueue styles.
		wp_enqueue_style(
			"alleypack-sync-script-{$object_type}-button-css",
			get_module_url() . '/assets/css/objectSyncButton.css',
			[],
			'1.1'
		);

		// Localize settings.
		wp_localize_script(
			"alleypack-sync-script-{$object_type}-button-js",
			'alleypackSync',
			[
				'postType'     => $object_type,
				'objectPlural' => $plural,
				'endpoint'     => rest_url( $this->endpoint_namespace . "/sync/{$this->sync_slug}/" ),
				'limit'        => $this->endpoint_limit ?? 10,
			]
		);
	}

	/**
	 * Add a sync link to the post row.
	 *
	 * @param array    $actions Row actions.
	 * @param \WP_Post $post    Post object.
	 * @return array
	 */
	public function post_row_actions( $actions, $post ) {

		// Build url args.
		$args = [
			'post_id' => $post->ID,
		];

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri         = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
			$args['redirect_to'] = rawurlencode( site_url( wp_unslash( $request_uri ) ) );
		}

		// Add a sync button to every row action.
		$actions['sync'] = sprintf(
			'<a href="%1$s">Sync Post</a>',
			esc_url( $this->get_endpoint_url( $args ) )
		);

		return $actions;
	}

	/**
	 * Add a query var to check if sync has ran.
	 *
	 * @param array $vars Query vars.
	 */
	public function add_gui_query_vars( $vars ) {
		$vars[] = 'alleypack_sync';
		return $vars;
	}

	/**
	 * Admin notice on sync.
	 */
	public function admin_notice() {
		if ( ! get_query_var( 'alleypack_sync' ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Sync has ran successfully.', 'alleypack' ); ?></p>
		</div>
		<?php
	}

}
