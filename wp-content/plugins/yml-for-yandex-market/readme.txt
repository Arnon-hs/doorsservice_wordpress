=== YML for Yandex Market ===
Contributors: icopydoc
Donate link: https://yasobe.ru/na/yml_for_yandex_market
Tags: yml, yandex, market, export, woocommerce
Requires at least: 4.4.2
Tested up to: 5.3.2
Stable tag: 3.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a YML-feed to upload to Yandex market.

== Description ==

Сreates a YML-feed to upload to Yandex market. The plug-in Woocommerce is required!

= Format and method requirements for product data feeds =

For a better understanding of the principles of YML feed - read this: https://yandex.ru/support/market-tech-requirements/index.html 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the entire `yml-for-yandex-market` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Export Yandex Market-->Settings screen to configure the plugin

== Frequently Asked Questions ==

= How to connect my store to Yandex market? =

Read this:
https://yandex.ru/support/partnermarket/registration/how-to-register.html
https://yandex.ru/support/webmaster/goods-prices/connecting-shop.xml
https://yandex.ru/adv/edu/market-exp/vtoroy-magazin

= What plugin online store supported by your plugin? =

Only Woocommerce.

= How to create a YML feed? =

Go to Yandex Market-->Settings. In the box called "Automatic file creation" select another menu entry (which differs from "off"). You can also change values in other boxes if necessary, then press "Save".
After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message.

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 3.1.1 =
* Fix bugs.
* Plugin code has been optimized.
* Added support for Premmerce Brands for WooCommerce.

= 3.1.0 =
* Fix bugs.
* Added extensions Rozetka Export. See extensions page!
* Added the ability to customize the attribute pickup for the product.

= 3.0.4 =
* Fix bugs with manufacturer_warranty.

= 3.0.3 =
* Fix critical bugs.

= 3.0.2 =
* Fix bugs.
* Updated self-diagnostic modules.
* Fixed a bug due to which some excluded products could mistakenly get into the YML-feed.

= 3.0.1 =
* Fix bugs.

= 3.0.0 =
Meet version 3.0.0!
What's new:
* Added support for multiple YML-feeds!
* Improves stability.
* Slightly improved interface.
* Fix bugs.
* Updated self-diagnostic modules.
* Improved support attribute oldprice.

= 2.3.3 =
* Fix bugs.
* Slightly improved interface.
* Added support individual param for delivery

= 2.3.2 =
* Fix bugs.
* Slightly improved interface.

= 2.3.1 =
* Fix bugs.

= 2.3.0 =
* Fix bugs.
* Added support credit-template.

= 2.2.0 =
* Fix bugs.

= 2.1.6 =
* Updated self-diagnostic modules.

= 2.1.5 =
* Added support for attribute condition.

= 2.1.4 =
* Fix bugs.
* Added a couple of new options.

= 2.1.3 =
* Fix bugs.
* Added extensions Prom Export. See extensions page!

= 2.1.2 =
* Added support for attribute bid.

= 2.1.1 =
* Fix bugs.

= 2.1.0 =
* Fix bugs.

= 2.0.12 =
* Slightly improved interface.

= 2.0.11 =
* Fix bugs.
* Updated manufacturer_warranty mechanism.

= 2.0.10 =
* Fix bugs.
* Improved translation.

= 2.0.9 =
* Fix bugs.
* Added partial compatibility with WPML.
* Improved translation.

= 2.0.8 =
* Added support for old Belarusian ruble (BYR).

= 2.0.7 =
* Fix bugs.

= 2.0.6 =
* Fix bugs.

= 2.0.5 =
* Fix bugs.

= 2.0.4 =
* Fixed a bug due to which drafts were in the feed.

= 2.0.3 =
* Now, ampersands are automatically replaced by html entities in the product name.

= 2.0.2 =
* Fix bugs.

= 2.0.1 =
* Fix bugs.
* Added support for attribute pickup-options (Beta).

= 2.0.0 =
Meet version 2.0.0!
What's new:
* Improves stability.
* Increased feed update rate!
* Logs added.
* Fix bugs.
* Added support for Turbo Pages (Beta).
* Improved vendorCode add mechanism.

= 1.5.1 =
* Fix bugs.

= 1.5.0 =
* Fix bugs.
* Added extensions Promos Export. See extensions page!

