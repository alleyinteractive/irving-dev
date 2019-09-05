# Term Post Link
Contributors: alleyinteractive, jameswburke

## Description

The Term Post Link module allows WordPress users to edit taxonomy terms from the post edit screen. Doing so offers advantages since the post edit screen is a more familiar and flexible interface than the term edit screen.

Term Post Link creates a post in the background for every term. So when a user tries to edit a term, it redirects them to the post. This is all done behind the scenes, so to a user this is seamless. This allows the user to access all the functionality of editing a post (like a nicer layout, the Gutenberg editor, ability to map terms to it etc.). 

This class can be used not only for new taxonomies, but for existing taxonomies as well. `get_term_meta` will continue to function as expected, performing a lookup for post meta in the corresponding linked post.

Support for WP SEO is built in.

## Usage

After scaffolding a taxonomy (ie my-tax.json) and scaffolding the linked post (my-tax-post.json), use Term Post Link to connect them.  By doing this, when a user edits a term, they will actually be updating the post, and thus getting all the functionality of editing a post.

### Scaffold the taxonomy

In `my-tax.json`:
```json
{
	"object_types": [
		"post"
	],
	"show_admin_column": true,
	"show_in_rest": true
}
```

### Scaffold the linked post

In `my-tax-post.json`:
```json
{
	"public": true,
	"show_ui": true,
	"show_in_nav_menus": false,
	"show_in_menu": false,
	"supports": [
		"title",
		"thumbnail",
		"editor",
		"revisions"
	]
}
```

### Load the module

The function for Term Post Link can be called in the `functions.php` file.  The link needs to be created very early on.  In the `functions.php` file, load the module in the section for Alleypack modules and consider adding a comment before calling the function.

Alternatively, the function can also be moved into a helper function or class.

```php
\Alleypack\load_module( 'term-post-link', '1.0' );
```

### Create the link

```php
//Create Term Post Link for My Tax
\Alleypack\create_term_post_link( 'my-tax', 'my-tax-post' );
```
