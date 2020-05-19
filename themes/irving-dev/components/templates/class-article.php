<?php
/**
 * Article Template Component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Templates;

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
			( new \WP_Components\Gutenberg_Content() )
				->set_post( $this->post )
		];
	}
}
