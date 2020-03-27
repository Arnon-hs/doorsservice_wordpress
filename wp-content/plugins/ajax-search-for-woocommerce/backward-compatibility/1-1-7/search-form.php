<?php
// Exit if accessed directly
if ( !defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

/*
 * Add css classes to search form
 *
 * @param array $args (shortcode args)
 * @return string
 */
if(!function_exists('dgwt_wcas_search_css_classes_1_1_7')) {
    function dgwt_wcas_search_css_classes_1_1_7($args = array())
    {

        $classes = array();

        if (DGWT_WCAS()->settings->getOption('show_details_box') === 'on') {
            $classes[] = 'dgwt-wcas-is-detail-box';
        }

        if (DGWT_WCAS()->settings->getOption('show_submit_button') !== 'on') {
            $classes[] = 'dgwt-wcas-no-submit';
        }

        if (isset($args['class']) && ! empty($args['class'])) {
            $classes[] = esc_html($args['class']);
        }

        if (is_rtl()) {
            $classes[] = 'dgwt_wcas_rtl';
        }

        return implode(' ', $classes);
    }
}

if(!function_exists('dgwt_wcas_print_ico_loupe_1_1_7')) {
    function dgwt_wcas_print_ico_loupe_1_1_7()
    {
        ?>
        <svg version="1.1" class="dgwt-wcas-ico-loupe" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 51.539 51.361" enable-background="new 0 0 51.539 51.361" xml:space="preserve">
		<path d="M51.539,49.356L37.247,35.065c3.273-3.74,5.272-8.623,5.272-13.983c0-11.742-9.518-21.26-21.26-21.26
			  S0,9.339,0,21.082s9.518,21.26,21.26,21.26c5.361,0,10.244-1.999,13.983-5.272l14.292,14.292L51.539,49.356z M2.835,21.082
			  c0-10.176,8.249-18.425,18.425-18.425s18.425,8.249,18.425,18.425S31.436,39.507,21.26,39.507S2.835,31.258,2.835,21.082z"/>
	</svg>
        <?php
    }
}

$submit_text = DGWT_WCAS()->settings->getOption( 'search_submit_text' );
$has_submit = DGWT_WCAS()->settings->getOption( 'show_submit_button' );

?>
<div class="dgwt-wcas-search-wrapp <?php echo dgwt_wcas_search_css_classes_1_1_7( $args ); ?>">
    <form class="dgwt-wcas-search-form" role="search" action="<?php echo esc_url( home_url( '/' ) ) ?>" method="get">
        <div class="dgwt-wcas-sf-wrapp">

            <?php
			if($has_submit !== 'on'){
			    dgwt_wcas_print_ico_loupe_1_1_7();
			}
			?>	
            <label class="screen-reader-text" for="dgwt-wcas-search"><?php _e( 'Products search', 'ajax-search-for-woocommerce' ) ?></label>

            <input 
				type="search"
				class="dgwt-wcas-search-input"
				name="s"
				value="<?php echo get_search_query() ?>"
				placeholder="<?php echo DGWT_WCAS()->settings->getOption( 'search_placeholder', __( 'Search for products...', 'ajax-search-for-woocommerce' ) ) ?>"
				/>
			<div class="dgwt-wcas-preloader"></div>
			
			<?php if($has_submit === 'on'): ?>
			<button type="submit" class="dgwt-wcas-search-submit"><?php echo empty( $submit_text ) ? dgwt_wcas_print_ico_loupe_1_1_7() : esc_html( $submit_text ); ?></button>
			<?php endif; ?>
			
			<input type="hidden" name="post_type" value="product" />
			<input type="hidden" name="dgwt_wcas" value="1" />

			<?php
// WPML compatible
			if ( defined( 'ICL_LANGUAGE_CODE' ) ):
				?>
				<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
			<?php endif ?>

        </div>
    </form>
</div>