# CLI Helpers

## Description

Helpers for common CLI tasks.

## Usage

### Load the module (e.g. in `functions.php`)

```php
\Alleypack\load_module( 'cli-helpers', '1.1' );
```

### Examples

#### Bulk Tasks

Breaks apart bulk operations when dealing with a large number of WP objects. 

##### v1.1
```php
class My_Theme_CLI_Command extends \WP_CLI_Command {
	// Renaming the function on import only is required if both
	// bulk tasks are being used within the same CLI class.
	use \Alleypack\CLI_Bulk_User_Task {
		\Alleypack\CLI_Bulk_User_Task::bulk_task as bulk_user_task;
	}
	use \Alleypack\CLI_Bulk_Post_Task {
		\Alleypack\CLI_Bulk_Post_Task::bulk_task as bulk_post_task;
	};

	/**
	 * Add post meta for existing posts.
	 *
	 * ## EXAMPLE
	 *
	 * wp my_theme add_post_meta
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function add_post_meta( $args, $assoc_args ) {
		$dry_run = ! empty( $assoc_args['dry-run'] );

		$this->bulk_post_task(
			[
				'post_type' => [ 'post' ],
			],
			function ( $post ) use ( $dry_run ) {
				if ( ! $dry_run ) {
					update_post_meta( $post->ID, 'some_meta', 'some value' );
				}
				WP_CLI::success( "Updating meta in post $post->ID." );
			}
		);
	}
	};

	/**
	 * Delete all users with 'Subscriber' role.
	 *
	 * ## EXAMPLE
	 *
	 * wp my_theme remove_subscribers
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function remove_subscribers( $args, $assoc_args ) {
		$dry_run = ! empty( $assoc_args['dry-run'] );

		$this->bulk_user_task(
			[
				'role' => 'Subscriber',
			],
			function ( $user ) use ( $dry_run ) {
				if ( ! $dry_run ) {
					wp_delete_user( $user->ID );
				}
				WP_CLI::success( "Deleted Subscriber $user->ID." );
			}
		);
	}
}
WP_CLI::add_command( 'my_theme', __NAMESPACE__ . '\My_Theme_CLI_Command' );
```


##### v1.0
```php
class My_Theme_CLI_Command extends \WP_CLI_Command {

	use \Alleypack\CLI_Bulk_Task;

	/**
	 * Add post meta for existing posts.
	 *
	 * ## EXAMPLE
	 *
	 * wp my_theme add_post_meta
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function add_post_meta( $args, $assoc_args ) {
		$dry_run = ! empty( $assoc_args['dry-run'] );

		$this->bulk_task(
			[
				'post_type' => [ 'post' ],
			],
			function ( $post ) use ( $dry_run ) {
				if ( ! $dry_run ) {
					update_post_meta( $post->ID, 'some_meta', 'some value' );
				}
				WP_CLI::success( "Updating meta in post $post->ID." );
			}
		);
	}
}
WP_CLI::add_command( 'my_theme', __NAMESPACE__ . '\My_Theme_CLI_Command' );
```


## Changelog

#### `1.1`

- Adds `trait-bulk-user-task` for bulk user operations
- Moves functionality responsible for bulk post operations into `trait-bulk-post-task`


#### `1.0`

- Initial release.