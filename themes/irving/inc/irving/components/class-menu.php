<?php
/**
 * Class file for Irving's Menu component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the Menu.
 */
class Menu extends \WP_Components\Component {
	use \WP_Components\WP_Menu;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'menu';
}
