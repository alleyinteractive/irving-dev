<?php
/**
 * Block Converter Loader.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack\Block;

// Load Alleypack dependencies.
\Alleypack\load_module( 'attachments', '1.0' );

// Load class.
require_once __DIR__ . '/class-converter.php';
