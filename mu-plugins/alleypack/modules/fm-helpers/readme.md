# FM Helpers

## Description

Additional fields and helper functions for Fieldmanager.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'fm-helpers', '1.0' );
```

### Fields

#### Fieldmanager_Content
Class for custom content field. Output sanitized output easily.

#### Fieldmanager_Excerpt
Class for custom excerpt field.

#### Fieldmanager_Post_Content
Class for custom post content field.

#### Fieldmanager_Rich_Post_Content
Class for custom rich post content field.

### Patterns

* `get_featured_media_fields()`
* `get_seo_and_social_fields()`
* `get_term_fields()`

#### Example in scaffolder:

```json
"media": {
	"label": { "__": "Featured Media" },
	"serialize_data": false,
	"add_to_prefix": false,
	"children": "`\\Alleypack\\Fieldmanager\\Patterns\\get_featured_media_fields()`"
}
```
