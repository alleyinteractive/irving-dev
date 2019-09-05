<?php
/**
 * Class file for Irving's Content Grid component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the content grid.
 */
class Content_Grid extends \WP_Components\Component {
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-grid';
}
