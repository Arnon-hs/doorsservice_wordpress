<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
 */

get_header(); ?>

<?php if ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'right') { ?>
	<div id="primary" class="content-area small-12 large-9 cell">
<?php } elseif ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'left')  { ?>
	<div id="primary" class="content-area small-12 large-9 cell medium-order-2">
<?php  } else { ?>
	<div id="primary" class="content-area small-12 large-12 cell">
<?php } ?>

	<main id="main" class="site-main">
		<?php
			if ( have_posts() ) :
				$makenzie_num_pages = $wp_query->max_num_pages;

				if ( is_home() && ! is_front_page() ) : ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
					<?php
				endif;

				/* Start the Loop */
				while ( have_posts() ) : the_post();
					$makenzie_layout = makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' );
					get_template_part( 'template-parts/listing/' . $makenzie_layout );
				endwhile;

				if ( makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) != 'post-s3' && makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) != 'post-s4' ) :
					/* Custom post navigation */

					makenzie_lite_pagination( array( 'pages' => $makenzie_num_pages ) );
				endif;

			else :
				get_template_part( 'template-parts/content', 'none' );
		endif;
		// reset query
		wp_reset_postdata(); ?>

	</main>
</div>

<?php
	if ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'right' ) {
		get_sidebar();
	} elseif ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'left' ) == 'left' ) {
		get_sidebar('left');
	}
?>

<?php get_footer(); ?>
