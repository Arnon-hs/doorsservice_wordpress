<?php

	// Get author ID
	if ( get_the_author_meta( 'ID' ) )
		$makenzie_author_id = get_the_author_meta( 'ID' );
	else
		$makenzie_author_id = 1;

?>
<?php if ( get_the_author_meta( 'description', $makenzie_author_id ) != '' ): ?>

	<h5 class="about-author-title">
		<?php _e( 'About the author', 'makenzie-lite' ); ?>	
	</h5>

	<div class="about-author clearfix">

		<div class="about-author-sidebar">
			<div class="about-author-avatar">
				<?php echo get_avatar( $makenzie_author_id , 90 ); ?>
			</div><!-- .about-author-avatar -->
		</div><!-- .about-author-sidebar -->

		<div class="about-author-main">

			<div class="about-author-name-main">
				<span class="about-author-name"><?php the_author_posts_link(); ?></span>
			</div><!-- .about-author-sidebar-main -->

			<div class="about-author-bio">
				<?php echo get_the_author_meta( 'description', $makenzie_author_id ); ?>
			</div><!-- .about-author-bio -->

		</div><!-- .about-author-main -->

	</div><!-- .about-author -->

<?php endif; ?>
