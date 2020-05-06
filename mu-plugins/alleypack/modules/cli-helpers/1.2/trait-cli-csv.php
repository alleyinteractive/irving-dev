<?php
/**
 * CLI CSV trait.
 *
 * @package Alleypack.
 */

namespace Alleypack;

use \WP_CLI;

/**
 * Trait for CSV Operations
 */
trait CLI_CSV {

	/**
	 * Given a path and a field definition, extracts and validates CSV data into
	 * an array of objects.
	 *
	 * @param string $path   The path to extract CSV data from.
	 * @param array  $fields The field definition to validate.
	 * @return array
	 */
	private function get_csv_data( string $path, array $fields ) : array {

		// Ensure the file exists.
		if ( ! file_exists( $path ) ) {
			WP_CLI::error( __( 'The filepath you specified does not exist.', 'alleypack' ) );
		}

		// Parse the CSV.
		$data = array_map( 'str_getcsv', file( $path ) );
		if ( empty( $data ) ) {
			WP_CLI::error( __( 'Could not read CSV data from the specified file.', 'alleypack' ) );
		}

		// Extract and validate the key.
		$keys = array_flip( array_shift( $data ) );
		ksort( $keys );
		sort( $fields );
		if ( array_keys( $keys ) !== $fields ) {
			\WP_CLI::error( __( 'Columns in provided CSV do not match required columns.', 'alleypack' ) );
		}

		// Remap the data as objects.
		foreach ( $data as &$row ) {
			$new_row = new \stdClass();
			foreach ( $keys as $key => $index ) {
				$sanitized_key           = preg_replace(
					'/[^A-Za-z0-9_]/',
					'',
					str_replace( [ ' ', '/' ], '_', $key )
				);
				$new_row->$sanitized_key = $row[ $index ];
			}
			$row = $new_row;
		}

		return $data;
	}
}
