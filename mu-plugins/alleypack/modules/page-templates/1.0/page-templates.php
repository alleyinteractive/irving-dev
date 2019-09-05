<?php
/**
 * Page Templates module.
 *
 * @package Alleypack\Page_Templates
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack\Page_Templates;

\Alleypack\load_module( 'singleton', '1.0' );

// Load classes.
require_once 'class-page-templates.php';

/**
 * Register a new page template.
 *
 * @param string $name   Template name.
 * @param string $slug   Template slug.
 * @param array  $fields Array of FM fields.
 */
function register( string $name, string $slug, array $fields = [] ) {
	Page_Templates::instance()->register_page_template( $name, $slug, $fields );
}
