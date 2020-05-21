<?php
/**
 * Article Template Component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Templates;

use Irving_Dev\Components\Admin_Bar\Admin_Bar;

// add_filter( 'show_admin_bar', '__return_true' ); // temp bc not properly logged in

/**
 * Class for the Article template.
 */
class Article extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'article-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set(): self {
		$body = ( new \WP_Components\Body() )->append_children( array_filter( $this->get_components() ) );
		return $this->append_child( $body );
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components(): array {
		return [
			( new Admin_Bar )->set_post( $this->wp_post_get_id() ),
			( new \WP_Components\HTML() )->set_config( 'content', $this->post->post_content ),
		];
	}
}
