# Breaking News Alerts

## Description

Rich text news alerts which can be scheduled/unscheduled.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'alerts', '1.0' );
```

### Filter the locations

```php
/**
 * Define alert location options.
 *
 * @param array $options Options.
 * @return array
 */
function alert_location_term_options( array $options ): array {
	return [
		'homepage'        => __( 'My Theme\'s Homepage', 'my-theme' ),
		'post-right-rail' => __( 'Single Posts Right Rail', 'my-theme' ),
		'everywhere'      => __( 'Everywhere', 'my-theme' ),
	];
}
add_filter( 'alleypack_alert_location_term_options', 'alert_location_term_options' );
```

### Display the latest published alert on the homepage

```php
$alert = \Alleypack\get_alert_by_locations( [ 'homepage', 'everywhere' ] );
echo wp_kses_post( $alert );
```

## Filters
`alleypack_alert_post_type` - filter the post type
`alleypack_alert_post_type_args` - filter the post type registration args
`alleypack_alert_location_taxonomy` - filter the taxonomy
`alleypack_alert_location_taxonomy_args` - filter the taxonomy registration args
`alleypack_alert_location_term_options` - filter the terms in the location taxonomy
