<?php
/**
 * The primary HTML wrapper for our theme.
 *
 * @package Irving
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'irving' ); ?></a>

	<header id="masthead" class="site-header" role="banner" data-component="siteHeader">
		<div class="site-branding">
			<?php the_custom_logo(); ?>
			<?php if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title" data-component="siteHeader"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php $irving_description = get_bloginfo( 'description' ); ?>
			<?php if ( $irving_description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo esc_html( $irving_description ); ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'irving' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id' => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<?php
				if ( have_posts() || is_404() ) {
					/**
					 * Load the main template file, e.g. single.php.
					 */
					\Irving\get_main_template();
				} else {
					ai_get_template_part( 'template-parts/content', 'none' );
				}
				?>

			</main>
		</div>

		<?php get_sidebar(); ?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-info">
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>
