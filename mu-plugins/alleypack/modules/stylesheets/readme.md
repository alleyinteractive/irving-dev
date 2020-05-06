Stylesheets
===========

## Description

A plugin to facilitate using CSS Modules.

## Setup

### Load the module (e.g. in `functions.php`)

```php
// Loader for stylesheets.
\Alleypack\load_module( 'stylesheets', '1.0' );

// The stylesheets module requires partials >= 1.1
\Alleypack\load_module( 'partials', '1.1' );
```

### Install and configure the PostCSS Modules plugin

See [css-modules-helpers](https://github.com/alleyinteractive/css-modules-helpers) for frontend setup, including helper functions to retrieve classnames and DOM elements.

### Call the Stylesheets setup in the site `<head>`

The `setup` method gets the _classnames.json_ manifest and outputs its contents as `window.cssModulesClassnames`.

```php
\Alleypack\Stylesheets::instance()->setup();
```

## Usage

Use the `ai_the_classnames` function to output an element's class attribute.

```php
<div class="<?php ai_the_classnames( [ 'wrapper' ], [], 'site-header' ); ?>"></div>
```

See the [Stylesheets README][ss-readme] for more.

[ss-readme]: 1.0
