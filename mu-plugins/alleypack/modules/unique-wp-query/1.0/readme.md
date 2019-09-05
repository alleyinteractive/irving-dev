# Unique WP Query
When building a site, we often run into situations where duplicate content displays. WP_Query's post__not_in helps us remove duplicates if we keep track of them, but there are performance issues associated with this approach, as outlined by [VIP's documentation](https://vip.wordpress.com/documentation/performance-improvements-by-removing-usage-of-post__not_in/).

Unique WP Query takes the suggested approach from VIP's docs and extends WP_Query to automatically handle the logic. The Unique_WP_Query class will perform exactly like a normal WP_Query, but ensures that all posts previously returned from a Unique_WP_Query are filtered out first.

Note: Using this functionality for archives where you need custom curation and pagination of content will cause it to return incorrect results. It's recommended you pay close attention to these situations and parse most of the logic yourself, and fall-back on using post__not_in.

### Use

Somewhere towards the beginning of your functions.php, load the module.

```php
\Alleypack\load_module( 'unique-wp-query', '1.0' );
```

and replace any WP_Query with Unique_WP_Query

```php
$results = new \Alleypack\Unique_WP_Query(
    [
        'post_type'      => 'post',
        'posts_per_page' => 10,
    ]
);
```

### Modify used post ids.
`Unique_WP_Query_Manager` keeps track of post ids that have already been returned in the static variable `$used_post_ids`. You can modify this directly, and there are two helper functions to assist with this.

Explicitly set the used post ids.
```php
\Alleypack\Unique_WP_Query_Manager::set_used_post_ids( $post_ids );
```

Append additional post ids to the `$used_post_ids` array.
```php
\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $post_ids );
```