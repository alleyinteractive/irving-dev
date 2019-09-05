# Classnames for PHP

Functions for managing concatenation and output of classes in your PHP templates. These are heavily influenced by https://github.com/JedWatson/classnames.

## Examples:

Output an array of classes:
```php
// Code:
<div class="<?php \Alleypack\the_classnames( [ 'class-one', 'class-two' ] ); ?>" /></div>

// Output:
<div class="class-one class-two" />
```

Output a static array of classes combined with an array of conditional classes:
```php
// Code:
<div class="<?php \Alleypack\the_classnames(
	[ 'class-one', 'class-two' ],
	[
		'class-three' => is_single(),
		'class-four'  => is_home(),
	]
); ?>" /></div>

// Output (when on homepage):
<div class="class-one class-two class-four" /></div>
```

