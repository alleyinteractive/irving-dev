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
 * Create or get an already saved attachment from an URL address.
 *
 * @param string $src Fully qualified URL.
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
 * @param string $meta_key Meta key to store the original URL.
 *
 * @return string URL Attachment.
 */
function create_or_get_attachment_from_url(
	string $src,
	array $args = [],
	string $meta_key = 'alleypack_attachments_legacy_url'
) : string {
	$query = new \WP_Query(
		[
			'post_type'   => 'attachment',
			'fields'      => 'ids',
			'post_status' => 'any',
			'meta_key'    => $meta_key,
			'meta_value'  => $src, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		]
	);

	if ( empty( $query->posts ?? [] ) ) {
		$attachment_id = create_attachment_from_url( $src, $args );
		if ( ! is_wp_error( $attachment_id ) ) {
			update_post_meta( $attachment_id, $meta_key, $src );
		}
	} else {
		$attachment_id = current( $query->posts );
	}

	if ( ! is_wp_error( $attachment_id ) ) {
		$src = wp_get_attachment_url( $attachment_id );
	}

	return $src;
}

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
 *        @type boolean     $s3_upload      Action to upload to AWS S3 - S3 Uploads
 *                                          plugin must be active and proper constant
 *                                          keys added.
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
			's3_upload'      => false,
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

	if ( true === $args['s3_upload'] ) {
		foreach (
			[
				'S3_UPLOADS_BUCKET',
				'S3_UPLOADS_KEY',
				'S3_UPLOADS_SECRET',
			] as $constant ) {
			if ( ! defined( $constant ) ) { // phpcs:ignore WordPressVIPMinimum.Constants.ConstantString.NotCheckingConstantName
				return new \WP_Error(
					'attachment',
					__( 'Invalid response.', 'alleypack' ),
					[
						'args'     => $args,
						// translators: Warning about the constant.
						'response' => sprintf( __( 'The required constant %s is not defined.', 'alleypack' ), $constant ),
						'url'      => $url,
					]
				);
			}
		}

		// Set properties needed to insert the attachment.
		$month            = gmdate( 'm' );
		$year             = gmdate( 'Y' );
		$file_name        = basename( $url );
		$attachment_title = sanitize_file_name( $file_name );
		$attachment_type  = wp_check_filetype( $file_name );
		$file_path        = $year . '/' . $month . '/' . $file_name;
		$file             = 's3://' . S3_UPLOADS_BUCKET . '/uploads/' . $file_path;
		$guid             = $file;

		// Send to S3 Bucket.
		copy( $url, $file );
	} else {
		// Load files where needed.
		if ( ! class_exists( 'WP_Http' ) ) {
			include_once ABSPATH . WPINC . '/class-http.php';
		}
		require_once ABSPATH . 'wp-admin/includes/media.php';

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
		$attachment_type  = wp_check_filetype( $file_name, null );
		$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
		$wp_upload_dir    = wp_upload_dir();
		$guid             = $wp_upload_dir['url'] . '/' . $file_name;
	}

	// Build array for the attachment post object.
	$attachment_data = [
		'guid'           => $guid,
		'meta_input'     => $args['meta'],
		'post_content'   => (string) ( $args['description'] ?? '' ), // Default to an empty string.
		'post_excerpt'   => (string) ( $args['caption'] ?? '' ), // Default to empty string.
		'post_mime_type' => $attachment_type['type'] ?? '',
		'post_status'    => 'inherit',
		'post_title'     => (string) ( $args['title'] ?? $attachment_title ), // Default to the attachment title.
	];

	// Create the attachment.
	$attachment_id = wp_insert_attachment( $attachment_data, $file_path, $args['parent_post_id'] );

	// Include admin image helpers.
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Define attachment metadata.
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path );

	// Assign metadata to attachment.
	wp_update_attachment_metadata( $attachment_id, $attachment_data );

	return $attachment_id;
}

/**
 * Helper function that gets various file attributes from a raw url. Currently
 * supports length and mime type.
 *
 * @todo Rewrite to use wp_remote_get instead, if possible.
 *
 * @param string $url File url.
 * @return array File attributes.
 */
function get_file_attributes_from_url( $url ) : array {

	$attributes = [
		'length'    => '',
		'mime_type' => '',
	];

	/* phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_close,WordPress.WP.AlternativeFunctions.curl_curl_exec,WordPress.WP.AlternativeFunctions.curl_curl_getinfo,WordPress.WP.AlternativeFunctions.curl_curl_init,WordPress.WP.AlternativeFunctions.curl_curl_setopt */

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_HEADER, true );
	curl_setopt( $ch, CURLOPT_NOBODY, true );

	$content = curl_exec( $ch );

	// Get the mime_type. If it's text/html, it will also include a character
	// encoding, but as of writing this, we are only concerned with audio
	// files, so it should be okay.
	$attributes['mime_type'] = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );

	// Regex to capture the content length from the full response.
	preg_match( '/Content-Length: (\d+)/', $content, $match );
	$attributes['length'] = $match[1] ?? 0;

	curl_close( $ch );

	/* phpcs:enable */

	return $attributes;
}
