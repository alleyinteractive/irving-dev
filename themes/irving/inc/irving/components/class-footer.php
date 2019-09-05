<?php
/**
 * Class file for Irving's Footer component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the Footer.
 */
class Footer extends \WP_Components\Component {
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer';
}
