<?php
/**
 * CLI bulk task trait.
 *
 * @package Alleypack.
 */

namespace Alleypack;

/**
 * Core Trait for Bulk Task Operations
 *
 * @license GPLv2
 * @codingStandardsIgnoreFile
 */
trait CLI_Bulk_Task {
	/**
	 * Store the last max ID for bulk task pagination.
	 *
	 * @var integer
	 */
	protected $bulk_task_min_id;

	/**
	 * Stub for bulk task operation.
	 *
	 * @param array $args
	 * @param
	 */
	abstract protected function bulk_task( $args, $callable = null );

	/**
	 * Output the status of a bulk task.
	 *
	 * This includes a progress bar, page/total pages, and a rough approximation
	 * of the time remaining based on the average number of seconds per page
	 * that the task has taken.
	 *
	 * @param  integer $page Current page number.
	 * @param  integer $max  Total number of pages to process.
	 */
	protected function do_bulk_status( $page = 0, $max = 0 ) {
		static $start;
		if ( ! $start || ! $page ) {
			$start = microtime( true );
		}
		if ( ! $page || ! $max ) {
			return;
		}
		$seconds_per_page = ( microtime( true ) - $start ) / $page;
		printf(
			'%s%' . ( strlen( $max ) + 2 ) . "d/%d complete; %s remaining\r",
			$this->progress_bar( $page / $max ),
			$page,
			$max,
			date( 'H:i:s', ( $max - $page ) * $seconds_per_page )
		);
	}

	/**
	 * Get a progress bar given a percent completion.
	 *
	 * This is a bit nicer than WP_CLI's progress bar and it fits nicely with
	 * the bulk task status.
	 *
	 * @param  float $percent Percent complete, from 0.00 - 1.00.
	 * @return string
	 */
	protected function progress_bar( $percent ) {
		return sprintf( '  [%-50s]  ', str_repeat( '#', floor( $percent * 50 ) ) );
	}
}
