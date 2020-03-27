<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Makenzie_Blog
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php $makenzie_wide_boxed = makenzie_lite_get_theme_mod( 'wide_boxed', 'boxed' ); if ( $makenzie_wide_boxed == 'wide' ) : ?>
	<div id="page" class="site">
<?php else : ?>
	<div id="page" class="boxed site">
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'makenzie-lite' ); ?></a>

<?php if ( has_header_image() ) : ?>
	<header style="background-image: url(<?php header_image(); ?>);" id="masthead" class="site-header">
<?php else : ?>
	<header id="masthead" class="site-header">
<?php endif; ?>


	<?php
		// Top Bar
		if ( makenzie_lite_get_theme_mod( 'top_bar_on_off', 'on' ) == 'on' ) :
		get_template_part( 'template-parts/header/top-bar' );
		endif;
	?>

	<?php
		// Header Style
		get_template_part( 'template-parts/header/headers/header-1' );
	?>

	<!-- Mobile Navigation -->
	<?php get_template_part( 'template-parts/header/mobile-navigation' ); ?>

</header>

	<nav id="desktop-site-navigation" class="main-navigation">
		<?php wp_nav_menu( array( 'theme_location' => 'header-1-menu', 'menu_id' => 'primary-menu', 'menu_class' => 'nav-menu' ) ); ?>
	</nav><!-- #site-navigation -->

	<?php
		// Sliders
		if (is_page_template('template-home.php') || is_front_page()) {
			get_template_part( 'template-parts/header/sliders/slider-1' );
		}
	?>

	<div id="content" class="site-content">
		<div class="grid-container fluid">
			<div class="grid-x grid-margin-x grid-padding-x">
