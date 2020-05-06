# Schedule Unpublish

## Description

Schedule posts to unpublish in the future.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'schedule-unpublish', '1.1' );
```

### Enable it for post type(s) and specify the meta key to be used.

By default it is enabled for the `post` post type, but this can be changed via filter:
```php
/**
 * Get the mapping of post types to meta keys for the unpublish module.
 *
 * @return array
 */
function get_schedule_unpublish_mapping(): array {
	return [
		'my-cpt'         => \Alleypack\Schedule_Unpublish::DEFAULT_META_KEY, // Use the default meta key.
		'my-second-cpt'  => 'end-date', // Use a custom meta key.
	];
}
add_filter( 'alleypack_schedule_unpublish_mapping', __NAMESPACE__ . '\get_schedule_unpublish_mapping' );
```
