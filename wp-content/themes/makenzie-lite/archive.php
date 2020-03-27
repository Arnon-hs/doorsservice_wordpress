<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
 */

get_header(); ?>

	<div id="primary" class="content-area small-12 large-9 cell">
		<main id="main" class="site-main">
		<?php get_template_part( 'template-parts/header/template-title' ); ?>
		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) :

				the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			/* Custom post navigation */
			makenzie_lite_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
