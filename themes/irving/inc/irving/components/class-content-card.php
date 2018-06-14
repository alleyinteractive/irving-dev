<?php
/**
 * Class file for Irving's Content Card component.
 *
 * @package WP_Irving
 */

namespace WP_Irving\Component;

/**
 * Defines the components of the content card.
 */
class Content_Card extends Content_Item {
	use \Irving\Content_Item;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-card';
}

/**
 * Helper to get the component.
 *
 * @param  string $name     Component name or array of properties.
 * @param  array  $config   Component config.
 * @param  array  $children Component children.
 * @return Content_Card An instance of the Content_Card class.
 */
function content_card( $name = '', array $config = [], array $children = [] ) {
	return new Content_Card( $name, $config, $children );
}
