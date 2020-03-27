<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
 */

get_header(); ?>
	<div id="primary" class="content-area small-12 large-9 cell">
		<main id="main" class="site-main">
			<?php woocommerce_content(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php if ( is_active_sidebar( 'sidebar-woo' ) ) : ?>
	<aside id="secondary" class="widget-area small-12 large-3 cell">
	<?php dynamic_sidebar( 'sidebar-woo' ); ?>
	</aside><!-- #secondary -->
<?php else : get_sidebar();
endif;
get_footer();
