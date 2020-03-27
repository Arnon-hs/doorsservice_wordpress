<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Makenzie_Blog
 */

get_header(); ?>

	<?php
	if ( makenzie_lite_get_theme_mod( 'single_sidebar_on_off', 'on' ) == 'on' ) :
		if ( makenzie_lite_get_theme_mod( 'single_sidebar_position', 'right' ) == 'left' ) :
			get_sidebar( 'left' );
		endif;
	?>

	<div id="primary" class="content-area small-12 large-9 cell">

	<?php else : ?>

		<div id="primary" class="content-area small-12 large-12 cell">

	<?php endif; ?>

		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Article', 'makenzie-lite' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'makenzie-lite' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous Article', 'makenzie-lite' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'makenzie-lite' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );

			// about author
			get_template_part( 'template-parts/about-author' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if ( makenzie_lite_get_theme_mod( 'single_sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'single_sidebar_position', 'right' ) == 'right' ) :
	get_sidebar();
endif;
get_footer();
