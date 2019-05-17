<?php
/**
 * Class file for Irving's Content component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the Content.
 */
class Content extends \WP_Components\Component {
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content';
}
