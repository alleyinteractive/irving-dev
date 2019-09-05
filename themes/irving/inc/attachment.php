<?php
/**
 * Attachment
 *
 * @package Irving
 */

namespace Irving;

/**
 * Remove support for the attachment rewrite rule.
 *
 * @param array $rules Rewrite rules.
 * @return array
 */
function remove_attachment_rewrite_rule( $rules ) : array {
	foreach ( $rules as $regex => $query ) {
		if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
			unset( $rules[ $regex ] );
		}
	}
	return $rules;
}
add_filter( 'rewrite_rules_array', __NAMESPACE__ . '\remove_attachment_rewrite_rule' );

/**
 * Remove the attachment link.
 *
 * @param string $link Attachment link.
 * @return string
 */
function remove_attachment_link( $link ) {
	return '';
}
add_filter( 'attachment_link', __NAMESPACE__ . '\remove_attachment_link' );

/**
 * Remove attachment link from admin bar.
 *
 * @param \WP_Admin_Bar $wp_admin_bar Admin bar class.
 */
function remove_attachment_link_from_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
	// Only on the attachment post type.
	if ( 'attachment' === get_post_type() ) {
		$wp_admin_bar->remove_node( 'view' );
	}
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\remove_attachment_link_from_admin_bar', 100 );
