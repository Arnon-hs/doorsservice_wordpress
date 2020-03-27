<?php
// Exit if accessed directly
if ( !defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$submitText = DGWT_WCAS()->settings->getOption( 'search_submit_text' );
$hasSubmit = DGWT_WCAS()->settings->getOption( 'show_submit_button' );
$uniqueID = ++DGWT_WCAS()->searchInstances . substr(uniqid(), 10, 3);
?>

<div class="dgwt-wcas-search-wrapp <?php echo \DgoraWcas\Helpers::searchWrappClasses( $args ); ?>" data-wcas-context="<?php echo $uniqueID; ?>">
    <form class="dgwt-wcas-search-form" role="search" action="<?php echo \DgoraWcas\Helpers::searchFormAction(); ?>" method="get">
        <div class="dgwt-wcas-sf-wrapp">
            <?php echo $hasSubmit !== 'on' ? \DgoraWcas\Helpers::getMagnifierIco() : ''; ?>
            <label class="screen-reader-text" for="dgwt-wcas-search-input-<?php echo $uniqueID; ?>"><?php _e( 'Products search', 'ajax-search-for-woocommerce' ) ?></label>
			
            <input id="dgwt-wcas-search-input-<?php echo $uniqueID; ?>"
				type="search"
				class="dgwt-wcas-search-input"
				name="<?php echo \DgoraWcas\Helpers::getSearchInputName(); ?>"
				value="<?php echo get_search_query() ?>"
				placeholder="<?php echo DGWT_WCAS()->settings->getOption( 'search_placeholder', __( 'Search for products...', 'ajax-search-for-woocommerce' ) ) ?>"
				/>
			<div class="dgwt-wcas-preloader"></div>
			
			<?php if($hasSubmit === 'on'): ?>
			<button type="submit" name="dgwt-wcas-search-submit" class="dgwt-wcas-search-submit"><?php echo empty( $submitText ) ? \DgoraWcas\Helpers::getMagnifierIco() : esc_html( $submitText ); ?></button>
			<?php endif; ?>
			
			<input type="hidden" name="post_type" value="product" />
			<input type="hidden" name="dgwt_wcas" value="1" />

			<?php
            // WPML compatible
			if ( defined( 'ICL_LANGUAGE_CODE' ) ):
				?>
				<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
			<?php endif ?>

            <?php do_action('dgwt/wcas/form'); ?>
        </div>
    </form>
</div>