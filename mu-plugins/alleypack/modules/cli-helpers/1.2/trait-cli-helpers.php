<?php
/**
 * CLI helpers trait.
 *
 * @package Alleypack.
 */

namespace Alleypack;

/**
 * Trait with misc helpers to modify and work with data.
 */
trait CLI_Helpers {

	/**
	 * Rename a meta key for a given post type.
	 *
	 * @param string $old_key   Old meta key.
	 * @param string $new_key   New meta key.
	 * @param string $post_type Post type.
	 */
	public function rename_post_meta_key( $old_key, $new_key, $post_type = 'post' ) {

		\WP_CLI::success( "Renaming meta `{$old_key}` to `{$new_key}` for all `{$post_type}` post types." );

		global $wpdb;

		$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"
				UPDATE $wpdb->postmeta
				LEFT JOIN $wpdb->posts
				ON $wpdb->posts.ID = $wpdb->postmeta.post_id
				SET meta_key = %s
				WHERE $wpdb->postmeta.meta_key = %s
				AND $wpdb->posts.post_type = %s
				",
				$new_key,
				$old_key,
				$post_type
			)
		);
	}

	/**
	 * Delete all meta by key for a given post type.
	 *
	 * @param string $key       Meta key.
	 * @param string $post_type Post type.
	 */
	public function delete_meta_key( $key, $post_type = 'post' ) {

		\WP_CLI::success( "Deleting meta `{$key}` in all `{$post_type}` post types." );

		global $wpdb;

		$wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->postmeta,
			[
				'meta_key' => $key,
			]
		);
	}

	/**
	 * Change post types.
	 *
	 * @param string $old_post_type Old post type.
	 * @param string $new_post_type New post type.
	 */
	public function change_post_type( $old_post_type, $new_post_type ) {

		// Ensure bulk task is available.
		if ( ! method_exists( $this, 'bulk_task' ) ) {
			\WP_CLI::error( 'Post bulk task function not available' );
		} else {
			\WP_CLI::success( "Migrating all post types `{$old_post_type}` to `{$new_post_type}`" );
		}

		$this->bulk_task(
			[
				'post_type' => $old_post_type,
			],
			function( $post ) use ( $new_post_type ) {
				wp_update_post(
					[
						'ID'        => $post->ID,
						'post_type' => $new_post_type,
					]
				);
				clean_post_cache( $post->ID );
			}
		);
	}

	/**
	 * Change meta value.
	 *
	 * @param string $key       Meta key.
	 * @param string $old_value Old value.
	 * @param string $new_value New value.
	 */
	public function change_meta_value( $key, $old_value, $new_value ) {

		// Ensure bulk task is available.
		if ( ! method_exists( $this, 'bulk_task' ) ) {
			\WP_CLI::error( 'Post bulk task function not available' );
		} else {
			\WP_CLI::success( "Updating `{$key}` values from `{$old_value}` to `{$new_value}`" );
		}

		$this->bulk_task(
			[
				'meta_key'   => $key,
				'meta_value' => $old_value, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			],
			function( $post ) use ( $key, $new_value, $old_value ) {
				update_post_meta( $post->ID, $key, $new_value );
				\WP_CLI::success( "Changed {$key} from {$old_value} to {$new_value} for Post ID: {$post->ID}." );
			}
		);
	}

	/**
	 * Replace a string within a meta value.
	 *
	 * @param string $key     Meta key.
	 * @param string $search  Search value.
	 * @param string $replace Replace value.
	 */
	public function str_replace_meta_value( $key, $search, $replace ) {

		// Ensure bulk task is available.
		if ( ! method_exists( $this, 'bulk_task' ) ) {
			\WP_CLI::error( 'Post bulk task function not available' );
		} else {
			\WP_CLI::success( "Replacing `{$key}` values containing `{$search}` to `{$replace}`" );
		}

		$this->bulk_task(
			[
				'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					[
						'key'     => $key,
						'compare' => 'EXISTS',
					],
				],
			],
			function( $post ) use ( $key, $search, $replace ) {
				$value = get_post_meta( $post->ID, $key, true );
				if ( false !== strpos( $value, $search ) ) {
					$new_value = str_replace( $search, $replace, $value );
					update_post_meta( $post->ID, $key, $new_value );
					\WP_CLI::success( "Updated {$key} from {$value} to {$new_value} for Post ID: {$post->ID}." );
				}
			}
		);
	}
}
