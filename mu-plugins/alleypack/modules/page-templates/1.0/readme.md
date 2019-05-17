# Page Templates

Page Templates allow greater control over how pages are rendered in WordPress. This module offers companion functionality that allows page templates to be defined along with an FM group to easily store the meta needed for that page template.

## Getting Started
Begin by loading this module.

```php
\Alleypack\load_module( 'page-templates', '1.0' );
```

To register a new page template and FM fields, hook into `after_setup_theme` and use `\Alleypack\Page_Templates\register()` to pass a page template config

Hooking after plugins and this module is loaded (`after_setup_theme` is a good choice)
### Add a Template
```php
add_action(
	'after_setup_theme',
	function() {

		// Ensure module has been loaded and register function is available.
		if ( ! function_exists( '\Alleypack\Page_Templates\register' ) ) {
			return;
		}

		// Register an `About Us` page template with two FM fields.
		\Alleypack\Page_Templates\register(
			__( 'About Us', 'cpr' ),
			'about-us',
			[
				'name'   => new \Fieldmanager_TextField( __( 'Name', 'alleypack' ) ),
				'number' => new \Fieldmanager_TextField( __( 'Number', 'alleypack' ) ),
			]
		);

		// Register an Staff Directory page template with two FM fields.
		\Alleypack\Page_Templates\register(
			__( 'Staff Directory', 'alleypack' ),
			'staff-directory',
			[
				'staff' => new \Fieldmanager_Group(
					[
						'label'  => __( 'Staff', 'alleypack' ),
						'limit' => 0,
						'label_macro' => [ __( '%s', 'alleypack' ), 'name' ],
						'add_more_label' => __( 'Add Person', 'alleypack' ),
						'children' => [
							'name'  => new \Fieldmanager_TextField( __( 'Name', 'alleypack' ) ),
							'title' => new \Fieldmanager_TextField( __( 'Title', 'alleypack' ) ),
						],
					]
				],
			]
		);
	}
);

```
