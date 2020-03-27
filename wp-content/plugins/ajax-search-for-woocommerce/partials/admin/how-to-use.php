<?php

use DgoraWcas\Admin\Promo\Upgrade;

// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

?>
    <h4><?php _e('There are four easy ways to display the search bar in your theme', 'ajax-search-for-woocommerce'); ?>: </h4>
    <ol>
        <li><?php printf(__('As a menu item - go to the %s and add menu item "AJAX Search box"', 'ajax-search-for-woocommerce'), '<a href="' . admin_url('nav-menus.php') . '" target="_blank">' . __('Menu Screen', 'ajax-search-for-woocommerce') . '</a>') ?>
        <li><?php printf(__('By shortcode - %s', 'ajax-search-for-woocommerce'), '<code>[wcas-search-form]</code>'); ?></li>
        <li><?php printf(__('As a widget - go to the %s and choose "AJAX Search box"', 'ajax-search-for-woocommerce'), '<a href="' . admin_url('widgets.php') . '" target="_blank">' . __('Widgets Screen', 'ajax-search-for-woocommerce') . '</a>') ?>
        <li><?php printf(__('By PHP - %s', 'ajax-search-for-woocommerce'), '<code>&lt;?php echo do_shortcode(\'[wcas-search-form]\'); ?&gt;</code>'); ?></li>
    </ol>
<?php if ( ! dgoraAsfwFs()->is_premium()): ?>
    <span class="dgwt-wcas-our-devs"><?php printf(__('Are there any difficulties? <a href="%s">Upgrade now</a> and our developers will help you <b>embed</b>, <b>adjust</b> and <b>configure</b> the search box in your theme.', 'ajax-search-for-woocommerce'), Upgrade::getUpgradeUrl()); ?></span>
<?php endif; ?>