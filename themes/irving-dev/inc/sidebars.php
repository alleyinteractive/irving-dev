<?php
/**
 * This file holds configuration settings for widget areas.
 *
 * @package Irving_Dev
 */

namespace Irving_Dev;

/**
 * Register widget areas.
 */
function widgets_init() {
	register_sidebar(
		[
			'name'          => __( 'Sidebar', 'irving-dev' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here.', 'irving-dev' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		]
	);
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );
