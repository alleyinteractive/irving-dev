<?php
/**
 * Class file for Irving's Content Card component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the content card.
 */
class Content_Card extends \WP_Components\Component {
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-card';
}
