<article id="post-<?php the_ID(); ?> first-post" <?php post_class('post-s2'); ?>>

	<!-- Post Thumbnail -->
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="post-thumb">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'makenzie-blog-listing2-thumb' ); ?></a>
	</div>
	<?php endif; ?>

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

		<!-- Post Meta -->
		<?php
			if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php makenzie_lite_posted_on(); ?>
				</div><!-- .entry-meta -->
		<?php
			endif;
		?>

		<!-- Post Content -->
		<div class="entry-content">
			<?php
				the_excerpt();
			?>
		</div>

		<!-- Continue Reading <span class="arrow">&#8594;</span> -->
		<div class="continue-reading">
			<a href="<?php the_permalink(); ?>"><?php _e( 'Continue Reading', 'makenzie-lite' ) ?><span class="arrow">&#8594;</span></a>
		</div>

	</div>

	<!-- <footer class="entry-footer">
		<?php makenzie_lite_entry_footer(); ?>
	</footer>.entry-footer -->

</article><!-- #post-## -->
