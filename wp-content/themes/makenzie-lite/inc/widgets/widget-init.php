<?php
/**
 * Register widget areas.
 *
 * @package WPlook
 * @subpackage Makenzie
 * @since Makenzie 1.0
 */

function makenzie_lite_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'makenzie-lite' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here.', 'makenzie-lite' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if( class_exists( 'WooCommerce' ) ) {
		register_sidebar( array(
			'name'          => __( 'Woocommerce Sidebar', 'makenzie-lite' ),
			'id'            => 'sidebar-woo',
			'description'   => __( 'Add widgets here.', 'makenzie-lite' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	/*-----------------------------------------------------------
		Footer Widget area
	-----------------------------------------------------------*/

	register_sidebar( array(
		'id' => 'footer',
		'name' => __( 'Footer Widget Area', 'makenzie-lite' ),
		'description' => __( 'Drag and Drop widgets to footer widget area.', 'makenzie-lite' ),
		'before_widget' => '<div class="small-12 medium-6 large-auto cell"><div id="%1$s" class="footer-section widget %2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title"><div class="footer-section-body">',
		'after_title' => '</div></h3>',
	) );
}
/** Register sidebars */
add_action( 'widgets_init', 'makenzie_lite_widgets_init' );
?>
