<?php
/**
 * Class file for Irving's Jumbotron component.
 *
 * @package Irving
 */

namespace Irving\Components;

/**
 * Defines the components of the jumbotron.
 */
class Jumbotron extends \WP_Components\Component {
	use \WP_Components\WP_Post;
	use \WP_Components\WP_Term;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'jumbotron';
}
