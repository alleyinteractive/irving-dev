<?php

WP_CLI::add_command( 'irving-dev', 'Irving_Dev_CLI_Command' );

class Irving_Dev_CLI_Command extends WP_CLI_Command {

	/**
	 * Prevent memory leaks from growing out of control
	 */
	function contain_memory_leaks() {
		global $wpdb, $wp_object_cache;
		$wpdb->queries = array();
		if ( ! is_object( $wp_object_cache ) ) {
			return;
		}
		$wp_object_cache->group_ops = array();
		$wp_object_cache->stats = array();
		$wp_object_cache->memcache_debug = array();
		$wp_object_cache->cache = array();
		if ( method_exists( $wp_object_cache, '__remoteset' ) ) {
			$wp_object_cache->__remoteset();
		}
	}

	/**
	 * Cleanup command when a new DB is cloned down to staging.
	 *
	 * ## EXAMPLES
	 *
	 * wp irving-dev post_db_load_staging_cleanup
	 */
	public function post_db_load_staging_cleanup() {
		update_option( 'home', 'https://irving-staging.herokuapp.com' );
		WP_CLI::runcommand( 'search-replace "https://irving-production.herokuapp.com" "https://irving-staging.herokuapp.com"' );
		wp_cache_flush();
	}
}