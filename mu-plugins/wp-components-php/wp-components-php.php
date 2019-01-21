<?php
/**
 * Plugin Name:     WP Components PHP
 * Plugin URI:      alley.co
 * Description:     Build WordPress themes using Components.
 * Author:          jameswalterburke
 * Text Domain:     wp-components
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WP_Component_PHP
 */

namespace WP_Component\PHP;

define( 'WP_COMPONENTS_PHP_ASSET_PATH', get_stylesheet_directory() . '/client/build' );

// Load classes.
require_once 'inc/classes/class-renderable.php';
require_once 'inc/classes/class-render-controller.php';
require_once 'template-tags.php';
