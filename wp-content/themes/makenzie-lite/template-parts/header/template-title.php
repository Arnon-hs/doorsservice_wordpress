<?php

/**
 * single
 */
if ( is_singular( array( 'post', 'page' ) ) && ! is_page_template( 'template-homepage.php') && ! is_archive() && ! is_search() ) {

	$makenzie_page_title = get_the_title();

}

if ( is_category() ) {
	$makenzie_page_title =__( 'Browsing Category: ', 'makenzie-lite' ) . '<span>' . single_cat_title( '', false ) . '</span>';
} elseif ( is_tag() ) {
	$makenzie_page_title =__( 'Browsing Tag: ', 'makenzie-lite' ) . '<span>' . single_tag_title( '', false ) . '</span>';
} elseif ( is_author() ) {
	$makenzie_page_title =__( 'All Posts By: ', 'makenzie-lite' ) . '<span>' .get_the_author() . '</span>';
} elseif ( is_year() ) {
	$makenzie_page_title =__( 'Browsing Year: ', 'makenzie-lite' ) . '<span>' .get_the_date( 'Y' ) . '</span>';
} elseif ( is_month() ) {
	$makenzie_page_title =__( 'Browsing Month: ', 'makenzie-lite' ) . '<span>' .get_the_date( 'F Y' ) . '</span>';
} elseif ( is_day() ) {
	$makenzie_page_title =__( 'Browsing Day: ', 'makenzie-lite' ) . '<span>' .get_the_date( 'F j, Y' ) . '</span>';
} elseif ( is_post_type_archive() ) {
	$makenzie_page_title =__( 'Browsing Post: ', 'makenzie-lite' ) . '<span>' .post_type_archive_title( '', false ) . '</span>';
} elseif ( is_tax() ) {
	$makenzie_page_title =__( 'Browsing Category: ', 'makenzie-lite' ) . '<span>' .single_term_title( '', false ) . '</span>';
} elseif ( is_search() ) {
	$makenzie_page_title =__( 'Search Results For: ', 'makenzie-lite' ) . '<span>' .get_search_query() . '</span>';
} elseif ( is_404() ) {
	$makenzie_page_title =__( 'Not Found', 'makenzie-lite' );
}

?>

<div id="page-title">
	<?php if ( $makenzie_page_title ) : ?>
		<h1><?php echo $makenzie_page_title; ?></h1>
	<?php endif; ?>

	<?php if ( is_singular( 'post' ) ) : ?>
		<div class="tagline-post-cats">
			<?php the_category( ' ', '', get_the_ID() ); ?>
		</div><!-- .tagline-post-cats -->
	<?php endif; ?>
</div>
