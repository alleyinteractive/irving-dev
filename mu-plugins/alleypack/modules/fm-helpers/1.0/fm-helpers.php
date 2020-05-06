<?php
/**
 * Fieldmanager fields, patterns, and helpers..
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack\Fieldmanager;

// Custom fields.
require_once __DIR__ . '/fields/class-fieldmanager-content.php';
require_once __DIR__ . '/fields/class-fieldmanager-excerpt.php';
require_once __DIR__ . '/fields/class-fieldmanager-post-content.php';
require_once __DIR__ . '/fields/class-fieldmanager-rich-post-content.php';

// FM patterns.
require_once __DIR__ . '/patterns/featured-media.php';
require_once __DIR__ . '/patterns/social-and-seo.php';
require_once __DIR__ . '/patterns/terms.php';
