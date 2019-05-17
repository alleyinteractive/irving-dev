# FM Modules

## Description

FM_Modules handles the legwork of setting up a modules-based page builder for editors.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'fm-modules', '1.0' );
```

### Example

Given that you have module classes `Module_A` and `Module_B` which extend `\WP_Irving\Component\Component` and `use \Alleypack\FM_Module`, you can add these modules to a `page-modules` FM group via the `fm_modules_components` filter:

```php
function add_components( $components, $slug ) {
	if ( 'page-modules' === $slug ) {
		return [
			'Module_A',
			'Module_B',
		];
	}
	return $components;
}
add_filter( 'fm_modules_components', 'add_components', 10, 2 );
```

Then, get an FM group with these modules:
```php
( new \Alleypack\FM_Modules( 'page-modules' ) )->get_fm_group();
```

Finally, to retrieve the modules data:
```php
$data    = (array) get_post_meta( $post_id, 'modules', true ) ?? [];
$modules = ( new \Alleypack\FM_Modules( 'page-modules' ) )->get_components_from_fm_data( $data );
```
