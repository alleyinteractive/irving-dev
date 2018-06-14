<?php
/**
 * Class file for Irving's Jumbotron component.
 *
 * @package WP_Irving
 */

namespace WP_Irving\Component;

/**
 * Defines the components of the jumbotron.
 */
class Jumbotron extends Content_Item {
	use \Irving\Content_Item;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'jumbotron';
}

/**
 * Helper to get the component.
 *
 * @param  string $name     Component name or array of properties.
 * @param  array  $config   Component config.
 * @param  array  $children Component children.
 * @return Jumbotron An instance of the Jumbotron class.
 */
function jumbotron( $name = '', array $config = [], array $children = [] ) {
	return new Jumbotron( $name, $config, $children );
}
