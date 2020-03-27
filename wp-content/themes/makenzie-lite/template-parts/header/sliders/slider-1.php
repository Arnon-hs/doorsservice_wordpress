<?php

	/**
	 * Template part for displaying a mobile slider
	 *
	 * @link https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package Makenzie_Blog
	 */

	// query args
	$makenzie_sticky = get_option( 'sticky_posts' );

	$makenzie_args = array(
		'posts_per_page' => 4,
		'post__in'  => get_option( 'sticky_posts' ),
		'ignore_sticky_posts' => 1
	);

	// query posts
	$makenzie_lite_query = new WP_Query( $makenzie_args );

	if ( $makenzie_lite_query->have_posts() ) :

?>

<div id="slider-1" data-autoplay="0">

	<div class="slider-content">

		<?php

			/* Start the Loop */
			while ( $makenzie_lite_query->have_posts() ) : $makenzie_lite_query->the_post(); ?>

			<article style="background-image: url(<?php the_post_thumbnail_url(); ?>);" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- Post Main Content -->
				<div class="post-main">

					<!-- Post Category -->
					<span class="entry-category"><?php the_category( ' / ' ); ?></span>

					<!-- Post Title -->
					<header class="entry-header">
						<?php
						if ( is_single() ) :
							the_title( '<h1 class="entry-title">', '</h1>' );
						else :
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						endif; ?>
					</header>

					<!-- Post Content -->
					<div class="entry-content align-center">
						<?php the_excerpt(); ?>
					</div>
					<div class="continue-reading">
						<a href="<?php the_permalink(); ?>"><?php _e( 'Continue Reading', 'makenzie-lite' ) ?><span class="arrow">&#8594;</span></a>
					</div>
				</div>
				<div class="slider-post-overlay"></div>
			</article><!-- #post-## -->

			<?php endwhile;

		?>

	</div>

</div>

<?php

	endif;

	wp_reset_postdata();

?>
