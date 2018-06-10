<?php
/**
 * The template for displaying all single posts.
 *
 * @package Irving
 */

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', 'single' );

	the_post_navigation();

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endwhile; // end of the loop.