= 1.4.11 =
* Now for each product, you can specify an individual value delivery-option.

= 1.4.10 =
* Fix bugs.
* Added encoding info.

= 1.4.9 =
* Fix bugs.
* Added support ALTERNATE_WP_CRON.

= 1.4.8 =
* Fix bugs.

= 1.4.7 =
* Fix bugs.

= 1.4.6 =
* Slightly improved interface.

= 1.4.5 =
* Fix bugs.

= 1.4.4 =
* Fix bugs.
* Added support enable_auto_discounts.
* Added plugin support Yoast SEO.

= 1.4.3 =
* Updated self-diagnostic modules.

= 1.4.2 =
* Fix bugs.

= 1.4.1 =
* Fix bugs.

= 1.4.0 =
* Fix bugs.
* Tags are automatically removed from the description, except p, h3, ul, li and br

= 1.3.11 =
* Fix bugs.
* Added the ability to update the feed when updating products

= 1.3.10 =
* Fix bugs.
* Added support for attributes: delivery-options, expiry, downloadable and age!
* Support discontinued for attribute local_delivery_cost!

= 1.3.9 =
* Fix bugs.
* Exclude from feed products for pre-order.
* Improved algorithm for the export of variation products!
* Important! To work correctly, you need a version of Woocommerce 3.0 or newer

= 1.3.8 =
* Fix bugs.
* Now the sales_notes parameter can be specified for each product separately!

= 1.3.7 =
* Fix bugs.
* Added extensions Book Export. See extensions page!

= 1.3.6 =
* Fix bugs.
* Important! Added attribute group_id for variational products.

= 1.3.5 =
* Fix bugs.
* Improved translation.

= 1.3.4 =
* Added support for the Belarusian ruble (BYN).

= 1.3.3 =
* Fix bugs.

= 1.3.2 =
* Fix bugs.

= 1.3.1 =
* Fix bugs.
* Added support for attribute dimensions!

= 1.3.0 =
* Fix bugs.
* Now extensions are available!

= 1.2.7 =
* Fix bugs.
* Now you can exclude items that are not in stock!

= 1.2.6 =
* Fix bugs.
* Conflict of CSS styles with YITH WooCommerce Wishlist fixed.
* Fixed a bug due to which images could not be unloaded.

= 1.2.5 =
* Now remove get parameters from <picture> tag.

= 1.2.4 =
* Fix bugs.
* Updated translations.
* Added supported EUR.
* Added unique names for variations.

= 1.2.3 =
* Fix bugs.
* Added the ability to adjust the speed of YML feed creation.

= 1.2.2 =
* Fixed a bug due to which the file was not created in MultiSite mode.

= 1.2.1 =
* Fix bugs.

= 1.2 =
Meet version 1.2!
Attention! After upgrading to version 1.2, you must reset the plugin settings!
Attention! After update can change URL yml feed!
What's new:
* Improves stability.
* Added support oldprice.
* Added progress bar.
* Fix bugs.

= 1.1.5 =
* Now supported Variations!
* Fix bugs.

= 1.1.4 =
* Support of shops for adults.
* Fix bugs.

= 1.1.3 =
* Removed sending anonymous statistics.

= 1.1.2 =
* Fix bugs.
* Poll added.

= 1.1.1 =
* Important update! Fixed bug Call to undefined function add_blog_options line 80

= 1.1.0 =
* Added support for attributes manufacturer_warranty, barcode, country_of_origin.
* Now supported MultiSite WordPress.
* Fix bugs.
* Redesign of the settings page.
* Added ability to reset plugin settings.
* Updated translations.

= 1.0.6 =
* Now products with no prices are not unloadeds.
* Now supported parameter Price from.

= 1.0.5 =
* Added support for attributes sales_notes.

= 1.0.4 =
* Added support for attributes model, store.
* Improved the translation. 
* Fix bugs.

= 1.0.3 =
* Fix bugs.

= 1.0.2 =
* Added support description and vendorCode params.

= 1.0.1 =
* Temporarily disabled local_delivery_days.

= 1.0.0 =
* First relise.

== Upgrade Notice ==

= 3.1.1 =
* Fix bugs.
* Plugin code has been optimized.
* Added support for Premmerce Brands for WooCommerce.