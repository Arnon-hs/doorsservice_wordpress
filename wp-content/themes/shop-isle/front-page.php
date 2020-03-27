<?php
/**
 * The front-page.php
 *
 * @package ShopIsle
 */

get_header();
/* Wrapper start */

echo '<div class="main">';
$big_title = get_template_directory() . '/inc/sections/shop_isle_big_title_section.php';
load_template( apply_filters( 'shop-isle-subheader', $big_title ) );

/* Wrapper start */
$shop_isle_bg = get_theme_mod( 'background_color' );

if ( isset( $shop_isle_bg ) && $shop_isle_bg != '' ) {
	echo '<div class="main front-page-main" style="background-color: #' . $shop_isle_bg . '">';
} else {

	echo '<div class="main front-page-main" style="background-color: #FFF">';

}

if ( defined( 'WCCM_VERISON' ) ) {

	/* Woocommerce compare list plugin */
	echo '<section class="module-small wccm-frontpage-compare-list">';
	echo '<div class="container">';
	do_action( 'shop_isle_wccm_compare_list' );
	echo '</div>';
	echo '</section>';

}

/******  Banners Section */
$banners_section = get_template_directory() . '/inc/sections/shop_isle_banners_section.php';
require_once( $banners_section );

/******* Video Section */
$video = get_template_directory() . '/inc/sections/shop_isle_video_section.php';
require_once( $video );

/******* Products Slider Section */
$products_slider = get_template_directory() . '/inc/sections/shop_isle_products_slider_section.php';
require_once( $products_slider );?>

<div class="supremes blue_mat" style="background: url(<?= $_SERVER['DOCUMENT_ROOT'];?>/wp-includes/images/media/w.png) left no-repeat #fff;">
        <!-- style="background-image: url(assets/images/slide) -->
        <div class="container">
            <div class="c1 c">
                <p class="h1-dop"></p><h2>Материал гимнастического покрытия</h2><p></p>
                <div class="itm i1">
                    <div class="itmtop">
                        <p> Упругость — пенополиуретан мгновенно восстанавливает структуру после вдавливания, предотвращая получение травмы в результате падения или приземления на ноги</p>
                    </div>
                </div>
                <div class="itm i2">
                    <div class="itmtop">
                       <p>Прочность — товар сохраняет вспененную структуру в течение нескольких лет, не слёживается, не становится плоским</p> 
                    </div>
                    
                </div>
                <div class="itm i3">
                    <div class="itmtop">
                       <p>Безопасность — ППУ не выделяет в воздух неприятных запахов, вредных для здоровья химических соединений</p> 
                    </div>
                </div>
                <div class="itm i4">
                    <div class="itmtop">
                       <p>Теплоизоляция — тренировочные маты не промерзают даже на холодном полу, не позволяют занимающемуся простыть</p> 
                    </div>
                </div>
            </div>
        </div>
</div>

<? 
/******* Products Section */
$latest_products = get_template_directory() . '/inc/sections/shop_isle_products_section.php';
require_once( $latest_products ); 
phpinfo();
echo do_shortcode('[contact-form-7 id="8" title="Контактная форма"]');
 get_footer();

?>