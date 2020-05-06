<?php
/**
 * Error Template component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Templates;

/**
 * Class for the Error template.
 */
class Error extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'error-template';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set(): self {
		$body = ( new \WP_Components\Body() )->append_children( array_filter( $this->get_components() ) );
		return $this->append_child( $body );
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components(): array {
		return [
			new \Irving_Dev\Components\Error_404(),
		];
	}
}
