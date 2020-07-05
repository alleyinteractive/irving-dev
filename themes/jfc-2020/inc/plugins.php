<?php
/**
 * Load and customize plugins
 *
 * @package JFC_2020
 */

namespace JFC_2020;

/**
 * Force Jetpack to use staging mode outside of Pantheon's production environment.
 */
if ( WP_Utils::is_pantheon_env() && 'live' !== WP_Utils::get_pantheon_env() ) {
	add_filter( 'jetpack_is_staging_site', '__return_true' );
}
