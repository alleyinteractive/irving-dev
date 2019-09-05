# Partials

## Description

An advanced template loader to DRY up template code.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'partials', '1.0' );
```

### An example of loading a partial

```php
ai_get_template_part( 'template', 'test', [ 'title' => $title ] );
```

If you need to load a template part for each item in an array of post objects, post ids, or a WP_Query object, you can use:

```php
ai_loop_template_part( $query_obj, 'test-template', [ 'title' => $title ] );
```

or alternatively use `ai_iterate_template_part` if you have an array of arbitrary values.

### Accessing provided variables within a partial

```php
<?php
/**
 * Test template file
 *
 * @package Alleypack
 */

$title = ai_get_var( 'title', 'Default Title' );

?>
<h2><?php echo esc_html( $title ); ?></h2>

```

