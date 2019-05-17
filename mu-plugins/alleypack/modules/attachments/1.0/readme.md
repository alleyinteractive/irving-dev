# Attachments

## Description

Helpers to manage attachments.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'attachments', '1.0' );
```
## Functions

### create_attachment_from_url( $url, $args )

/**
 * Create an attachment from an URL address.
 *
 * @param string $url Fully qualified URL.
 * @param array  $args {
 *        Optional. Arguments for the attachment. Default empty array.
 *
 *        @type string      $alt            Alt text.
 *        @type string      $caption        Caption text.
 *        @type string      $description    Description text.
 *        @type array       $meta           Associate array of meta to set.
 *                                          The value of alt text will
 *                                          automatically be mapped into
 *                                          this value and will be
 *                                          overridden by the alt explicitly
 *                                          passed into this array.
 *        @type null|int    $parent_post_id Parent post id.
 *        @type null|string $title          Title text. Null defaults to the
 *                                          sanitized filename.
 * }
 * @return int|WP_Error Attachment ID or \WP_Error.
 */
