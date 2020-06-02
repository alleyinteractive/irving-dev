<?php
/**
 * Gutenberg Block component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components;

/**
 * Class for the Gutenberg Block component.
 */
class Gutenberg_Block extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'gutenberg-block';

	/**
	 * Define a default config.
	 *
	 * @return array
	 */
	public function default_config(): array {
		return [];
	}
}
