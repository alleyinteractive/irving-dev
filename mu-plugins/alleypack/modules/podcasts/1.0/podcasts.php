<?php
/**
 * Podcasts module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

// Load dependencies.
\Alleypack\load_module( 'singleton', '1.0' );
\Alleypack\load_module( 'term-post-link', '1.0' );

// Load class.
require_once __DIR__ . '/class-podcasts.php';

// Enable.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Podcasts', 'instance' ] );
