<?php
/**
 * This file holds configuration settings and functions for media, including
 * image sizes and custom field handling.
 *
 * @package Irving
 */

namespace Irving;

\WP_Irving\Component\Image::register_breakpoints( [
	'xxl' => '90rem',
	'xl' => '80rem',
	'lg' => '64rem',
	'md' => '48rem',
	'sm' => '32rem',
] );

\WP_Irving\Component\Image::register_crop_sizes( [
	'16:9' => [
		'card' => [
			'height' => 1920,
			'width'  => 1080,
		],
	],
] );

/**
 * Register image sizes for use by the Image component.
 */
\WP_Irving\Component\Image::register_sizes( [
	'feature' => [
		'sources' => [
			[
				'transforms' => [
					'resize' => [ 1920, 1080 ],
				],
				'descriptor' => 1920,
				'media' => [ 'min' => 'xxl' ],
			],
			[
				'transforms' => [
					'resize' => [ 1440, 810 ],
				],
				'descriptor' => 1440,
				'media' => [ 'min' => 'xl' ],
			],
		],
	],
] );
