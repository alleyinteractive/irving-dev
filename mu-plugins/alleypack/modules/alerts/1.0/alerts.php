<?php
/**
 * Breaking News alerts module.
 *
 * @package Alleypack
 * @version 1.0.0
 * @see readme.md
 */

namespace Alleypack;

// Load dependencies.
\Alleypack\load_module( 'programmatic-terms', '1.0' );
\Alleypack\load_module( 'schedule-unpublish', '1.0' );
\Alleypack\load_module( 'singleton', '1.0' );

// Load class.
require_once __DIR__ . '/class-alerts.php';

// Enable.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Alerts', 'instance' ] );

/**
 * Get the most recent alert by location(s).
 *
 * @param array $locations Locations.
 * @return string|null
 */
function get_alert_by_locations( array $locations ): ?string {
	return \Alleypack\Alerts::instance()->get_alert_by_locations( $locations );
}
