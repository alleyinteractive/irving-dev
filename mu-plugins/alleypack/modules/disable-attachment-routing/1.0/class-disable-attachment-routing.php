<?php
/**
 * Remove routes for attachments.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * Class to disable attachment routing.
 */
class Disable_Attachment_Routing {

	use \Alleypack\Singleton;

	/**
	 * Setup.
	 */
	public function setup() {

		// Add filters/actions.
		add_filter( 'rewrite_rules_array', [ $this, 'remove_attachment_rewrite_rule' ] );
		add_filter( 'attachment_link', [ $this, 'remove_attachment_link' ] );
		add_action( 'pre_get_posts', [ $this, 'handle_attachment_pages' ] );
		add_action( 'admin_bar_menu', [ $this, 'remove_attachment_link_from_admin_bar' ], 100 );
	}

	/**
	 * Remove support for the attachment rewrite rule.
	 *
	 * @param array $rules Rewrite rules.
	 * @return array
	 */
	function remove_attachment_rewrite_rule( $rules ): array {
		foreach ( $rules as $regex => $query ) {
			if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
				unset( $rules[ $regex ] );
			}
		}
		return $rules;
	}

	/**
	 * Remove the attachment link.
	 *
	 * @param string $link Attachment link.
	 * @return string
	 */
	function remove_attachment_link( $link ): string {
		return '';
	}

	/**
	 * Ensure attachment pages return 404s.
	 *
	 * @param WP_Query $query WP_Query object.
	 */
	function handle_attachment_pages( $query ) {

		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if (
			$query->queried_object instanceof \WP_Post
			&& 'attachment' === get_post_type( $query->get_queried_object_id() )
		) {
			$query->set_404();
			status_header( 404 );
		}
	}

	/**
	 * Remove attachment link from admin bar.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar Admin bar class.
	 */
	function remove_attachment_link_from_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {

		if ( 'attachment' !== get_post_type() ) {
			return;
		}

		$wp_admin_bar->remove_node( 'view' );
	}
}
