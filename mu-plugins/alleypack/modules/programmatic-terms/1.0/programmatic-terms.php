<?php
/**
 * Programmatic Terms module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

// Load dependencies.
\Alleypack\load_module( 'one-timer', '1.0' );

// Load class.
require_once __DIR__ . '/class-programmatic-terms.php';

/**
 * Enable programmatic terms for a taxonomy.
 *
 * @param string $taxonomy Taxonomy.
 */
function create_programmatic_taxonomy( $taxonomy ) {
	new Programmatic_Terms( $taxonomy );
}
