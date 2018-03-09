<?php
/**
 * Cleanup functions.
 *
 * @package WP_Alley_React
 */

namespace Alley_React;

class Cleanup {

	use \Alley_React\Traits\Singleton;

	/**
	 * Initalize this class
	 */
	public function setup() {
		$this->decode_html_entities();
		$this->redirect_template_calls();
		$this->rss_feed_fixes();
	}


	/**
	 * Decode HTML entities as needed
	 */
	public function decode_html_entities() {
		if ( true === apply_filters( 'alley_react_decode_title', true ) ) {
			add_filter( 'the_title', function( $title ) {
				return html_entity_decode( $title );
			} );
		}
	}

	/**
	 * Redirect all template calls to the admin
	 *
	 * To disable:
	 * add_filter( 'alley_react_redirect_template_calls', '__return_false' );
	 */
	function redirect_template_calls() {

		// Allow disabling using a filter
		if ( true === apply_filters( 'alley_react_redirect_template_calls', true ) ) {

			// Redirect non-feed template calls
			if ( ! is_feed() ) {
				add_action( 'template_redirect', function() {
					wp_safe_redirect( admin_url() );
					exit();
				} );
			}
		}
	}

	/**
	 * Filter RSS links to use the app url.
	 */
	function rss_feed_fixes() {

		// Allow disabling using a filter
		if ( true === apply_filters( 'alley_react_rss_feed_fixes', true ) ) {

			// Disable default RSS loading
			remove_all_actions( 'do_feed_rss2' );

			// Replace with our own version
			add_action( 'do_feed_rss2', function() {

				// Load the RSS feed as a var
				ob_start();
				load_template( ABSPATH . WPINC . '/feed-rss2.php' );
				$content = ob_get_clean();

				// Check for an app url
				if ( defined( 'REACT_CLIENT_APP_URL' ) && REACT_CLIENT_APP_URL ) {

					// Replace urls
					$content = str_replace( home_url(), GR_APP_URL, $content );
				}

				echo $content;
			}, 10, 1 );
		}
	}

}

/**
 * Get the endpoint instance.
 *
 * @return Endpoints
 */
function cleanup() {
	return Cleanup::instance();
}

// Initialize after theme setup
add_action( 'after_setup_theme', __NAMESPACE__ . '\cleanup' );
