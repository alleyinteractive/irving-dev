<?php
/**
 * Landing Pages module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

// Load dependencies.
\Alleypack\load_module( 'singleton', '1.0' );

// Load class.
require_once __DIR__ . '/class-landing-pages.php';

// Enable.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Landing_Pages', 'instance' ] );
