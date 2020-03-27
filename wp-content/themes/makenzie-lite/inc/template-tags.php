<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Makenzie_Blog
 */

if ( ! function_exists( 'makenzie_lite_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function makenzie_lite_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'on %s', 'post date', 'makenzie-lite' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'makenzie-lite' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'makenzie_lite_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function makenzie_lite_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list();
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged in : %1$s', 'makenzie-lite' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'makenzie-lite' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'makenzie-lite' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function makenzie_lite_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'makenzie_lite_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'makenzie_lite_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so makenzie_lite_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so makenzie_lite_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in makenzie_lite_categorized_blog.
 */
function makenzie_lite_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'makenzie_lite_categories' );
}
add_action( 'edit_category', 'makenzie_lite_category_transient_flusher' );
add_action( 'save_post',     'makenzie_lite_category_transient_flusher' );

if ( ! function_exists( 'makenzie_lite_pagination' ) ) :

	/**
	 * Output post pagination
	 *
	 * @since 1.0
	 */
	function makenzie_lite_pagination( $atts = false ) {

		// The output will be stored here
		$output = '';

		if ( is_numeric( get_query_var( 'page' ) ) ) { $paged = get_query_var( 'page' ); } elseif ( is_numeric( get_query_var( 'paged' ) ) ) { $paged = get_query_var( 'paged' ); } else { $paged = 1; }

		if ( ! isset( $atts['force_number'] ) ) $force_number = false; else $force_number = $atts['force_number'];
		if ( ! isset( $atts['pages'] ) ) $pages = false; else $pages = $atts['pages'];
		if ( ! isset( $atts['type'] ) ) $type = 'numbered'; else $type = $atts['type'];
		$range = 2;

		$showitems = ($range * 2)+1;

		if ( empty ( $paged ) ) { $paged = 1; }

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
		}

		if( 1 != $pages ) {
			?>
			<div class="pagination clear pagination-type-<?php echo esc_attr( $type ); ?>">
				<ul class="clear">
					<?php

						if ( $type == 'numbered' ) {

							if($paged > 2 && $paged > $range+1 && $showitems < $pages) { echo "<li class='inactive'><a href='" . esc_url(get_pagenum_link(1)) . "'>&laquo;</a></li>"; }
							if($paged > 1 && $showitems < $pages) { echo "<li class='inactive'><a href='" . esc_url(get_pagenum_link($paged - 1)) . "' >&lsaquo;</a></li>"; }

							for ($i=1; $i <= $pages; $i++){
								if (1 != $pages &&(!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems)){
									echo ($paged == $i)? "<li class='active'><a href='" . get_pagenum_link($i) . "'>" . $i . "</a></li>":"<li class='inactive'><a class='inactive' href='" . esc_url(get_pagenum_link($i)) . "'>" . $i . "</a></li>";
								}
							}

							if ($paged < $pages && $showitems < $pages) { echo "<li class='inactive'><a href='" . esc_url(get_pagenum_link($paged + 1)) . "'>&rsaquo;</a></li>"; }
							if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) { echo "<li class='inactive'><a href='" . get_pagenum_link($pages) . "'>&raquo;</a></li>"; }

						} elseif ( $type == 'prevnext' ) {

							if($paged > 1 ) { echo "<li class='inactive float-left'><a href='" . esc_url(get_pagenum_link($paged - 1)) . "' >" . _e( 'Newer', 'makenzie-lite' ) . "</a></li>"; }
							if ($paged < $pages ) { echo "<li class='inactive float-right'><a href='" . esc_url(get_pagenum_link($paged + 1)) . "'>" . _e( 'Older', 'makenzie-lite' ) . "</a></li>"; }

						} elseif ( $type == 'default' ) {

							posts_nav_link();

						}

					?>
				</ul>

			</div><!-- .pagination --><?php
		}

	}

endif;
