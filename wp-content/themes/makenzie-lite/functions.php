<?php
/**
 * Makenzie Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package makenzie_lite
 */

define( 'MAKENZIE_LITE_CUSTOMIZER_PREPEND', 'makenzie_lite_theme_' );

if ( ! function_exists( 'makenzie_lite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */

function makenzie_lite_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Makenzie Blog, use a find and replace
	 * to change 'makenzie-lite' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'makenzie-lite', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'makenzie-blog-slider-thumb', 366, 406, true );
	add_image_size( 'makenzie-blog-slider-mobile-thumb', 485, 400, true );
	add_image_size( 'makenzie-blog-listing1-thumb', 433, 414, true );
	add_image_size( 'makenzie-blog-listing2-thumb', 1032, 9999, false );
	add_image_size( 'makenzie-blog-listing3-thumb', 492, 9999, false );
	add_image_size( 'makenzie-blog-single-thumb', 1032, 615, true );


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'header-1-menu' => __( 'Header 1 & 2 Menu', 'makenzie-lite' ),
		'menu-mobile' => __( 'Mobile Menu', 'makenzie-lite' ),
		'top-bar-menu' => __( 'Top Bar Menu', 'makenzie-lite' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
		'comment-list',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add theme support for Woocommerce
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );


}
endif;
add_action( 'after_setup_theme', 'makenzie_lite_setup' );

// Allow Shortcodes in the Excerpt field
add_filter('the_excerpt', 'do_shortcode');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */

if ( !function_exists( 'makenzie_lite_content_width' ) ) {
	function makenzie_lite_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'makenzie_lite_content_width', 1032 );
	}
	add_action( 'after_setup_theme', 'makenzie_lite_content_width', 0 );
}

/* Add class to the body if it has a featured image and if it has a slider*/

function makenzie_lite_body_class($classes) {
	if ( has_post_thumbnail() ) {
		array_push($classes, 'has-featured-image');
	}
	if (isset($_GET['boxed'])) {
		array_push($classes, 'is-boxed');
	}
	if (isset($_GET['slider'])) {
		array_push($classes, 'has-no-slider');
	}
	return $classes;
}
add_filter('body_class', 'makenzie_lite_body_class' );


/**
 * Returns customizer option value
 *
 * @since 1.0
 */
function makenzie_lite_get_theme_mod( $option_id, $default = '' ) {

	$return = get_theme_mod( MAKENZIE_LITE_CUSTOMIZER_PREPEND . $option_id, $default );
	if ( $return == '' ) { $return = $default; }

	return $return;

}

/*-----------------------------------------------------------------------------------*/
/*	Include CSS
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'makenzie_lite_css_frontend' ) ) {

	function makenzie_lite_css_frontend() {
		$makenzie = wp_get_theme();

		// Main stylesheet
		wp_enqueue_style( 'makenzie-blog-style', get_stylesheet_uri(), array(),$makenzie->get('Version') );

		// Vendors
		wp_enqueue_style( 'vendors', get_template_directory_uri() . '/assets/styles/vendors.min.css' );
		//wp_enqueue_style( 'makenzie-blog-grid', get_template_directory_uri() . '/css/foundation.css' );

	}

	add_action( 'wp_enqueue_scripts', 'makenzie_lite_css_frontend' );
}

/*-----------------------------------------------------------------------------------*/
/*	Include JavaScript
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'makenzie_lite_js_frontend' ) ) {

	function makenzie_lite_js_frontend() {

		$vendor = get_template_directory_uri() . '/assets/javascripts';
		$makenzie = wp_get_theme();

		// jQuery
		wp_enqueue_script( 'jquery' );

		// Foundation 6
		wp_enqueue_script( 'foundation', $vendor . '/foundation.min.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'slick', $vendor . '/slick.min.js', array( 'jquery' ), $makenzie->get('Version'), true );
		wp_enqueue_script( 'makenzie-blog-navigation', $vendor . '/navigation.min.js', array( 'jquery' ), $makenzie->get('Version'), true );
		wp_enqueue_script( 'makenzie-blog-plugins', $vendor . '/plugins.min.js', array( 'jquery' ), $makenzie->get('Version'), true );


		wp_localize_script( 'makenzie-blog-navigation', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'makenzie-lite' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'makenzie-lite' ) . '</span>',
		) );



		wp_enqueue_script( 'makenzie-blog-skip-link-focus-fix', get_template_directory_uri() . '/assets/javascripts/skip-link-focus-fix.min.js', array(), $makenzie->get('Version'), true );

		// Google Fonts
		wp_enqueue_style( 'makenzie-blog-google-fonts', makenzie_lite_fonts_url(), array(), true );


		// Comment reply
		if ( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1 ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Main JavaScript File - Custom Javascript
		wp_enqueue_script( 'main', $vendor . '/main.min.js', array( 'jquery' ), false, true );
	}

	add_action( 'wp_enqueue_scripts', 'makenzie_lite_js_frontend' );

}


/*-----------------------------------------------------------
	Get Date
-----------------------------------------------------------*/

if ( ! function_exists( 'makenzie_lite_get_dates' ) ) {

	function makenzie_lite_get_dates() {
		the_time('M j');
	}

}

/*-----------------------------------------------------------------------------------*/
/*  Archive functionality
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'makenzie_lite_posts_by_year' ) ) {
	function makenzie_lite_posts_by_year() {
		// array to use for results
		$years = array();

		// get posts from WP
		$posts = get_posts(array(
			'numberposts' => -1,
			'orderby' => 'post_date',
			'order' => 'ASC',
			'post_type' => 'post',
			'post_status' => 'publish'
		));

		// loop through posts, populating $years arrays
		foreach($posts as $post) {
			$years[date('Y', strtotime($post->post_date))][] = $post;
		}

		// reverse sort by year
		krsort($years);

		return $years;
	}
}


/* -------------------------------------------------------------
 * Enable svg support
 * ============================================================*/
if ( ! function_exists( 'makenzie_lite_add_svg_mime_types' ) ) {
	/**
	 * @param $mimes - MIME file type
	 * @return mixed
	 */
	function makenzie_lite_add_svg_mime_types( $mimes ) {
		if ( is_super_admin() ) {
			$mimes['svg'] = 'image/svg+xml';
		}
		return $mimes;
	}
	add_filter( 'upload_mimes', 'makenzie_lite_add_svg_mime_types' );
}




if ( ! function_exists( 'makenzie_lite_fonts_url' ) ) {

	/**
	 * Returns the google fonts URL
	 *
	 * @since 1.0
	 */
	function makenzie_lite_fonts_url() {

		$font_url = '';

		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$font_state = _x( 'on', 'Google fonts: on or off', 'makenzie-lite' );
		if ( 'off' !== $font_state ) {
			$font_url = add_query_arg( 'family', urlencode( 'Overpass:300,400,600,700|Nothing You Could Do|Lato:400,400italic,500,500italic,600,600i,700|Roboto:400|Material Icons' ), "//fonts.googleapis.com/css" );
		}

		return $font_url;
	}

}


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */

require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/theme-options.php';
require get_template_directory() . '/inc/pro/class-customize.php';
/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * Initiate widgets.
 */
require get_template_directory() . '/inc/widgets/widget-init.php';
