<?php
/**
 * Attachments module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

/**
 * Create an attachment from an URL address.
 *
 * @param string $url Fully qualified URL.
 * @param array  $args {
 *        Optional. Arguments for the attachment. Default empty array.
 *
 *        @type string      $alt            Alt text.
 *        @type string      $caption        Caption text.
 *        @type string      $description    Description text.
 *        @type array       $meta           Associate array of meta to set.
 *                                          The value of alt text will
 *                                          automatically be mapped into
 *                                          this value and will be
 *                                          overridden by the alt explicitly
 *                                          passed into this array.
 *        @type null|int    $parent_post_id Parent post id.
 *        @type null|string $title          Title text. Null defaults to the
 *                                          sanitized filename.
 * }
 * @return int|WP_Error Attachment ID or \WP_Error.
 */
function create_attachment_from_url( $url, $args = [] ) {

	// Parse arguments.
	$args = wp_parse_args(
		$args,
		[
			'alt'            => '',
			'caption'        => '',
			'description'    => '',
			'meta'           => [],
			'parent_post_id' => null,
			'title'          => null,
		]
	);

	// Parse meta arguments. This ensure $args['meta'] is a properly formatted
	// array, and also moves the alt text value so it can be set during the
	// same loop as the rest of the meta.
	$args['meta'] = wp_parse_args(
		$args['meta'],
		[
			'alt'                              => $args['alt'],
			'alleypack_attachments_legacy_url' => $url,
		]
	);

	// Load class if it hasn't already loaded.
	if ( ! class_exists( 'WP_Http' ) ) {
		include_once( ABSPATH . WPINC . '/class-http.php' );
	}

	// Execute and validate the request.
	$http     = new \WP_Http();
	$response = $http->request( $url );

	if ( is_wp_error( $response ) ) {
		return new \WP_Error(
			'attachment',
			__( 'Invalid response.', 'alleypack' ),
			[
				'args'     => $args,
				'response' => $response,
				'url'      => $url,
			]
		);
	}

	if ( 200 !== $response['response']['code'] ) {
		return new \WP_Error(
			'attachment',
			__( 'Invalid response code.', 'alleypack' ),
			[
				'args'     => $args,
				'response' => $response,
				'url'      => $url,
			]
		);
	}

	// Uploading from raw data.
	$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
	if ( ! empty( $upload['error'] ) ) {
		return new \WP_Error(
			'attachment',
			$upload['error'],
			[
				'args'   => $args,
				'upload' => $upload,
				'url'    => $url,
			]
		);
	}

	// Set properties needed to insert the attachment.
	$file_path        = $upload['file'];
	$file_name        = basename( $file_path );
	$file_type        = wp_check_filetype( $file_name, null );
	$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
	$wp_upload_dir    = wp_upload_dir();

	// Build array for the attachment post object.
	$attachment_data = [
		'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
		'meta_input'     => $args['meta'],
		'post_content'   => (string) ( $args['description'] ?? '' ), // Default to an empty string.
		'post_excerpt'   => (string) ( $args['caption'] ?? '' ), // Default to empty string.
		'post_mime_type' => $file_type['type'],
		'post_status'    => 'inherit',
		'post_title'     => (string) ( $args['title'] ?? $attachment_title ), // Default to the attachment title.
	];

	// Create the attachment.
	$attachment_id = wp_insert_attachment( $attachment_data, $file_path, $args['parent_post_id'] );

	// Include admin image helpers.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Define attachment metadata.
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path );

	// Assign metadata to attachment.
	wp_update_attachment_metadata( $attachment_id, $attachment_data );

	return $attachment_id;
}
