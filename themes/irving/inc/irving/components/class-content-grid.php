<?php
/**
 * Class file for Irving's Content Grid component.
 *
 * @package WP_Irving
 */

namespace WP_Irving\Component;

/**
 * Defines the components of the content grid.
 */
class Content_Grid extends Component {
	use \WP_Irving\Content_List;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-grid';
}

/**
 * Helper to get the content grid component.
 *
 * @param  string $name     Component name or array of properties.
 * @param  array  $config   Component config.
 * @param  array  $children Component children.
 * @return Content_Grid An instance of the Content_Grid class.
 */
function content_grid( $name = '', array $config = [], array $children = [] ) {
	return new Content_Grid( $name, $config, $children );
}
