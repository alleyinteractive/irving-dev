<?php
/**
 * Class file for Irving's Post Wrapper component.
 *
 * @package WP_Irving
 */

namespace WP_Irving\Component;

/**
 * Defines the components of the admin bar.
 */
class Post_Wrapper extends Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'post-wrapper';
}

/**
 * Helper to get the post wrapper component.
 *
 * @param  string $name     Component name or array of properties.
 * @param  array  $config   Component config.
 * @param  array  $children Component children.
 * @return Post_Wrapper An instance of the Post_Wrapper class.
 */
function post_wrapper( $name = '', array $config = [], array $children = [] ) {
	return new Post_Wrapper( $name, $config, $children );
}
