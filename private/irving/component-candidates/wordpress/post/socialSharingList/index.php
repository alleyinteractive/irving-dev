<?php
/**
 * Social Sharing List Component.
 *
 * Wrapper for social sharing items.
 *
 *
 * @package Irving_Components
 */

namespace WP_Irving;

use WP_Irving\Component;

if ( ! function_exists( '\WP_Irving\get_registry' ) ) {
	return;
}

/**
 * Get a Facebook sharing url.
 *
 * @return string | url for sharing
 */
function get_facebook_url() {
  return add_query_arg(
    [
      'u' => rawurlencode( get_permalink() ) ,
    ],
    'https://www.facebook.com/sharer.php/'
  );
};

/**
   * Get a Twitter sharing url.
   *
   * @return string | url for sharing
   */
function get_twitter_url() {
  return add_query_arg(
    [
      'text' => get_the_title(),
      'url'  => get_permalink(),
    ],
    'https://twitter.com/intent/tweet/'
  );
}

/**
   * Get a Whatsapp sharing url.
   *
   * @return string | the url
   */
function get_whatsapp_url() {
  return
    add_query_arg(
      [
        'text' => rawurlencode(
          sprintf(
            // Translators: %1$s - article title, %2$s - article url.
            esc_html__( 'Check out this story: %1$s %2$s', 'irving' ),
            get_the_title(),
            get_permalink()
          )
        ),
      ],
      'https://api.whatsapp.com/send/'
    );
}

/**
   * Get a LinkedIn sharing url.
   *
   * @return string | url
   */
function get_linkedin_url() {
  return
    add_query_arg(
          [
            'url'     => get_permalink(),
            'title'   => get_the_title(),
            'summary' => get_the_excerpt(),
          ],
          'https://www.linkedin.com/shareArticle/'
  );
}

/**
   * Get a Pinterest sharing url.
   *
   * @return string | url
   */
function get_pinterest_url() {
  return
    add_query_arg(
      [
        'url'         => get_permalink(),
        'media'       => rawurlencode( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ),
        'description' => get_the_excerpt(),
      ],
      'https://pinterest.com/pin/create/button/'
  );
}

/**
   * Get an Email sharing url.
   * Sets the subject to the item's title, and
   * the email body to the URL of the item being shared.
   *
   * @return string | url
   */
function get_email_url() {
  return
    add_query_arg(
      [
        'subject' => get_the_title(),
        'body'    => get_the_permalink(),
      ],
      'mailto:'
    );
}

/**
   * Get a Reddit sharing url.
   * Sets the Reddit post title to the item's title.
   *
   * @return string | url
   */
function get_reddit_url() {
  return
    add_query_arg(
          [
            'title' => get_the_title(),
            'url'   => get_permalink(),
          ],
          'http://www.reddit.com/submit'
    );
}

/**
 * Register the component and callback.
 */
get_registry()->register_component_from_config(
	__DIR__ . '/component',
	[
		'callback' => function( Component $component ): Component {

			// Get the post ID from a context provider, or fallback to the global.
			$post_id = $component->get_config( 'post_id' );
			if ( 0 === $post_id ) {
				$post_id = get_the_ID();
			}

			$post = get_post( $post_id );
			if ( ! $post instanceof \WP_Post ) {
				return $component;
			}

			$services = $component->get_config('services');
			foreach ($services as $service ) {
				if ( function_exists( "\WP_Irving\get_{$service}_url" ) ) {
					$component->append_child(
						( new Component( 'irving/social-sharing-item' ) )
							->merge_config(
								[
									'post_id' => $post_id,
									'service' => $service,
									'url'     => call_user_func( "\WP_Irving\get_{$service}_url" ),
								]
							)
					);
				}
			};

			return $component;
		},
	]
);
