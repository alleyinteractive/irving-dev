<?php
/**
 * Header component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Header;

/**
 * Class for the Header component.
 */
class Header extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children(): array {
		return [];
	}
}
