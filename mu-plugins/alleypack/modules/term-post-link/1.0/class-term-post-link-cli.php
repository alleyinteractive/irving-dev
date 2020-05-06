<?php
/**
 * Class that adds the term post link CLI.
 *
 * @package Alleypack
 * @version 1.0
 */

namespace Alleypack;

/* phpcs:disable WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli */

/**
 * Term_Post_Link_CLI class.
 */
class Term_Post_Link_CLI extends \WP_CLI_Command {

	/**
	 * Create term linked posts.
	 *
	 * [--dry-run]
	 * : If present, no term link will be created.
	 *
	 * @param array $args       Arguments.
	 * @param array $assoc_args Assoc arguments.
	 */
	public function recreate_term_posts( $args, $assoc_args ) {
		$dry_run    = ! empty( $assoc_args['dry-run'] );
		$taxonomies = \Alleypack\Term_Post_Link::$taxonomies;

		if ( empty( $taxonomies ) ) {
			\WP_CLI::error( 'There is no term post link set up.' );
		}

		foreach ( $taxonomies as $taxonomy ) {

			// Check if taxonomy exists first.
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			// Get all terms from this taxonomy.
			$term_query = new \WP_Term_Query(
				[
					'taxonomy'   => $taxonomy,
					'orderby'    => 'parent',
					'hide_empty' => false,
					'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						[
							'key'     => '_linked_post_id',
							'compare' => 'NOT EXISTS',
						],
					],
				]
			);

			// Loop and create post for each term.
			if ( empty( $term_query->terms ) ) {
				continue;
			}

			\WP_CLI::log(
				sprintf(
					// translators: taxonomy slug.
					__( '%s taxonomy.', 'alleypack' ),
					$taxonomy
				)
			);

			foreach ( $term_query->terms as $term ) {

				// Validate.
				if ( ! $term instanceof \WP_Term ) {
					continue;
				}

				if ( ! $dry_run ) {
					\Alleypack\Term_Post_Link::create_linked_post( $term->term_id );
				} else {
					\WP_CLI::log(
						sprintf(
							'Term %s would have a linked post created.',
							$term->name
						)
					);
				}
			}

			\WP_CLI::log( '-------------------' );
		}

		\WP_CLI::success( 'Finished! \o/' );
	}
}

\WP_CLI::add_command( 'term-post-link', __NAMESPACE__ . '\Term_Post_Link_CLI' );
