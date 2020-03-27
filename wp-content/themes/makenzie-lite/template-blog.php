<?php

/**
 * Template Name: Blog
 */

get_header(); ?>
<?php


	$makenzie_lite_tag_to_hide = '';

	// posts style
	$makenzie_lite_posts_style_template = makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' );

	// first post query args
		$makenzie_lite_argsFirst = array(
		'posts_per_page' => '1',
		'ignore_sticky_posts' => true,
		'tax_query' => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'operator' => 'NOT IN'
			),
		),
);

	$makenzie_lite_pageNumber = $wp_query->query_vars['paged'];

	// The first post ID if first_full is enabled ( to exclude in regular query )
	$makenzie_lite_post_exclude = false;
	if ( makenzie_lite_get_theme_mod( 'first_full', 'yes' ) == 'yes' ) {
		$makenzie_lite_first_post = get_posts( array( 'numberposts' => 1 ) );
		$makenzie_lite_post_exclude = $makenzie_lite_first_post[0]->ID;
	}

	if ( makenzie_lite_get_theme_mod( 'first_full', 'yes' ) == 'yes' && $makenzie_lite_pageNumber == 0 ) :

		// query args
		$makenzie_lite_args = array(
			'post_type' => 'post',
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'operator' => 'NOT IN'
				),
			),
		);

		// Exclude the first post
		if ( $makenzie_lite_post_exclude ) :
			$makenzie_lite_args['post__not_in'] = array( $makenzie_lite_post_exclude );
		endif;

	else :

		// query args
		$makenzie_lite_args = array(
			'post_type' => 'post',
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'operator' => 'NOT IN'
				),
			),
		);

		// Exclude the first post
		if ( $makenzie_lite_post_exclude ) :
			$makenzie_lite_args['post__not_in'] = array( $makenzie_lite_post_exclude );
		endif;

	endif;

	// query posts
	$makenzie_lite_query = new WP_Query( $makenzie_lite_args );
	$makenzie_lite_first_query = new WP_Query( $makenzie_lite_argsFirst );

	?>

		<?php
			if ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' ) :
				if ( makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'left' ) :
					get_sidebar( 'left' );
				endif;
		?>

			<div id="primary" class="content-area small-12 large-9 column">

		<?php else : ?>

			<div id="primary" class="content-area small-12 large-12 column">

		<?php

		endif;

		if ( makenzie_lite_get_theme_mod( 'first_full', 'yes' ) == 'yes' && $makenzie_lite_pageNumber == 0 ) : ?>

			<div id="first-post">
				<?php
					if ( $makenzie_lite_first_query->have_posts() ) :
					/* Start the Loop */
					while ( $makenzie_lite_first_query->have_posts() ) : $makenzie_lite_first_query->the_post();
						get_template_part( 'template-parts/listing/post-first' );
					endwhile;
					rewind_posts();
					endif;
				?>
			</div>

		<?php endif;

		if ( makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) == 'post-s3' || makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) == 'post-s4' ) : ?>

			<div class="row masonry">

				<main id="main" class="site-main masonry-init">

		<?php else: ?>

				<main id="main" class="site-main">

		<?php endif; ?>

			<?php

				if ( $makenzie_lite_query->have_posts() ) :

				// amount of pages
				$makenzie_lite_num_pages = $makenzie_lite_query->max_num_pages;

				if ( is_home() && ! is_front_page() ) : ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
				<?php
				endif;

				/* Start the Loop */
				while ( $makenzie_lite_query->have_posts() ) : $makenzie_lite_query->the_post();

					$makenzie_lite_layout = makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' );

					get_template_part( 'template-parts/listing/' . $makenzie_lite_layout );

				endwhile;

				if ( makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) != 'post-s3' && makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) != 'post-s4' ) :

					/* Custom post navigation */
					makenzie_lite_pagination( array( 'pages' => $makenzie_lite_num_pages ) );

				endif;

				else :

				get_template_part( 'template-parts/content', 'none' );

			endif;

			// reset query
			wp_reset_postdata();

			?>

			</main><!-- #main -->

			<?php if ( makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) == 'post-s3' || makenzie_lite_get_theme_mod( 'posts_style_template', 'post-s1' ) == 'post-s4' ) : ?>

				<div class="small-12 cell">

					<?php

					/* Custom post navigation */
					makenzie_lite_pagination( array( 'pages' => $makenzie_lite_num_pages ) );

					?>

				</div>

			</div>

			<?php endif; ?>

		</div><!-- #primary -->

<?php

if ( makenzie_lite_get_theme_mod( 'sidebar_on_off', 'on' ) == 'on' && makenzie_lite_get_theme_mod( 'sidebar_position', 'right' ) == 'right' ) :
	get_sidebar();
endif;
get_footer();
