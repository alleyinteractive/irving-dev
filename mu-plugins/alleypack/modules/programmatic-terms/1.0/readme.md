# Programmatic Terms

## Description

Manage terms programmatically.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'programmatic-terms', '1.0' );
```

### Enable it for a taxonomy

For example, enable it for a taxonomy called `state` (which your theme has already registered):
```php
\Alleypack\create_programmatic_taxonomy( 'state' );
```

### Filter the list of term options
E.g.,
```php
/**
 * Get state options.
 *
 * @param array  $options  Associative array of term options ( slug => name ).
 * @param string $taxonomy Taxonomy.
 * @return array
 */
function get_state_options( array $options, string $taxonomy ): array {
	if ( 'state' !== $taxonomy ) {
		return $options;
	}

	return [
		'ak' => __( 'Alaska', 'my-theme' ),
		'tx' => __( 'Texas', 'my-theme' ),
	];
}
add_filter( 'alleypack_programmatic_terms_options', 'get_state_options', 10, 2 );
```
