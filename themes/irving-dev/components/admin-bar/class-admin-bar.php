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

	use \WP_Components\WP_Post;

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
			// Manual test - this content has been cleaned up slightly and works.
			'content' => "<div id=\"wpadminbar\" class=\"nojq nojs\">
			<a class=\"screen-reader-shortcut\" href=\"#wp-toolbar\" tabindex=\"1\">Skip to toolbar</a>
			<div class=\"quicklinks\" id=\"wp-toolbar\" role=\"navigation\" aria-label=\"Toolbar\">
			  <ul id='wp-admin-bar-root-default' class=\"ab-top-menu\">
				<li id='wp-admin-bar-wp-logo' class=\"menupop\">
				  <a class='ab-item' aria-haspopup=\"true\" href='https://irving-dev.alley.test/wp-admin/about.php'><span class=\"ab-icon\"></span><span class=\"screen-reader-text\">About WordPress</span></a>
				  <div class=\"ab-sub-wrapper\">
					<ul id='wp-admin-bar-wp-logo-default' class=\"ab-submenu\">
					  <li id='wp-admin-bar-about'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/about.php'>About WordPress</a></li>
					</ul>
					<ul id='wp-admin-bar-wp-logo-external' class=\"ab-sub-secondary ab-submenu\">
					  <li id='wp-admin-bar-wporg'><a class='ab-item' href='https://wordpress.org/'>WordPress.org</a></li>
					  <li id='wp-admin-bar-documentation'><a class='ab-item' href='https://codex.wordpress.org/'>Documentation</a></li>
					  <li id='wp-admin-bar-support-forums'><a class='ab-item' href='https://wordpress.org/support/'>Support</a></li>
					  <li id='wp-admin-bar-feedback'><a class='ab-item' href='https://wordpress.org/support/forum/requests-and-feedback'>Feedback</a></li>
					</ul>
				  </div>
				</li>
				<li id='wp-admin-bar-site-name' class=\"menupop\">
				  <a class='ab-item' aria-haspopup=\"true\" href='https://irving-dev.alley.test/wp-admin/'>Irving (Local Development)</a>
				  <div class=\"ab-sub-wrapper\">
					<ul id='wp-admin-bar-site-name-default' class=\"ab-submenu\">
					  <li id='wp-admin-bar-dashboard'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/'>Dashboard</a></li>
					</ul>
					<ul id='wp-admin-bar-appearance' class=\"ab-submenu\">
					  <li id='wp-admin-bar-themes'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/themes.php'>Themes</a></li>
					  <li id='wp-admin-bar-widgets'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/widgets.php'>Widgets</a></li>
					  <li id='wp-admin-bar-menus'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/nav-menus.php'>Menus</a></li>
					</ul>
				  </div>
				</li>
				<li id='wp-admin-bar-customize' class=\"hide-if-no-customize\"><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/customize.php?url=https%3A%2F%2Firving-dev.alley.test%2Fwp-json%2Firving%2Fv1%2Fcomponents%3Fcontext%3Dpage%26path%3D%2F2013%2F01%2F11%2Fmarkup-html-tags-and-formatting%2F'>Customize</a></li>
				<li id='wp-admin-bar-updates'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/update-core.php' title='3 Plugin Updates'><span class=\"ab-icon\"></span><span class=\"ab-label\">3</span><span class=\"screen-reader-text\">3 Plugin Updates</span></a></li>
				<li id='wp-admin-bar-comments'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/edit-comments.php'><span class=\"ab-icon\"></span><span class=\"ab-label awaiting-mod pending-count count-0\" aria-hidden=\"true\">0</span><span class=\"screen-reader-text comments-in-moderation-text\">0 Comments in moderation</span></a></li>
				<li id='wp-admin-bar-new-content' class=\"menupop\">
				  <a class='ab-item' aria-haspopup=\"true\" href='https://irving-dev.alley.test/wp-admin/post-new.php'><span class=\"ab-icon\"></span><span class=\"ab-label\">New</span></a>
				  <div class=\"ab-sub-wrapper\">
					<ul id='wp-admin-bar-new-content-default' class=\"ab-submenu\">
					  <li id='wp-admin-bar-new-post'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/post-new.php'>Post</a></li>
					  <li id='wp-admin-bar-new-media'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/media-new.php'>Media</a></li>
					  <li id='wp-admin-bar-new-page'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/post-new.php?post_type=page'>Page</a></li>
					  <li id='wp-admin-bar-new-redirect_rule'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/post-new.php?post_type=redirect_rule'>Redirect</a></li>
					  <li id='wp-admin-bar-new-user'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/user-new.php'>User</a></li>
					</ul>
				  </div>
				</li>
				<li id='wp-admin-bar-edit'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/post.php?post=1178&#038;action=edit'>Edit Post</a></li>
			  </ul>
			  <ul id='wp-admin-bar-top-secondary' class=\"ab-top-secondary ab-top-menu\">
				<li id='wp-admin-bar-search' class=\"admin-bar-search\">
				  <div class=\"ab-item ab-empty-item\" tabindex=\"-1\">
					<form action=\"https://irving.alley.test/\" method=\"get\" id=\"adminbarsearch\"><input class=\"adminbar-input\" name=\"s\" id=\"adminbar-search\" type=\"text\" value=\"\" maxlength=\"150\" /><label for=\"adminbar-search\" class=\"screen-reader-text\">Search</label><input type=\"submit\" class=\"adminbar-button\" value=\"Search\"/></form>
				  </div>
				</li>
				<li id='wp-admin-bar-my-account' class=\"menupop with-avatar\">
				  <a class='ab-item' aria-haspopup=\"true\" href='https://irving-dev.alley.test/wp-admin/profile.php'>Howdy, <span class=\"display-name\">alley</span><img alt='' src='https://secure.gravatar.com/avatar/ad5dd285560221a7302608c74c690035?s=26&#038;d=mm&#038;r=g' srcset='https://secure.gravatar.com/avatar/ad5dd285560221a7302608c74c690035?s=52&#038;d=mm&#038;r=g 2x' class='avatar avatar-26 photo' height='26' width='26' /></a>
				  <div class=\"ab-sub-wrapper\">
					<ul id='wp-admin-bar-user-actions' class=\"ab-submenu\">
					  <li id='wp-admin-bar-user-info'><a class='ab-item' tabindex=\"-1\" href='https://irving-dev.alley.test/wp-admin/profile.php'><img alt='' src='https://secure.gravatar.com/avatar/ad5dd285560221a7302608c74c690035?s=64&#038;d=mm&#038;r=g' srcset='https://secure.gravatar.com/avatar/ad5dd285560221a7302608c74c690035?s=128&#038;d=mm&#038;r=g 2x' class='avatar avatar-64 photo' height='64' width='64' /><span class='display-name'>alley</span></a></li>
					  <li id='wp-admin-bar-edit-profile'><a class='ab-item' href='https://irving-dev.alley.test/wp-admin/profile.php'>Edit My Profile</a></li>
					  <li id='wp-admin-bar-logout'><a class='ab-item' href='https://irving-dev.alley.test/wp-login.php?action=logout&#038;_wpnonce=1536482ee4'>Log Out</a></li>
					  <li id='wp-admin-bar-switch-off'><a class='ab-item' href='https://irving-dev.alley.test/wp-login.php?action=switch_off&#038;nr=1&#038;_wpnonce=6cd4efba52&#038;redirect_to=https%3A%2F%2Firving-dev.alley.test%2Fwp-json%2Firving%2Fv1%2Fcomponents%3Fcontext%3Dpage%26path%3D%2F2013%2F01%2F11%2Fmarkup-html-tags-and-formatting%2F'>Switch Off</a></li>
					</ul>
				  </div>
				</li>
				<li id='wp-admin-bar-debug-bar' class=\"menupop\">
				  <div class=\"ab-item ab-empty-item\" aria-haspopup=\"true\">Debug</div>
				  <div class=\"ab-sub-wrapper\">
					<ul id='wp-admin-bar-debug-bar-default' class=\"ab-submenu\">
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_Queries'><a class='ab-item' href='#debug-menu-target-Debug_Bar_Queries' rel='#debug-menu-link-Debug_Bar_Queries'>Queries</a></li>
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_WP_Query'><a class='ab-item' href='#debug-menu-target-Debug_Bar_WP_Query' rel='#debug-menu-link-Debug_Bar_WP_Query'>WP Query</a></li>
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_Request'><a class='ab-item' href='#debug-menu-target-Debug_Bar_Request' rel='#debug-menu-link-Debug_Bar_Request'>Request</a></li>
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_Object_Cache'><a class='ab-item' href='#debug-menu-target-Debug_Bar_Object_Cache' rel='#debug-menu-link-Debug_Bar_Object_Cache'>Object Cache</a></li>
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_JS'><a class='ab-item' href='#debug-menu-target-Debug_Bar_JS' rel='#debug-menu-link-Debug_Bar_JS'>JavaScript</a></li>
					  <li id='wp-admin-bar-debug-bar-Debug_Bar_Roles_And_Capabilities_Panel'><a class='ab-item' href='#debug-menu-target-Debug_Bar_Roles_And_Capabilities_Panel' rel='#debug-menu-link-Debug_Bar_Roles_And_Capabilities_Panel'>Roles and Capabilities</a></li>
					</ul>
				  </div>
				</li>
			  </ul>
			</div>
			<a class=\"screen-reader-shortcut\" href=\"https://irving-dev.alley.test/wp-login.php?action=logout&#038;_wpnonce=1536482ee4\">Log Out</a>\n\t\t\t\t\t
		  </div>",
		  // Kill the manual test.
		  'content'    => '',
		  'iframe_src' => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set(): self {
		// Temp to deal with not being properly "logged in".
		// @todo figure out why JWT auth doesn't work for me locally.
		$user = get_user_by( 'login', 'alley' );
		wp_clear_auth_cookie();
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
		
		// Initialize the admin bar.
		_wp_admin_bar_init();
		
		// Render the admin bar and capture the output.
		ob_start();
		wp_admin_bar_render();
		$admin_bar = ob_get_clean();

		// Alternate, since the above doesn't work due to wp_is_json_request() issue.
		ob_start();
		global $wp_admin_bar;
		require_once ABSPATH . WPINC . '/class-wp-admin-bar.php';
		$wp_admin_bar = new \WP_Admin_Bar;
		$wp_admin_bar->initialize();
		$wp_admin_bar->add_menus();
		do_action_ref_array( 'admin_bar_menu', array( &$wp_admin_bar ) );
		$wp_admin_bar->render();
		$admin_bar = ob_get_clean();

		// JS.
		$wp_scripts = wp_scripts();
		$admin_bar_dep = $wp_scripts->registered['admin-bar'] ?? '';
		$admin_bar_script = $admin_bar_dep->src ?? '';
		// error_log( $admin_bar_script );
		
		// @todo call $head->add_script for the $admin_bar_script->src as well as any dependencies

		return $this->set_config( 'content', $admin_bar );

		// return $this;
	}

	/**
	 * Iframe the admin bar.
	 *
	 * @todo make this more robust, maybe just use the $path to get the iframe src.
	 * @todo add target="_parent" to the links.
	 * @return self
	 */
	public function set_iframe(): self {

		if ( get_the_ID() ) {
			$link = str_replace( home_url(), site_url(), get_permalink( get_the_ID() ) );
			return $this->set_config( 'iframe_src', $link );
		}

		// Fallback.
		return $this->set_config( 'iframe_src', 'https://irving-dev.alley.test' );
	}
}
