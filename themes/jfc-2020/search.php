<?php
/**
 * The template for displaying search results pages.
 *
 * @package JFC_2020
 */

?>

<header class="page-header">
	<h1 class="page-title">
		<?php
		printf(
			/* translators: %s: search query */
			esc_html__( 'Search Results for: %s', 'jfc-2020' ),
			'<span>' . get_search_query() . '</span>'
		);
		?>
	</h1>
</header><!-- .page-header -->

<?php
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', 'search' );

endwhile;

the_posts_navigation();
