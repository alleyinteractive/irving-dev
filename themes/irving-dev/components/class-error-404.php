<?php
/**
 * Error 404 component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components;

/**
 * Class for the Error 404 component.
 */
class Error_404 extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'error-404';

	/**
	 * Define a default config.
	 *
	 * @return array
	 */
	public function default_config(): array {
		return [];
	}
}
