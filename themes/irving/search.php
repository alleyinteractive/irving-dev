<?php
/**
 * The template for displaying search results pages.
 *
 * @package Irving
 */
?>

<header class="page-header">
	<h1 class="page-title">
		<?php
		printf(
			/* translators: %s: search query */
			esc_html__( 'Search Results for: %s', 'irving-dev' ),
			'<span>' . get_search_query() . '</span>'
		);
		?>
	</h1>
</header><!-- .page-header -->

<?php
while ( have_posts() ) :
	the_post();
	?>

	<?php get_template_part( 'template-parts/content', 'search' ); ?>

<?php endwhile; ?>

<?php the_posts_navigation(); ?>
