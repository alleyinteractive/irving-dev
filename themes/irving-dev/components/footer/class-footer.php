<?php
/**
 * Footer component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Footer;

/**
 * Class for the Footer component.
 */
class Footer extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children(): array {
		return [];
	}
}
