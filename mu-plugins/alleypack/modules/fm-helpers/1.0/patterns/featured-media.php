<?php
/**
 * Fieldmanager patterns for featured media fields.
 *
 * @package Alleypack\Fieldmanager\Patterns
 */

namespace Alleypack\Fieldmanager\Patterns;

/**
 * Fields related to featured media.
 *
 * @return array FM fields.
 */
function get_featured_media_fields() {
	$fields = [
		'_group_title'        => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
			[
				'description' => sprintf(
					'<h2 style="font-size: 24px; font-style: normal; padding: 0px;">%1$s</h2>',
					__( 'Featured Media', 'alleypack' )
				),
			]
		),
		'_thumbnail_id'       => new \Fieldmanager_Media(
			[
				'label'       => __( 'Featured Image', 'alleypack' ),
				'description' => __( 'Select an image to be used as the article thumbnail on the homepage, term archives, search results, and other archives.', 'alleypack' ),
			]
		),
		'divider'             => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
			[
				'description' => '<hr/>',
			]
		),
		'featured_media_type' => new \Fieldmanager_Select(
			[
				'label'   => __( 'Select media to display at the top of this article.', 'alleypack' ),
				'options' => [
					'image' => __( 'Image (default)', 'alleypack' ),
					'video' => __( 'Video', 'alleypack' ),
					'none'  => __( 'None', 'alleypack' ),
				],
			]
		),
		'featured_image_id'   => new \Fieldmanager_Media(
			[
				'label'       => __( 'Override Featured Image for the full article.', 'alleypack' ),
				'description' => __( 'If empty, the Featured Image set above will be used.', 'alleypack' ),
				'display_if'  => [
					'src'   => 'featured_media_type',
					'value' => 'image',
				],
			]
		),
		'video_url'           => new \Fieldmanager_Link(
			[
				'label'       => __( 'Video URL', 'alleypack' ),
				'description' => __( 'E.g. https://www.youtube.com/watch?v=abc123', 'alleypack' ),
				'display_if'  => [
					'src'   => 'featured_media_type',
					'value' => 'video',
				],
			]
		),
	];
	return apply_filters( 'alleypack_fm_helpers_featured_media', $fields );
}
