Stylesheets
===========

Functions for retrieving hashed classnames and managing concatenation and output of the classes in your PHP templates.

## Usage

### `ai_use_stylesheet`

Set the stylesheet reference. This allows you to use the classnames functions without explicitly providing a stylesheet each time. Best to use in main template files (_single.php_, _archive.php_, etc.), rather than template-parts.

```php
ai_use_stylesheet( $stylesheet );
```

The preferred method for ensuring a template-part references the correct stylesheet is to pass a `'stylesheet'` argument to `ai_get_template_part`, since the previous stylesheet will be restored after each template-part loads.

```php
ai_get_template_part( 'template-parts/header', [ 'stylesheet' => 'site-header' ] );
```

### `ai_the_classnames`

Print a string of classnames from the provided arguments.

```php
ai_the_classnames( $static_classes, $dynamic_classes, $stylesheet );
```

### `ai_get_classnames`

Get a string of classnames from the provided arguments.

```php
$classnames = ai_get_classnames( $static_classes, $dynamic_classes, $stylesheet );
```

### `ai_the_classnames_with_global`

Print a string of classnames from the provided arguments, including the raw input values.

```php
$classnames = ai_the_classnames_with_global( $static_classes, $dynamic_classes, $stylesheet );
```

### `ai_get_classnames_with_global`

Get a string of classnames from the provided arguments, including the raw input values.

```php
ai_get_classnames_with_global( $static_classes, $dynamic_classes, $stylesheet );
```

### Parameters

#### `$static_classes`
`string[]`

An indexed array of classnames to retrieve based on the current stylesheet.

#### `$dynamic_classes`
`array`

An optional associative array of conditional classnames to retrieve based on the current stylesheet, where the array key is a classname and the associated value is a condition that must resolve to _truthy_ for the classname to be output.

```php
// The following classname will be output only on the homepage.
[ 'is-home' => is_home() ]
```

#### `$stylesheet`
`string`

The stylesheet from which the classname(s) should be retrieved. If not provided, will use the stylesheet provided via `ai_use_stylesheet`.

If we have no need for `$dynamic_classes` we can safely pass `$stylesheet` as the second argument.

## Example

```php
// Input:
<?php ai_use_stylesheet( 'component-name' ); ?>

<div class="<?php ai_the_classnames( [ 'class-one' ], [ 'class-three' => is_home() ] ); ?>"></div>
```

```html
<!-- Output (when on homepage): -->
<div class="component-name__class-one___hG6sn component-name__class-three___KGM0d"></div>
```
