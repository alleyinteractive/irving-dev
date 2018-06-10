<?php
/**
 * The template for displaying archive pages.
 *
 * @package Irving
 */
?>

<header class="page-header">
	<h1 class="page-title"><?php the_archive_title(); ?></h1>
	<div class="archive-description"><?php the_archive_description(); ?></div>
</header><!-- .page-header -->

<?php
while ( have_posts() ) :
	the_post();
	?>

	<?php get_template_part( 'template-parts/content' ); ?>

<?php endwhile; ?>

<?php the_posts_navigation(); ?>
