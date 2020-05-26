<?php
/**
 * WordPress Admin Bar component.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev\Components\Admin_Bar;

/**
 * Class for the Admin_Bar component.
 */
class Admin_Bar extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'admin-bar';

	/**
	 * Define a default config.
	 *
	 * @return array
	 */
	public function default_config(): array {
		return [
			'iframe_src' => '',
		];
	}

	/**
	 * Iframe the admin bar.
	 *
	 * @return self
	 */
	public function set_iframe( string $path ): self {

		// Only show the admin bar if logged in.
		if ( ! is_user_logged_in() ) {
			return $this;
		}

		return $this->set_config( 'iframe_src', site_url( $path ) );
	}

	/**
	 * Callback for wp_head to add a base tag for the iframed content.
	 */
	public static function add_base_tag() {
		?>
		<base href="<?php echo esc_url( site_url( '/' ) ); ?>" target="_parent">
		<?php
	}
}
