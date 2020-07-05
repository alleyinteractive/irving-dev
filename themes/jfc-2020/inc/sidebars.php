<?php
/**
 * This file holds configuration settings for widget areas.
 *
 * @package JFC_2020
 */

namespace JFC_2020;

/**
 * Register widget areas.
 */
function widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'jfc-2020' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here.', 'jfc-2020' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) 
	);
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );
