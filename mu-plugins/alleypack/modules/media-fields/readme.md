# Media Fields

## Description

Easily add media fields.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'media-fields', '1.0' );
```

### Example

```php
/**
 * Add custom media fields.
 *
 * @return array The custom media fields.
 */
function add_custom_media_fields() : array {
	return [
		'credit' => [
			'label'     => __( 'Credit' ),
			'input'     => 'text',
			'helps'     => __( 'Credit to be shown below the image.' ),
			'mime_type' => 'image',
		],
		'source' => [
			'label' => __( 'Source' ),
			'input' => 'text',
		],
		'available' => [
			'label' => __( 'Available' ),
			'input' => 'checkbox',
		],
		'type' => [
			'label' => __( 'Type' ),
			'input' => 'select',
			'options' => [
				'public'  => __( 'Public' ),
				'private' => __( 'Private' ),
			],
		],
		'rights_purchased' => [
			'label'   => __( 'Rights purchased' ),
			'input'   => 'checkboxes',
			'options' => [
				'digital'      => __( 'Digital' ),
				'print'        => __( 'Print' ),
				'social_media' => __( 'Social media' ),
			],
		],
	];
}
add_filter( 'alleypack_get_media_fields', 'add_custom_media_fields' );
```
