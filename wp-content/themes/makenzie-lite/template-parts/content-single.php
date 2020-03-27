<?php
/**
 * Template part for displaying single posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<!-- Post Main Content -->
	<div class="post-main">

		<!-- Post Title -->
		<header class="entry-header">

			<!-- Post Category -->
			<span class="entry-category"><?php the_category( ' / ' ); ?></span>

			<?php
			if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif; ?>

			<div class="entry-meta-content">
				<!-- Post Meta Date -->
				<?php
				if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
				<?php makenzie_lite_posted_on(); ?>
				</div><!-- .entry-meta -->
				<?php
				endif; ?>
			</div>

		</header>

		<!-- Post Thumbnail -->
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumb">
				<?php the_post_thumbnail( 'makenzie-blog-single-thumb' ); ?>
			</div>
		<?php endif; ?>

		<!-- Post Content -->
		<div class="entry-content">

			<!-- Post Excerpt -->
			<?php if ( has_excerpt( $post->ID ) ) {
				echo '<div class="deck">';
				echo '<span>' . get_the_excerpt() . '</span>';
				echo '</div>';
				}
			?>

			<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'makenzie-lite' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
					'next_or_number' => 'next'
				) );
			?>
		</div>

	</div>

	<footer class="entry-footer">
		<?php makenzie_lite_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
