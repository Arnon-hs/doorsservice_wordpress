<?php
/**
 * Template Name: Home
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
				$makenzie_lite_args = array(
					'post_type' => 'post',
					'paged' => $paged,
				);

				$makenzie_lite_query = new WP_Query( $makenzie_lite_args );

				if ( $makenzie_lite_query->have_posts() ) :

					// amount of pages
					$makenzie_lite_num_pages = $makenzie_lite_query->max_num_pages;

					/* Start the Loop */
					while ( $makenzie_lite_query->have_posts() ) : $makenzie_lite_query->the_post();

					$makenzie_lite_layout = makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' );

					get_template_part( 'template-parts/listing/' . $makenzie_lite_layout );

					endwhile;
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;

				// reset query
				wp_reset_postdata(); ?>
		</main>

		<div class="small-12 cell">
			<?php makenzie_lite_pagination( array( 'pages' => $makenzie_lite_num_pages ) );  ?>
		</div>
</div> <!-- /primary  -->

<?php
	if ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'right' ) {
		get_sidebar();
	} elseif ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'left' ) == 'left' ) {
		get_sidebar('left');
	}
?>

<?php get_footer(); ?>
