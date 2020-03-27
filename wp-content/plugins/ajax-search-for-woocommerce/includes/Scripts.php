<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Scripts
{
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'jsScripts' ) );
        add_action( 'wp_print_styles', array( $this, 'cssStyle' ) );
    }
    
    /**
     * Register scripts.
     * Uses a WP hook wp_enqueue_scripts
     *
     * @return void
     */
    public function jsScripts()
    {
        
        if ( !is_admin() ) {
            // Labels
            $t = array(
                'sale_badge'      => __( 'Sale', 'woocommerce' ),
                'featured_badge'  => __( 'Featured', 'woocommerce' ),
                'category'        => __( 'Category', 'woocommerce' ),
                'tag'             => __( 'Tag' ),
                'brand'           => __( 'Brand', 'ajax-search-for-woocommerce' ),
                'post'            => __( 'Post' ),
                'page'            => __( 'Page' ),
                'product_cat_plu' => __( 'Categories', 'woocommerce' ),
                'product_tag_plu' => __( 'Tags' ),
                'product_plu'     => __( 'Products', 'woocommerce' ),
                'brand_plu'       => __( 'Brands', 'ajax-search-for-woocommerce' ),
                'post_plu'        => __( 'Posts' ),
                'page_plu'        => __( 'Pages' ),
                'sku_label'       => __( 'SKU', 'woocommerce' ) . ':',
            );
            $label_seeall = __( 'See all products...', 'ajax-search-for-woocommerce' );
            $label_nores = __( 'No results', 'ajax-search-for-woocommerce' );
            // Main JS
            $localize = array(
                't'                      => $t,
                'ajax_search_endpoint'   => \WC_AJAX::get_endpoint( DGWT_WCAS_SEARCH_ACTION ),
                'ajax_details_endpoint'  => \WC_AJAX::get_endpoint( DGWT_WCAS_RESULT_DETAILS_ACTION ),
                'action_search'          => DGWT_WCAS_SEARCH_ACTION,
                'action_result_details'  => DGWT_WCAS_RESULT_DETAILS_ACTION,
                'min_chars'              => 3,
                'width'                  => 'auto',
                'show_details_box'       => false,
                'show_images'            => false,
                'show_price'             => false,
                'show_desc'              => false,
                'show_sale_badge'        => false,
                'show_featured_badge'    => false,
                'is_rtl'                 => ( is_rtl() == true ? true : false ),
                'show_preloader'         => false,
                'show_headings'          => false,
                'preloader_url'          => '',
                'taxonomy_brands'        => '',
                'copy_no_result'         => DGWT_WCAS()->settings->getOption( 'search_no_results_text', $label_nores ),
                'copy_show_more'         => DGWT_WCAS()->settings->getOption( 'search_see_all_results_text', $label_seeall ),
                'copy_in_category'       => _x( 'in', 'in categories fe. in Books > Crime stories', 'ajax-search-for-woocommerce' ),
                'img_url'                => DGWT_WCAS_URL . 'assets/img/',
                'is_premium'             => ( dgoraAsfwFs()->is_premium() ? true : false ),
                'overlay_mobile'         => ( DGWT_WCAS()->settings->getOption( 'enable_mobile_overlay' ) === 'on' ? true : false ),
                'mobile_breakpoint'      => apply_filters( 'dgwt/wcas/scripts/mobile_breakpoint', 992 ),
                'mobile_overlay_wrapper' => apply_filters( 'dgwt/wcas/scripts/mobile_overlay_wrapper', 'body' ),
                'debounce_wait_ms'       => apply_filters( 'dgwt/wcas/scripts/debounce_wait_ms', 400 ),
                'send_ga_events'         => apply_filters( 'dgwt/wcas/scripts/send_ga_events', true ),
                'magnifier_icon'         => Helpers::getMagnifierIco( '' ),
            );
            if ( Multilingual::isMultilingual() ) {
                $localize['current_lang'] = Multilingual::getCurrentLanguage();
            }
            // Min characters
            $min_chars = DGWT_WCAS()->settings->getOption( 'min_chars' );
            if ( !empty($min_chars) && is_numeric( $min_chars ) ) {
                $localize['min_chars'] = absint( $min_chars );
            }
            $sug_width = DGWT_WCAS()->settings->getOption( 'sug_width' );
            if ( !empty($sug_width) && is_numeric( $sug_width ) && $sug_width > 100 ) {
                $localize['sug_width'] = absint( $sug_width );
            }
            // Show/hide Details panel
            if ( DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on' ) {
                $localize['show_details_box'] = true;
            }
            // Show/hide images
            if ( DGWT_WCAS()->settings->getOption( 'show_product_image' ) === 'on' ) {
                $localize['show_images'] = true;
            }
            // Show/hide price
            if ( DGWT_WCAS()->settings->getOption( 'show_product_price' ) === 'on' ) {
                $localize['show_price'] = true;
            }
            // Show/hide description
            if ( DGWT_WCAS()->settings->getOption( 'show_product_desc' ) === 'on' ) {
                $localize['show_desc'] = true;
            }
            // Show/hide description
            if ( DGWT_WCAS()->settings->getOption( 'show_product_sku' ) === 'on' ) {
                $localize['show_sku'] = true;
            }
            // Show/hide sale badge
            if ( DGWT_WCAS()->settings->getOption( 'show_sale_badge' ) === 'on' ) {
                $localize['show_sale_badge'] = true;
            }
            // Show/hide featured badge
            if ( DGWT_WCAS()->settings->getOption( 'show_featured_badge' ) === 'on' ) {
                $localize['show_featured_badge'] = true;
            }
            // Set preloader
            
            if ( DGWT_WCAS()->settings->getOption( 'show_preloader' ) === 'on' ) {
                $localize['show_preloader'] = true;
                $localize['preloader_url'] = esc_url( trim( DGWT_WCAS()->settings->getOption( 'preloader_url' ) ) );
            }
            
            // Show/hide autocomplete headings
            if ( DGWT_WCAS()->settings->getOption( 'show_grouped_results' ) === 'on' ) {
                $localize['show_headings'] = true;
            }
            $min = ( !DGWT_WCAS_DEBUG ? '.min' : '' );
            wp_register_script(
                'jquery-dgwt-wcas',
                apply_filters( 'dgwt/wcas/scripts/js_url', DGWT_WCAS_URL . 'assets/js/search' . $min . '.js' ),
                array( 'jquery' ),
                DGWT_WCAS_VERSION,
                true
            );
            $localize = apply_filters( 'dgwt/wcas/scripts/localize', $localize );
            wp_localize_script( 'jquery-dgwt-wcas', 'dgwt_wcas', $localize );
        }
    
    }
    
    /**
     * Register and enqueue style
     * Uses a WP hook wp_print_styles
     *
     * @return void
     */
    public function cssStyle()
    {
        // Main CSS
        $min = ( !DGWT_WCAS_DEBUG ? '.min' : '' );
        wp_register_style(
            'dgwt-wcas-style',
            apply_filters( 'dgwt/wcas/scripts/css_style_url', DGWT_WCAS_URL . 'assets/css/style' . $min . '.css' ),
            array(),
            DGWT_WCAS_VERSION
        );
        wp_enqueue_style( 'dgwt-wcas-style' );
    }

}