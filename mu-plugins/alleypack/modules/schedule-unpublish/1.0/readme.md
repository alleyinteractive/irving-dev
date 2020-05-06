# Schedule Unpublish

## Description

Schedule posts to unpublish in the future.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'schedule-unpublish', '1.0' );
```

### Enable it for post type(s)

By default it is enabled for the `post` post type, but this can be changed via filter:
```php
/**
 * Get the post types where the unpublish module should be active.
 *
 * @param array $post_types Array of post types.
 * @return array
 */
function get_schedule_unpublish_post_types( array $post_types ): array {
	return [ 'my-cpt' ];
}
add_filter( 'alleypack_schedule_unpublish_post_types', 'get_schedule_unpublish_post_types' );
```
