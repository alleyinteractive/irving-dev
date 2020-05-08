<?php
/**
 * Homepage Template Component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Templates;

/**
 * Class for the Homepage template.
 */
class Homepage extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'homepage-template';

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function default_children(): array {
		return [
			( new \WP_Components\Body() )
				->append_children(
					[
						( new \WP_Components\HTML() )->set_config( 'content', esc_html__( 'this is your homepage', 'irving-dev' ) ),
					]
				),
		];
	}
}
