<?php

$submitText = DGWT_WCAS()->settings->getOption('search_submit_text');
$hasSubmit  = DGWT_WCAS()->settings->getOption('show_submit_button');

$labelNoResults = DGWT_WCAS()->settings->getOption('search_no_results_text', __('No results', 'ajax-search-for-woocommerce'));
$labelSeeAll    = DGWT_WCAS()->settings->getOption('search_see_all_results_text', __('See all products...', 'ajax-search-for-woocommerce'));

?>
<div class="dgwt-wcas-preview js-dgwt-wcas-preview">

    <div class="dgwt-wcas-preview-head">
        <span class="dgwt-wcas-preview-header"><?php _e('Preview', 'ajax-search-for-woocommerce'); ?></span>
        <span class="dgwt-wcas-preview-subheader dgwt-wcas-preview-subheader__sb"><?php _e('Search bar', 'ajax-search-for-woocommerce'); ?></span>
        <span class="dgwt-wcas-preview-subheader dgwt-wcas-preview-subheader__ac"><?php _e('Autocomplete', 'ajax-search-for-woocommerce'); ?></span>
    </div>

    <div class="js-dgwt-wcas-preview-source dgwt-wcas-preview-source">

        <div class="js-dgwt-wcas-search-wrapp dgwt-wcas-search-wrapp <?php echo \DgoraWcas\Helpers::searchWrappClasses(); ?>" data-wcas-context="75c2">
            <form class="dgwt-wcas-search-form" role="search" action="http://dev.damiangora.com/" method="get">
                <div class="dgwt-wcas-sf-wrapp">
                    <?php echo \DgoraWcas\Helpers::getMagnifierIco(); ?>

                    <label class="screen-reader-text"><?php _e('Products search', 'ajax-search-for-woocommerce'); ?></label>

                    <input
                            type="search"
                            class="js-dgwt-wcas-search-input dgwt-wcas-search-input"
                            name="<?php echo \DgoraWcas\Helpers::getSearchInputName(); ?>"
                            value="<?php echo get_search_query() ?>"
                            autocomplete="off"
                            placeholder="<?php echo DGWT_WCAS()->settings->getOption('search_placeholder', __('Search for products...', 'ajax-search-for-woocommerce')) ?>"
                    />
                    <div class="dgwt-wcas-preloader"></div>

                    <button type="submit" name="dgwt-wcas-search-submit" class="js-dgwt-wcas-search-submit dgwt-wcas-search-submit"><?php
                        echo '<span class="js-dgwt-wcas-search-submit-l">' . esc_html($submitText) . '</span>';
                        echo '<span class="js-dgwt-wcas-search-submit-m">' . \DgoraWcas\Helpers::getMagnifierIco() . '</span>';
                        ?>
                    </button>

                    <input type="hidden" name="post_type" value="product">
                    <input type="hidden" name="dgwt_wcas" value="1">

                    <input type="hidden" name="lang" value="en">

                </div>
            </form>
        </div>

        <div class="dgwt-wcas-autocomplete">

            <div class="dgwt-wcas-suggestions-wrapp js-dgwt-wcas-suggestions-wrapp woocommerce dgwt-wcas-has-price dgwt-wcas-has-desc dgwt-wcas-has-sku" unselectable="on">

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-brand dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Brands', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-brand">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php _e('Brand', 'ajax-search-for-woocommerce'); ?></span>
                        <?php _e('Sample brand <strong>name</strong>', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-cat dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Categories', 'woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-cat">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php _e('Category', 'woocommerce'); ?></span>
                        <?php _e('Sample category <strong>name</strong>', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-tag dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Tags', 'woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tag">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php _e('Tag', 'woocommerce'); ?></span>
                        <?php _e('Sample tag <strong>name</strong>', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-post dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Posts'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-post">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php _e('Post'); ?></span>
                        <?php _e('Sample post <strong>name</strong>', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-page dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Pages'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-page">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php _e('Page'); ?></span>
                        <?php _e('Sample page <strong>name</strong>', 'ajax-search-for-woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php _e('Products', 'woocommerce'); ?>
                    </span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-product">
                    <span class="js-dgwt-wcas-si dgwt-wcas-si">
                        <img src="<?php echo DGWT_WCAS_URL; ?>assets/img/sample-product.png">
                    </span>
                    <div class="js-dgwt-wcas-content-wrapp dgwt-wcas-content-wrapp">
                        <span class="dgwt-wcas-st">
                            <span class="dgwt-wcas-st-title"><?php _e('Sample product <strong>name</strong>', 'ajax-search-for-woocommerce'); ?></span>
                            <span class="dgwt-wcas-sku js-dgwt-wcas-sku">(<?php _e('SKU', 'woocommerce'); ?>: 0001)</span>
                            <span class="dgwt-wcas-sd js-dgwt-wcas-sd"><?php _e('Lorem <strong>ipsum</strong> dolor sit amet, consectetur adipiscing elit. Quisque gravida lacus nec diam porttitor pharetra. Nulla facilisi. Proin pharetra imperdiet neque, non varius.', 'ajax-search-for-woocommerce'); ?></span>
                        </span>
                        <span class="dgwt-wcas-sp js-dgwt-wcas-sp">
                            <?php echo wc_price(99); ?>
                        </span>
                    </div>
                </div>


                <div class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-more dgwt-wcas-suggestion-more dgwt-wcas-suggestion-no-border-bottom" data-index="7">
                    <span class="dgwt-wcas-st-more"><span class="js-dgwt-wcas-st-more-label"><?php echo $labelSeeAll; ?></span> (73)</span>
                </div>

                <div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-nores js-dgwt-wcas-suggestion-nores dgwt-wcas-hide">
                    <span class="dgwt-wcas-st"><?php echo $labelNoResults; ?></span>
                </div>

            </div>

            <div class="dgwt-wcas-details-wrapp js-dgwt-wcas-details-wrapp woocommerce">
                <div class="dgwt-wcas-details-inner">
                    <div class="dgwt-wcas-product-details">

                        <span class="dgwt-wcas-pd-details">

                            <div class="dgwt-wcas-pd-image">
                                <img src="<?php echo DGWT_WCAS_URL; ?>assets/img/sample-product.png">
                            </div>

                            <div class="dgwt-wcas-pd-rest">

                                <span class="product-title"><?php _e('Sample product name', 'ajax-search-for-woocommerce'); ?></span>

                                <div class="dgwt-wcas-pd-price">
                                    <?php echo wc_price(99); ?>
                                </div>
                            </div>

                        </span>

                        <div class="dgwt-wcas-pd-desc">
                            <?php _e('Lorem <strong>ipsum</strong> dolor sit amet, consectetur adipiscing elit. Quisque gravida lacus nec diam porttitor pharetra. Nulla facilisi. Proin pharetra imperdiet neque, non varius.', 'ajax-search-for-woocommerce'); ?>
                        </div>

                        <div class="dgwt-wcas-pd-addtc js-dgwt-wcas-pd-addtc">
                            <form class="dgwt-wcas-pd-addtc-form" action="" method="post" enctype="multipart/form-data">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus button is-form"> <label class="screen-reader-text" for="quantity_5dd6fc1fd5f44">White Air Meter Gauge for Flat Mount Meter quantity</label>
                                    <input type="number" class="input-text qty text" step="1" min="0" max="9999" name="js-dgwt-wcas-quantity" value="1" title="Qty" size="4" inputmode="numeric">
                                    <input type="button" value="+" class="plus button is-form"></div>
                                <p class="product woocommerce add_to_cart_inline " style="">
                                    <a href="?add-to-cart=17436" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow">Add to cart</a>
                            </p>
                            </form>
                        </div>


                    </div>
                </div>

            </div>

        </div>

    </div>
</div>