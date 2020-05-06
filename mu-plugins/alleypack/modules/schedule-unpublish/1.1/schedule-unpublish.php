<?php
/**
 * Schedule Unpublish module.
 *
 * @package Alleypack
 * @version 1.1.0
 * @see readme.md
 */

namespace Alleypack;

// Load dependencies.
\Alleypack\load_module( 'singleton', '1.0' );

// Load class.
require_once __DIR__ . '/class-schedule-unpublish.php';

// Enable.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Schedule_Unpublish', 'instance' ] );
