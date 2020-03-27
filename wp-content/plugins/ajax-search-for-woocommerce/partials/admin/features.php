<?php

use DgoraWcas\Admin\Promo\Upgrade;

$utmLink = 'https://ajaxsearch.pro/?utm_source=wp-admin&utm_medium=referral&utm_campaign=settings&utm_content=features&utm_gen=utmdc#features-comparsion';
?>

<div class="dgwt-wcas-upgrade-s">
    <h2 class="dgwt-wcas-upgrade-s__title"><?php _e('If users can’t find the product they’re searching for, they can’t buy it. Help your customers to find the right products <span>faster and simpler</span>.', 'ajax-search-for-woocommerce'); ?></h2>
    <p class="dgwt-wcas-upgrade-s__subtitle">
        <?php _e('Update now and increase your sales. You will receive 14-day satisfaction guarantee.  A return on investment will come very quickly.', 'ajax-search-for-woocommerce'); ?>
    </p>
    <ul>
        <li><strong>+ <?php _e('Speed up search! (even <b>10x faster</b>) - users love it!', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Help in embedding the search form in your theme. <b>Our developers will do it for you</b>.', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Efficient with a large number of products. Tested on <b>60 000 products</b>', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('New modern search engine based on bm25 ranking algorithm', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Fuzzy search', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Search in variation products SKUs (option)', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Search in product attributes (option)', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Search in product custom fields (option)', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Individual customization of the search form (simple CSS improvements)', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('Individual tips and support', 'ajax-search-for-woocommerce'); ?></strong></li>
        <li><strong>+ <?php _e('And more...', 'ajax-search-for-woocommerce'); ?> <a target="_blank" href="<?php echo $utmLink; ?>"><?php _e('See a comparison of all free and premium features!', 'ajax-search-for-woocommerce'); ?></a></strong></li>
    </ul>
    <a href="<?php echo Upgrade::getUpgradeUrl(); ?>" class="button ajax-build-index-primary"><?php _e('Upgrade Now!', 'ajax-search-for-woocommerce'); ?></a>
</div>