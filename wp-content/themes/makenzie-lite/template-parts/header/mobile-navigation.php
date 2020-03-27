<?php
/**
 * Template part for displaying navigation
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Makenzie_Blog
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

?>

<nav id="site-navigation" class="main-navigation">
	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"></button>
	<div class="nav-wrapper">
		<?php wp_nav_menu( array( 'theme_location' => 'menu-mobile', 'menu_id' => 'primary-menu-mobile', 'menu_class' => 'nav-menu' ) ); ?>
		<?php if ( $makenzie_social_on_off == 'on' ) : ?>
				<ul id="social-mobile">
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
</nav>
