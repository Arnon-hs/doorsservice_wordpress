<?php

	/**
	 * Template part for displaying header 3
	 *
	 * @link https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package makenzie_lite
	 */

// Social options

$makenzie_social_on_off = makenzie_lite_get_theme_mod( 'social_on_off', 'on' );
$makenzie_header_facebook = makenzie_lite_get_theme_mod( 'header_facebook', '' );
$makenzie_header_twitter = makenzie_lite_get_theme_mod( 'header_twitter', '' );
$makenzie_header_instagram = makenzie_lite_get_theme_mod( 'header_instagram', '' );
$makenzie_header_pinterest = makenzie_lite_get_theme_mod( 'header_pinterest', '' );
$makenzie_header_behance = makenzie_lite_get_theme_mod( 'header_behance', '' );
$makenzie_header_etsy = makenzie_lite_get_theme_mod( 'header_etsy', '' );
$makenzie_header_youtube = makenzie_lite_get_theme_mod( 'header_youtube', '' );

// Search options

$makenzie_search_on_off = makenzie_lite_get_theme_mod( 'search_on_off', 'on' );

?>
<div class="site-header-wrapper header-1">
	<div class="grid-container fluid">
		<div class="grid-x grid-margin-x">
			<div class="cell small-1 medium-3 hide-for-small-only">
			<!-- Desktop Social -->
			<?php if ( $makenzie_social_on_off == 'on' ) : ?>
				<div class="follow-us-text">
					<?php _e( 'Follow us!', 'makenzie-lite' ); ?>
				</div>
				<ul class="social-header">

					<?php if ( $makenzie_header_facebook ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_facebook ); ?>"><i class="fab fa-facebook-f"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_twitter ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_twitter ); ?>"><i class="fab fa-twitter"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_instagram ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_instagram ); ?>"><i class="fab fa-instagram"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_pinterest ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_pinterest ); ?>"><i class="fab fa-pinterest"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_behance ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_behance ); ?>"><i class="fab fa-behance"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_etsy ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_etsy ); ?>"><i class="fab fa-etsy"></i></a></li>
					<?php endif; ?>
					<?php if ( $makenzie_header_youtube ) :  ?>
						<li><a href="<?php echo esc_url( $makenzie_header_youtube ); ?>"><i class="fab fa-youtube"></i></a></li>
					<?php endif; ?>

				</ul>

			<?php endif; ?>
			</div>

			<div class="cell auto text-center">
				<?php
					if ( has_custom_logo() ) :
					$makenzie_custom_logo_id = get_theme_mod( 'custom_logo' );
					$makenzie_image = wp_get_attachment_image_src( $makenzie_custom_logo_id , 'full' );
				?>

				<a class="custom-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img class="custom-logo" src="<?php echo $makenzie_image[0] ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
				</a>

				<?php elseif ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>

				<?php
					$makenzie_description = get_bloginfo( 'description', 'display' );
					if ( $makenzie_description || is_customize_preview() ) :
				?>

					<p class="site-description text-center"><?php echo $makenzie_description; ?></p>

				<?php endif; ?>
			</div>

			<div class="cell small-3">
				<!-- Seach Box -->
				<?php if ( $makenzie_search_on_off == 'on' ) : ?>
					<div class="desktop-search">
						<?php get_search_form(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
