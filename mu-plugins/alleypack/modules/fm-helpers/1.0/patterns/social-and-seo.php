<?php
/**
 * Fieldmanager patterns for SEO and social fields.
 *
 * @package Alleypack\Fieldmanager\Patterns
 */

namespace Alleypack\Fieldmanager\Patterns;

/**
 * Fields related to audiences, SEO, sharing, and indexing. Works out of
 * the box with \WP_Components\Head. Also overlaps keys with WP SEO.
 *
 * @return array FM fields.
 */
function get_seo_and_social_fields() {
	return [
		'seo'               => new \Fieldmanager_Group(
			[
				'label'          => __( 'SEO Settings', 'alleypack' ),
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'collapsed'      => true,
				'children'       => [
					'_meta_title'       => new \Fieldmanager_TextField( __( 'Title Tag', 'alleypack' ) ),
					'_meta_description' => new \Fieldmanager_TextArea(
						[
							'label'      => __( 'Meta Description', 'alleypack' ),
							'attributes' => [
								'style' => 'width: 100%;',
								'rows'  => 5,
							],
						]
					),
					'_meta_keywords'    => new \Fieldmanager_TextArea(
						[
							'label'      => __( 'Meta Keywords', 'alleypack' ),
							'attributes' => [
								'style' => 'width: 100%;',
								'rows'  => 5,
							],
						]
					),
				],
			]
		),
		'social'            => new \Fieldmanager_Group(
			[
				'label'          => __( 'Social Media Settings', 'alleypack' ),
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'collapsed'      => true,
				'children'       => [
					'social_title'       => new \Fieldmanager_TextField( __( 'Social Title', 'alleypack' ) ),
					'social_description' => new \Fieldmanager_TextArea(
						[
							'label'      => __( 'Social Description', 'alleypack' ),
							'attributes' => [
								'style' => 'width: 100%;',
								'rows'  => 5,
							],
						]
					),
					'social_image_id'    => new \Fieldmanager_Media( __( 'Social Image', 'alleypack' ) ),
				],
			]
		),
		'advanced_settings' => new \Fieldmanager_Group(
			[
				'label'          => __( 'Advanced Settings', 'alleypack' ),
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'collapsed'      => true,
				'children'       => [
					'canonical_url'   => new \Fieldmanager_TextField(
						[
							'label'       => __( 'Canonical URL', 'alleypack' ),
							'description' => __( 'This is the original URL of syndicated content.', 'alleypack' ),
						]
					),
					'de_index_google' => new \Fieldmanager_Checkbox(
						[
							'label'       => __( 'De-index in search engines', 'alleypack' ),
							'description' => __( 'This will prevent search engines from indexing this content.', 'alleypack' ),
						]
					),
				],
			]
		),
	];
}
