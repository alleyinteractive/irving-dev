<?php
/**
 * Gutenberg
 *
 * @package Irving_Dev
 */

namespace Irving_Dev;

add_filter(
	'wp_components_block_components',
	function () {
		return [
			'core/paragraph' => '\Irving_Dev\Components\Gutenberg_Block'
		];
	}
);

add_filter(
	'wp_components_block_render_exceptions',
	function ( $block_exceptions ) {
		return array_merge(
			$block_exceptions,
			[
				'core/paragraph',
			]
		);
	}
);
