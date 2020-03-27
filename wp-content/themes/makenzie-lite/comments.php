<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
			if ( 1 === get_comments_number() ) {
				printf(
					__( 'One comment on &ldquo;%1$s&rdquo;', 'makenzie-lite' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					_n( '%1$s comments on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'makenzie-lite' ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => '55',
				) );
			?>
		</ol><!-- .comment-list -->

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-under" class="navigation comment-navigation">
			<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'makenzie-lite' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'makenzie-lite' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'makenzie-lite' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-under -->
		<?php endif; // Check for comment navigation. ?>

		<?php

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'makenzie-lite' ); ?></p>
	<?php
	endif;

	comment_form();
	?>

</div><!-- #comments -->
