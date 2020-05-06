<?php
/**
 * Load 1.0 of the Term Post Link module.
 *
 * @package Alleypack
 * @version 1.0
 */

namespace Alleypack;

// Load class.
require_once __DIR__ . '/class-term-post-link.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/class-term-post-link-cli.php';
}

/**
 * Create a new link between a taxonomy and a post type.
 *
 * @param  string $taxonomy  Taxonomy to link.
 * @param  string $post_type Post type to link.
 */
function create_term_post_link( $taxonomy, $post_type ) {
	new Term_Post_Link( $taxonomy, $post_type );
}
