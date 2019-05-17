<?php
/**
 * Path Dispatch module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

// Load class.
require_once 'class-path-dispatch.php';

/**
 * Return the path dispatch instance.
 *
 * @return Path_Dispatch
 */
function path_dispatch() {
	return Path_Dispatch::instance();
}
