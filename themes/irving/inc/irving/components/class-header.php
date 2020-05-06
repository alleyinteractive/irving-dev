<?php
/**
 * Class file for Irving's Header component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the Header.
 */
class Header extends \WP_Components\Component {
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header';
}