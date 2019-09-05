# One Timer

## Description

This is a simple class which can be used to ensure that something (e.g. a function or setup routine) runs once and only once. For example, use this when creating new user roles, terms, DB tables, etc.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'one-timer', '1.0' );
```

### An example passing a version number

```php
$version   = '1.0';
$one_timer = new \Alleypack\One_Timer( 'an_example', $version );

if ( $one_timer->is_unchanged() ) {
	return;
}

// Run the setup.
$this->setup();

$one_timer->save_change();
```

### An example using an array of data

```php
$data      = [ 'thing_one', 'thing_two' ];
$one_timer = new \Alleypack\One_Timer( 'another_example', $data );

if ( ! $one_timer->is_unchanged() ) {
	// Do some things once and save the change.
	$this->do_things();
	$one_timer->save_change();
}
```
