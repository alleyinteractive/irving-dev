# Sync Script

Easily and quickly sync data from an external source to a WordPress object.

## Basic Usage

There are two primary objects that make a sync work. The Feed class, and the Feed Item class.

## Feed Class
Extend \Alleypack\Sync_Script\Feed to parse a new data source.

```
<?php
/**
 * Job Feed.
 *
 * @package Alleypack\Sync_Script.
 */

namespace Alleypack\Sync_Script;

/**
 * Job Feed.
 */
class Job_Feed extends \Alleypack\Sync_Script\Feed {

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'jobs';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\Alleypack\Sync_Script\Job_Feed_Item';

	/**
	 * Batch size.
	 *
	 * @var int
	 */
	protected $batch_size = 50;

	/**
	 * Load feed items.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return array
	 */
	public function load_source_feed_data( int $limit, int $offset ) : array {

		// Build URL.
		$request_url = add_query_arg(
			[
				'limit'  => $limit,
				'offset' => $offset,
			],
			'https://example.com/jobs/feed/json'
		);

		// Get request.
		$request = wp_remote_get( $request_url );
		$results = (string) wp_remote_retrieve_body( $request ) ?? '';

		return json_decode( $results, true ) ?? [];
	}
}
```

## Feed Item Class
This class acts as a general object representing any kind of data. It should be extended for specific data types (posts, terms, users, etc.).


### Post Feed Item
To sync to a post type, extend \Alleypack\Sync_Script\Post_Feed_Item.

```
<?php
/**
 * Class for parsing a single job feed item.
 *
 * @package Alleypack\Sync_Script
 */

namespace Alleypack\Sync_Script;

/**
 * Job Feed Item.
 */
class Job_Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Job post type.
	 *
	 * @var string
	 */
	public static $post_type = 'job';

	/**
	 * Version track the mapping logic. If you modify anything in this class,
	 * you should probably bump this value.
	 *
	 * @var string
	 */
	protected $mapping_version = '1.1';

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	public function get_unique_id() {
		return $this->source['guid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		// Map fields.
		$this->object['post_title']   = $this->source['title'];
		$this->object['post_content'] = $this->source['description'];
		$this->object['post_status']  = 'publish';

		// Map meta.
		$this->object['meta_input'] = [];
	}

	/**
	 * Modify object after it's been saved.
	 */
	public function post_object_save() {
		$post_id = $this->get_object_id();

		if ( is_null( $post_id ) ) {
			return false;
		}

		// Perform actions that require a valid post ID.
	}
}
```

## Debugging

To enable debugging: `define( 'SYNC_DEBUG', TRUE );`

Then in your code, use the function `alleypack_log()` to log messages and data to the error log.

For example, in your `map_source_to_object()` function, it could be useful to log the source and object:

```php
alleypack_log( 'Mapped source to object. Source:', $this->source );
alleypack_log( 'Object:', $this->object );
```
