<?php defined('ABSPATH') OR exit;
/*
Plugin Name: Yml for Yandex Market
Description: Подключите свой магазин к Яндекс Маркету и выгружайте товары, получая новых клиентов!
Tags: yml, yandex, market, export, woocommerce
Author: Maxim Glazunov
Author URI: https://icopydoc.ru
License: GPLv2
Version: 3.1.1
Text Domain: yml-for-yandex-market
Domain Path: /languages/
WC requires at least: 3.0.0
WC tested up to: 3.9.0
*/
/*  Copyright YEAR PLUGIN_AUTHOR_NAME (email : djdiplomat@yandex.ru)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once plugin_dir_path(__FILE__).'/functions.php'; // Подключаем файл функций
require_once plugin_dir_path(__FILE__).'/offer.php';
register_activation_hook(__FILE__, array('YmlforYandexMarket', 'on_activation'));
register_deactivation_hook(__FILE__, array('YmlforYandexMarket', 'on_deactivation'));
register_uninstall_hook(__FILE__, array('YmlforYandexMarket', 'on_uninstall'));
add_action('plugins_loaded', array('YmlforYandexMarket', 'init'));
add_action('plugins_loaded', 'yfym_load_plugin_textdomain'); // load translation
function yfym_load_plugin_textdomain() {
 load_plugin_textdomain('yfym', false, dirname(plugin_basename(__FILE__)).'/languages/');
}
class YmlforYandexMarket {
 protected static $instance;
 public static function init() {
	is_null(self::$instance) AND self::$instance = new self;
	return self::$instance;
 }

 public function __construct() {
	// yfym_DIR contains /home/p135/www/site.ru/wp-content/plugins/myplagin/
	define('yfym_DIR', plugin_dir_path(__FILE__)); 
	// yfym_URL contains http://site.ru/wp-content/plugins/myplagin/
	define('yfym_URL', plugin_dir_url(__FILE__));
	// yfym_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads
	$upload_dir = (object)wp_get_upload_dir();
	define('yfym_UPLOAD_DIR', $upload_dir->basedir);
	// yfym_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads/yfym
	$name_dir = $upload_dir->basedir."/yfym"; 
	define('yfym_NAME_DIR', $name_dir);
	$yfym_keeplogs = yfym_optionGET('yfym_keeplogs');
	define('yfym_KEEPLOGS', $yfym_keeplogs);
	define('yfym_VER', '3.1.1');
	if (!defined('yfym_ALLNUMFEED')) {
		define('yfym_ALLNUMFEED', '5');
	}

	add_action('admin_menu', array($this, 'add_admin_menu'));
	add_filter('upload_mimes', array($this, 'yfym_add_mime_types'));

	add_filter('cron_schedules', array($this, 'cron_add_seventy_sec'));
	add_filter('cron_schedules', array($this, 'cron_add_five_min'));	
	add_filter('cron_schedules', array($this, 'cron_add_six_hours'));

	add_action('yfym_cron_sborki', array($this, 'yfym_do_this_seventy_sec'), 10, 1);
	add_action('yfym_cron_period', array($this, 'yfym_do_this_event'), 10, 1);
		
	// индивидуальные опции доставки товара
	add_action('add_meta_boxes', array($this, 'yfym_add_custom_box'));
	add_action('save_post', array($this, 'yfym_save_post_product_function'), 50, 3);
	// пришлось юзать save_post вместо save_post_product ибо wc блочит обновы
	
	add_action('admin_notices', array($this, 'yfym_admin_notices_function'));

	/* Регаем стили только для страницы настроек плагина */
	add_action('admin_init', function() {
		wp_register_style('yfym-admin-css', plugins_url('css/yfym.css', __FILE__));
	}, 9999);
 }

 public static function yfym_admin_css_func() {
	/* Ставим css-файл в очередь на вывод */
	wp_enqueue_style('yfym-admin-css');
 } 

 public static function yfym_admin_head_css_func() {
	/* печатаем css в шапке админки */
	print '<style>/* Yml for Yandex Market */
		.metabox-holder .postbox-container .empty-container {height: auto !important;}
		.icp_img1 {background-image: url('. yfym_URL .'/img/sl1.jpg);}
		.icp_img2 {background-image: url('. yfym_URL .'/img/sl2.jpg);}
		.icp_img3 {background-image: url('. yfym_URL .'/img/sl3.jpg);}
	</style>';
 }  
 
 // Срабатывает при активации плагина (вызывается единожды)
 public static function on_activation() {
	$upload_dir = (object)wp_get_upload_dir();
	$name_dir = $upload_dir->basedir.'/yfym';
	if (!is_dir($name_dir)) {
	 if (!mkdir($name_dir)) {
		error_log('ERROR: Ошибка создания папки '.$name_dir.'; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
		//return false;
	 }
	}
	$numFeed = '1'; // (string)
	if (!defined('yfym_ALLNUMFEED')) {define('yfym_ALLNUMFEED', '5');}
	$allNumFeed = (int)yfym_ALLNUMFEED;
	for ($i = 1; $i<$allNumFeed+1; $i++) {
		$name_dir = $upload_dir->basedir.'/yfym/feed'.$numFeed;
		if (!is_dir($name_dir)) {
		 if (!mkdir($name_dir)) {
			error_log('ERROR: Ошибка создания папки '.$name_dir.'; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			//return false;
		 }
		}
		yfym_optionADD('yfym_status_cron', 'off', $numFeed);
		yfym_optionADD('yfym_step_export', '500', $numFeed);
		yfym_optionADD('yfym_status_sborki', '-1', $numFeed); // статус сборки файла
		yfym_optionADD('yfym_date_sborki', 'unknown', $numFeed); // дата последней сборки
		yfym_optionADD('yfym_type_sborki', 'yml', $numFeed); // тип собираемого файла yml или xls
		yfym_optionADD('yfym_file_url', '', $numFeed); // урл до файла
		yfym_optionADD('yfym_file_file', '', $numFeed); // путь до файла
		yfym_optionADD('yfym_file_ids_in_yml', '', $numFeed);
		yfym_optionADD('yfym_ufup', '0', $numFeed);
		yfym_optionADD('yfym_magazin_type', 'woocommerce', $numFeed); // тип плагина магазина 
		yfym_optionADD('yfym_vendor', 'none', $numFeed); // тип плагина магазина
		yfym_optionADD('yfym_whot_export', 'all', $numFeed); // что выгружать (все или там где галка)
		yfym_optionADD('yfym_skip_missing_products', '0', $numFeed);
		yfym_optionADD('yfym_date_save_set', 'unknown', $numFeed); // дата сохранения настроек		
		yfym_optionADD('yfym_separator_type', 'type1', $numFeed); 
		
		$blog_title = get_bloginfo('name');
		yfym_optionADD('yfym_shop_name', $blog_title, $numFeed);
		yfym_optionADD('yfym_company_name', $blog_title, $numFeed);
		yfym_optionADD('yfym_main_product', 'other', $numFeed);		
		yfym_optionADD('yfym_adult', 'no', $numFeed);
		yfym_optionADD('yfym_desc', 'fullexcerpt', $numFeed);
		yfym_optionADD('yfym_price_from', 'no', $numFeed); // разрешить "цена от"
		yfym_optionADD('yfym_oldprice', 'no', $numFeed);
		yfym_optionADD('yfym_params_arr', '', $numFeed);
		yfym_optionADD('yfym_add_in_name_arr', '', $numFeed);
		yfym_optionADD('yfym_no_group_id_arr', '', $numFeed);
		yfym_optionADD('yfym_product_tag_arr', '', $numFeed); // id меток таксономии product_tag
		yfym_optionADD('yfym_store', 'false', $numFeed);
		yfym_optionADD('yfym_delivery', 'false', $numFeed);
		yfym_optionADD('yfym_delivery_options', '0', $numFeed);
		yfym_optionADD('yfym_delivery_cost', '0', $numFeed);
		yfym_optionADD('yfym_delivery_days', '32', $numFeed);
		yfym_optionADD('yfym_order_before', '', $numFeed);
		yfym_optionADD('yfym_sales_notes_cat', 'off', $numFeed);
		yfym_optionADD('yfym_sales_notes', '', $numFeed);
		yfym_optionADD('yfym_model', 'none', $numFeed); // атрибут model магазина
		yfym_optionADD('yfym_pickup', 'true', $numFeed);
		yfym_optionADD('yfym_barcode', 'off', $numFeed);
		yfym_optionADD('yfym_enable_auto_discount', '', $numFeed);
		yfym_optionADD('yfym_expiry', 'off', $numFeed);
		yfym_optionADD('yfym_downloadable', 'off', $numFeed);
		yfym_optionADD('yfym_age', 'off', $numFeed);	
		yfym_optionADD('yfym_country_of_origin', 'off', $numFeed);
		yfym_optionADD('yfym_manufacturer_warranty', 'off', $numFeed);
		yfym_optionADD('yfym_errors', '', $numFeed);
		yfym_optionADD('yfym_enable_auto_discounts', '', $numFeed);
		yfym_optionADD('yfym_skip_backorders_products', '0', $numFeed);
		yfym_optionADD('yfym_no_default_png_products', '0', $numFeed);	
		yfym_optionADD('yfym_skip_products_without_pic', '0', $numFeed);
		$numFeed++;		
	}
	if (is_multisite()) {
		add_blog_option(get_current_blog_id(), 'yfym_version', '3.1.1');
		add_blog_option(get_current_blog_id(), 'yfym_keeplogs', '0');
		add_blog_option(get_current_blog_id(), 'yfym_disable_notices', '0');
		add_blog_option(get_current_blog_id(), 'yfym_enable_five_min', '0');
	} else {
		add_option('yfym_version', '3.1.1');
		add_option('yfym_keeplogs', '0');
		add_option('yfym_disable_notices', '0');
		add_option('yfym_enable_five_min', '0');		
	}	
 }
 
 // Срабатывает при отключении плагина (вызывается единожды)
 public static function on_deactivation() {
	$numFeed = '1'; // (string)
	if (!defined('yfym_ALLNUMFEED')) {define('yfym_ALLNUMFEED', '5');}
	$allNumFeed = (int)yfym_ALLNUMFEED;
	for ($i = 1; $i<$allNumFeed+1; $i++) {	 
		wp_clear_scheduled_hook('yfym_cron_period', array($numFeed));
		wp_clear_scheduled_hook('yfym_cron_sborki', array($numFeed));
		$numFeed++;
	}
	deactivate_plugins('yml-for-yandex-market-pro/yml-for-yandex-market-pro.php');
	deactivate_plugins('yml-for-yandex-market-promos-export/yml-for-yandex-market-promos-export.php');
	deactivate_plugins('yml-for-yandex-market-prom-export/yml-for-yandex-market-prom-export.php');
	deactivate_plugins('yml-for-yandex-market-book-export/yml-for-yandex-market-book-export.php');
 } 
 
 // Срабатывает при удалении плагина (вызывается единожды)
 public static function on_uninstall() {
	if (is_multisite()) {		
		delete_blog_option(get_current_blog_id(), 'yfym_version');
		delete_blog_option(get_current_blog_id(), 'yfym_keeplogs');
		delete_blog_option(get_current_blog_id(), 'yfym_disable_notices');
		delete_blog_option(get_current_blog_id(), 'yfym_enable_five_min');			
	} else {
		delete_option('yfym_version');
		delete_option('yfym_keeplogs');
		delete_option('yfym_disable_notices');
		delete_option('yfym_enable_five_min');
	}
	$numFeed = '1'; // (string)
	$allNumFeed = (int)yfym_ALLNUMFEED;
	for ($i = 1; $i<$allNumFeed+1; $i++) {		
		yfym_optionDEL('yfym_shop_name', $numFeed);
		yfym_optionDEL('yfym_company_name', $numFeed);
		yfym_optionDEL('yfym_main_product', $numFeed);			
		yfym_optionDEL('yfym_version', $numFeed);
		yfym_optionDEL('yfym_status_cron', $numFeed);
		yfym_optionDEL('yfym_whot_export', $numFeed);
		yfym_optionDEL('yfym_skip_missing_products', $numFeed);
		yfym_optionDEL('yfym_date_save_set', $numFeed);
		yfym_optionDEL('yfym_separator_type', $numFeed);
		yfym_optionDEL('yfym_status_sborki', $numFeed);
		yfym_optionDEL('yfym_date_sborki', $numFeed);
		yfym_optionDEL('yfym_type_sborki', $numFeed);
		yfym_optionDEL('yfym_vendor', $numFeed);
		yfym_optionDEL('yfym_model', $numFeed);
		yfym_optionDEL('yfym_params_arr', $numFeed);
		yfym_optionDEL('yfym_add_in_name_arr', $numFeed);
		yfym_optionDEL('yfym_no_group_id_arr', $numFeed);
		yfym_optionDEL('yfym_product_tag_arr', $numFeed);
		yfym_optionDEL('yfym_file_url', $numFeed);
		yfym_optionDEL('yfym_file_file', $numFeed);
		yfym_optionDEL('yfym_add_in_name_arr', $numFeed);
		yfym_optionDEL('yfym_ufup', $numFeed);
		yfym_optionDEL('yfym_magazin_type', $numFeed);
		yfym_optionDEL('yfym_pickup', $numFeed);
		yfym_optionDEL('yfym_store', $numFeed);
		yfym_optionDEL('yfym_delivery', $numFeed);
		yfym_optionDEL('yfym_delivery_cost', $numFeed);
		yfym_optionDEL('yfym_delivery_days', $numFeed);
		yfym_optionDEL('yfym_sales_notes_cat', $numFeed);
		yfym_optionDEL('yfym_sales_notes', $numFeed);
		yfym_optionDEL('yfym_price_from', $numFeed);	
		yfym_optionDEL('yfym_desc', $numFeed);
		yfym_optionDEL('yfym_barcode', $numFeed);
		yfym_optionDEL('yfym_enable_auto_discount', $numFeed);
		yfym_optionDEL('yfym_expiry', $numFeed);
		yfym_optionDEL('yfym_downloadable', $numFeed);
		yfym_optionDEL('yfym_age', $numFeed);
		yfym_optionDEL('yfym_country_of_origin', $numFeed);
		yfym_optionDEL('yfym_manufacturer_warranty', $numFeed);
		yfym_optionDEL('yfym_adult', $numFeed);
		yfym_optionDEL('yfym_oldprice', $numFeed);
		yfym_optionDEL('yfym_step_export', $numFeed);
		yfym_optionDEL('yfym_errors', $numFeed);
		yfym_optionDEL('yfym_enable_auto_discounts', $numFeed);
		yfym_optionDEL('yfym_skip_backorders_products', $numFeed);
		yfym_optionDEL('yfym_no_default_png_products', $numFeed);
		yfym_optionDEL('yfym_skip_products_without_pic', $numFeed);
		$numFeed++;
	}
 }

 // Добавляем пункты меню
 public function add_admin_menu() {
	$page_suffix = add_menu_page(null , __('Export Yandex Market', 'yfym'), 'manage_options', 'yfymexport', 'yfym_export_page', 'dashicons-redo', 51);
	require_once yfym_DIR.'/export.php'; // Подключаем файл настроек
	// создаём хук, чтобы стили выводились только на странице настроек
	add_action('admin_print_styles-'. $page_suffix, array($this, 'yfym_admin_css_func'));
 	add_action('admin_print_styles-'. $page_suffix, array($this, 'yfym_admin_head_css_func'));

	add_submenu_page('yfymexport', __('Debug', 'yfym'), __('Debug page', 'yfym'), 'manage_options', 'yfymdebug', 'yfym_debug_page');
	require_once yfym_DIR.'/debug.php';
	add_submenu_page('yfymexport', __('Add Extensions', 'yfym'), __('Extensions', 'yfym'), 'manage_options', 'yfymextensions', 'yfym_extensions_page');
	require_once yfym_DIR.'/extensions.php';
 } 
 
 // Разрешим загрузку xml и csv файлов
 public function yfym_add_mime_types($mimes) {
	$mimes ['csv'] = 'text/csv';
	$mimes ['xml'] = 'text/xml';
	return $mimes;
 } 

 /* добавляем интервалы крон в 70 секунд и 6 часов */
 public function cron_add_seventy_sec($schedules) {
	$schedules['seventy_sec'] = array(
		'interval' => 70,
		'display' => '70 sec'
	);
	return $schedules;
 }
 public function cron_add_five_min($schedules) {
	$schedules['five_min'] = array(
		'interval' => 360,
		'display' => '5 min'
	);
	return $schedules;
 } 
 public function cron_add_six_hours($schedules) {
	$schedules['six_hours'] = array(
		'interval' => 21600,
		'display' => '6 hours'
	);
	return $schedules;
 }
 /* end добавляем интервалы крон в 70 секунд и 6 часов */ 
 
 // Добавляем блок Настройки акции на страницау создания поста акции
 public function yfym_add_custom_box() {
	$screens = array('product');
	add_meta_box('yfym_delivery_options', __('Settings delivery options for Yandex Market', 'yfym'),  array($this,'yfym_meta_box_callback'), $screens /*, 'side'*/ );
 }
 // HTML код блока Настройки акции
 public function yfym_meta_box_callback($post, $meta) {
	// $screens = $meta['args'];
	// Используем nonce для верификации
	wp_nonce_field(plugin_basename(__FILE__), 'yfym_noncename');

	$yfym_meta = new stdClass; // читаем все метаполя
	foreach((array)get_post_meta($post->ID) as $k => $v) $yfym_meta->$k = $v[0];

	if (property_exists($yfym_meta, 'yfym_individual_delivery')) {$yfym_individual_delivery = $yfym_meta->yfym_individual_delivery;} else {$yfym_individual_delivery = 'off';}
	if (property_exists($yfym_meta, 'yfym_cost')) {$yfym_cost = $yfym_meta->yfym_cost;} else {$yfym_cost = '';}	
	if (property_exists($yfym_meta, 'yfym_days')) {$yfym_days = $yfym_meta->yfym_days;} else {$yfym_days = '';}	
	if (property_exists($yfym_meta, 'yfym_order_before')) {$yfym_order_before = $yfym_meta->yfym_order_before;} else {$yfym_order_before = '';}	
	if (property_exists($yfym_meta, 'yfym_individual_pickup')) {$yfym_individual_pickup = $yfym_meta->yfym_individual_pickup;} else {$yfym_individual_pickup = 'off';}
	if (property_exists($yfym_meta, 'yfym_pickup_cost')) {$yfym_pickup_cost = $yfym_meta->yfym_pickup_cost;} else {$yfym_pickup_cost = '';}
	if (property_exists($yfym_meta, 'yfym_pickup_days')) {$yfym_pickup_days = $yfym_meta->yfym_pickup_days;} else {$yfym_pickup_days = '';}
	if (property_exists($yfym_meta, 'yfym_pickup_order_before')) {$yfym_pickup_order_before = $yfym_meta->yfym_pickup_order_before;} else {$yfym_pickup_order_before = '';}
	if (property_exists($yfym_meta, 'yfym_bid')) {$yfym_bid = $yfym_meta->yfym_bid;} else {$yfym_bid = '';} 
	if (property_exists($yfym_meta, 'yfym_condition')) {$yfym_condition = $yfym_meta->yfym_condition;} else {$yfym_condition = 'off';}
	if (property_exists($yfym_meta, 'yfym_reason')) {$yfym_reason = $yfym_meta->yfym_reason;} else {$yfym_reason = '';}
	if (property_exists($yfym_meta, 'yfym_credit_template')) {$yfym_credit_template = $yfym_meta->yfym_credit_template;} else {$yfym_credit_template = '';}
	
	// Поля формы для введения данных
	?>
	<p><span class="description"><?php _e('Here you can set up individual options terms for this product', 'yfym'); ?>. <a target="_blank" href="//yandex.ru/support/partnermarket/elements/delivery-options.html#structure"><?php _e('Read more on Yandex', 'yfym'); ?></a></span></p>
	<table class="form-table"><tbody>
	 <tr>
		<th scope="row"><label for="yfym_individual_delivery"><?php _e('Delivery', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<select name="yfym_individual_delivery" id="yfym_individual_delivery">	
			<option value="off" <?php selected($yfym_individual_delivery, 'off'); ?>><?php _e('Use global settings', 'yfym'); ?></option>	
			<option value="false" <?php selected($yfym_individual_delivery, 'false'); ?>>False</option>
			<option value="true" <?php selected($yfym_individual_delivery, 'true'); ?>>True</option>
			</select><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>delivery</strong>.</span>
		</td>
	 </tr>	
	 <tr>
		<th scope="row"><label for="yfym_cost"><?php _e('Delivery cost', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_cost" min="0" type="number" name="yfym_cost" value="<?php echo $yfym_cost; ?>" /><br />
			<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>cost</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong></span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_days"><?php _e('Delivery days', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_days" type="text" name="yfym_days" value="<?php echo $yfym_days; ?>" /><br />
			<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>days</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong></span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_order_before"><?php _e('The time', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_order_before" type="text" name="yfym_order_before" value="<?php echo $yfym_order_before; ?>" /><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>order-before</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong>.<br /><?php _e('The time in which you need to place an order to get it at this time', 'yfym'); ?></span>
		</td>
	 </tr>
	</tbody></table>
	<p><span class="description"><?php _e('Here you can configure the pickup conditions for this product', 'yfym'); ?>.</span></p>
	<table class="form-table"><tbody>
	 <tr>
		<th scope="row"><label for="yfym_individual_pickup"><?php _e('Pickup', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<select name="yfym_individual_pickup" id="yfym_individual_pickup">	
			<option value="off" <?php selected($yfym_individual_pickup, 'off'); ?>><?php _e('Use global settings', 'yfym'); ?></option>	
			<option value="false" <?php selected($yfym_individual_pickup, 'false'); ?>>False</option>
			<option value="true" <?php selected($yfym_individual_pickup, 'true'); ?>>True</option>
			</select><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>pickup</strong>.</span>
		</td>
	 </tr>	
	 <tr>
		<th scope="row"><label for="yfym_pickup_cost"><?php _e('Pickup cost', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_pickup_cost" min="0" type="number" name="yfym_pickup_cost" value="<?php echo $yfym_pickup_cost; ?>" /><br />
			<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>cost</strong> <?php _e('of attribute', 'yfym'); ?> <strong>pickup-options</strong></span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_pickup_days"><?php _e('Pickup days', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_pickup_days" type="text" name="yfym_pickup_days" value="<?php echo $yfym_pickup_days; ?>" /><br />
			<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>days</strong> <?php _e('of attribute', 'yfym'); ?> <strong>pickup-options</strong></span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_pickup_order_before"><?php _e('The time', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_pickup_order_before" type="text" name="yfym_pickup_order_before" value="<?php echo $yfym_pickup_order_before; ?>" /><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>order-before</strong> <?php _e('of attribute', 'yfym'); ?> <strong>pickup-options</strong>.<br /><?php _e('The time in which you need to place an order to get it at this time', 'yfym'); ?></span>
		</td>
	 </tr>
	</tbody></table>
	<p><span class="description"><?php _e('Bid values', 'yfym'); ?> & <?php _e('Сondition', 'yfym'); ?>.</span></p>
	<table class="form-table"><tbody>
	 <tr>
		<th scope="row"><label for="yfym_bid"><?php _e('Bid values', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_bid" type="text" name="yfym_bid" value="<?php echo $yfym_bid; ?>" /><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>bid</strong>.<br /><?php _e('Bid values in your price list. Specify the bid amount in Yandex cents: for example, the value 80 corresponds to the bid of 0.8 Yandex units. The values must be positive integers', 'yfym'); ?>. <a target="_blank" href="//yandex.ru/support/partnermarket/elements/bid-cbid.html"><?php _e('Read more on Yandex', 'yfym'); ?></a></span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_condition"><?php _e('Сondition', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<select name="yfym_condition" id="yfym_condition">	
			<option value="off" <?php selected($yfym_condition, 'off'); ?>><?php _e('None', 'yfym'); ?></option>			
			<option value="likenew" <?php selected($yfym_condition, 'likenew'); ?>><?php _e('Like New', 'yfym'); ?></option>
			<option value="used" <?php selected($yfym_condition, 'used'); ?>><?php _e('Used', 'yfym'); ?></option>
			</select><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>condition</strong>.</span>
		</td>
	 </tr>
	 <tr>
		<th scope="row"><label for="yfym_reason"><?php _e('Reason', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_reason" type="text" name="yfym_reason" value="<?php echo $yfym_reason; ?>" /><br />
			<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>reason</strong> <?php _e('of attribute', 'yfym'); ?> <strong>condition</strong></span>
		</td>		
	 </tr>	 
	 <tr>
		<th scope="row"><label for="yfym_credit_template"><?php _e('ID of the loan program', 'yfym'); ?></label></th>
		<td class="overalldesc">
			<input id="yfym_credit_template" type="text" name="yfym_credit_template" value="<?php echo $yfym_credit_template; ?>" /><br />
			<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>credit-template</strong>. <a target="_blank" href="//yandex.ru/support/partnermarket/efficiency/credit.html"><?php _e('Read more on Yandex', 'yfym'); ?></a></span>
		</td>	
	 </tr>
	</tbody></table><?php 
 }
 // Сохраняем данные блока, когда пост сохраняется
 function yfym_save_post_product_function ($post_id, $post, $update) {
	yfym_error_log('Стартовала функция yfym_save_post_product_function! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
	
	if ($post->post_type !== 'product') {return;} // если это не товар вукомерц
	if (wp_is_post_revision($post_id)) {return;} // если это ревизия
	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	// если это автосохранение ничего не делаем
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return;}
	// проверяем права юзера
	if (!current_user_can('edit_post', $post_id)) {return;}
	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	yfym_error_log('Работает функция yfym_save_post_product_function! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);

	// Убедимся что поле установлено.
	if (isset($_POST['yfym_cost'])) {	
		$yfym_individual_delivery = sanitize_text_field($_POST['yfym_individual_delivery']);
		$yfym_cost = sanitize_text_field($_POST['yfym_cost']);
		$yfym_days = sanitize_text_field($_POST['yfym_days']);		
		$yfym_order_before = sanitize_text_field($_POST['yfym_order_before']);
		$yfym_individual_pickup = sanitize_text_field($_POST['yfym_individual_pickup']);
		$yfym_pickup_cost = sanitize_text_field($_POST['yfym_pickup_cost']);
		$yfym_pickup_days = sanitize_text_field($_POST['yfym_pickup_days']);
		$yfym_pickup_order_before = sanitize_text_field($_POST['yfym_pickup_order_before']);
		$yfym_bid = sanitize_text_field($_POST['yfym_bid']);
		$yfym_condition = sanitize_text_field($_POST['yfym_condition']);
		$yfym_reason = sanitize_text_field($_POST['yfym_reason']);
		$yfym_credit_template = sanitize_text_field($_POST['yfym_credit_template']);
		
		// Обновляем данные в базе данных
		update_post_meta($post_id, 'yfym_individual_delivery', $yfym_individual_delivery);
		update_post_meta($post_id, 'yfym_cost', $yfym_cost);
		update_post_meta($post_id, 'yfym_days', $yfym_days);
		update_post_meta($post_id, 'yfym_order_before', $yfym_order_before);
		update_post_meta($post_id, 'yfym_individual_pickup', $yfym_individual_pickup);
		update_post_meta($post_id, 'yfym_pickup_cost', $yfym_pickup_cost);
		update_post_meta($post_id, 'yfym_pickup_days', $yfym_pickup_days);
		update_post_meta($post_id, 'yfym_pickup_order_before', $yfym_pickup_order_before);
		update_post_meta($post_id, 'yfym_bid', $yfym_bid);
		update_post_meta($post_id, 'yfym_condition', $yfym_condition);
		update_post_meta($post_id, 'yfym_reason', $yfym_reason);
		update_post_meta($post_id, 'yfym_credit_template', $yfym_credit_template);				
	}
	
	$numFeed = '1'; // (string) создадим строковую переменную
	// нужно ли запускать обновление фида при перезаписи файла
	$allNumFeed = (int)yfym_ALLNUMFEED;
	for ($i = 1; $i<$allNumFeed+1; $i++) {

		yfym_error_log('FEED № '.$numFeed.'; Шаг $i = '.$i.' цикла по формированию кэша файлов; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);

		$result_yml_unit = yfym_unit($post_id, $numFeed); // формируем фид товара
		if (is_array($result_yml_unit)) {
			$result_yml = $result_yml_unit[0];
			$ids_in_yml = $result_yml_unit[1];
		} else {
			$result_yml = $result_yml_unit;
			$ids_in_yml = '';
		}
		yfym_wf($result_yml, $post_id, $numFeed, $ids_in_yml); // записываем кэш-файл

		$yfym_ufup = yfym_optionGET('yfym_ufup', $numFeed);
		if ($yfym_ufup !== 'on') {$numFeed++; continue; /*return;*/}
		$status_sborki = (int)yfym_optionGET('yfym_status_sborki', $numFeed);
		if ($status_sborki > -1) {$numFeed++; continue; /*return;*/} // если идет сборка фида - пропуск
		
		$yfym_date_save_set = yfym_optionGET('yfym_date_save_set', $numFeed);
		$yfym_date_sborki = yfym_optionGET('yfym_date_sborki', $numFeed);

		if ($numFeed === '1') {$prefFeed = '';} else {$prefFeed = $numFeed;}
		if (is_multisite()) {
			/*
			*	wp_get_upload_dir();
			*   'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
			*	'url'     => 'http://site.ru/wp-content/uploads/2016/04',
			*	'subdir'  => '/2016/04',
			*	'basedir' => '/home/site.ru/public_html/wp-content/uploads',
			*	'baseurl' => 'http://site.ru/wp-content/uploads',
			*	'error'   => false,
			*/
			$upload_dir = (object)wp_get_upload_dir();
			$filenamefeed = $upload_dir->basedir."/".$prefFeed."feed-yml-".get_current_blog_id().".xml";
		} else {
			$upload_dir = (object)wp_get_upload_dir();
			$filenamefeed = $upload_dir->basedir."/".$prefFeed."feed-yml-0.xml";
		}
		if (!file_exists($filenamefeed)) {
			yfym_error_log('FEED № '.$numFeed.'; WARNING: Файла filenamefeed = '.$filenamefeed.' не существует! Пропускаем быструю сборку; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			$numFeed++; continue; /*return;*/ 
		} // файла с фидом нет
		
		clearstatcache(); // очищаем кэш дат файлов
		$last_upd_file = filemtime($filenamefeed);
		yfym_error_log('FEED № '.$numFeed.'; $yfym_date_save_set='.$yfym_date_save_set.';$filenamefeed='.$filenamefeed, 0);
		yfym_error_log('FEED № '.$numFeed.'; Начинаем сравнивать даты! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);	
		if ($yfym_date_save_set > $last_upd_file) {
			// настройки фида сохранялись позже, чем создан фид		
			// нужно полностью пересобрать фид
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Настройки фида сохранялись позже, чем создан фид; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			$yfym_status_cron = yfym_optionGET('yfym_status_cron', $numFeed);
			$recurrence = $yfym_status_cron;
			wp_clear_scheduled_hook('yfym_cron_period', array($numFeed));
			wp_schedule_event(time(), $recurrence, 'yfym_cron_period', array($numFeed));
			yfym_error_log('FEED № '.$numFeed.'; yfym_cron_period внесен в список заданий! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
		} else { // нужно лишь обновить цены	
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Настройки фида сохранялись раньше, чем создан фид. Нужно лишь обновить цены; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			yfym_clear_file_ids_in_yml($numFeed); /* С версии 3.1.0 */
			yfym_onlygluing($numFeed);
		}
		$numFeed++;
	}
	return;
 }
  
 /* функции крона */
 public function yfym_do_this_seventy_sec($numFeed = '1') {
	yfym_error_log('FEED № '.$numFeed.'; Крон yfym_do_this_seventy_sec запущен; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);	 
	// yfym_optionGET('yfym_status_sborki', $numFeed);	
	$this->yfym_construct_yml($numFeed); // делаем что-либо каждые 70 сек
 }
 public function yfym_do_this_event($numFeed = '1') {
	yfym_error_log('FEED № '.$numFeed.'; Крон yfym_do_this_event включен. Делаем что-то каждый час; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
	$step_export = (int)yfym_optionGET('yfym_step_export', $numFeed);
	if ($step_export === 0) {$step_export = 500;}		
	yfym_optionUPD('yfym_status_sborki', $step_export, $numFeed);

	wp_clear_scheduled_hook('yfym_cron_sborki', array($numFeed));

	// Возвращает nul/false. null когда планирование завершено. false в случае неудачи.
	$res = wp_schedule_event(time(), 'seventy_sec', 'yfym_cron_sborki', array($numFeed));
	if ($res === false) {
		yfym_error_log('FEED № '.$numFeed.'; ERROR: Не удалось запланировань CRON seventy_sec; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
	} else {
		yfym_error_log('FEED № '.$numFeed.'; CRON seventy_sec успешно запланирован; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
	}
 }
 /* end функции крона */
 
 // Вывод различных notices
 public function yfym_admin_notices_function() {
	$numFeed = '1'; // (string) создадим строковую переменную
	// нужно ли запускать обновление фида при перезаписи файла
	$allNumFeed = (int)yfym_ALLNUMFEED;

	$yfym_disable_notices = yfym_optionGET('yfym_disable_notices');
if ($yfym_disable_notices !== 'on') {
	for ($i = 1; $i<$allNumFeed+1; $i++) {
		$status_sborki = yfym_optionGET('yfym_status_sborki', $numFeed);
		if ($status_sborki == false) {
			$numFeed++; continue;
		} else {
			$status_sborki = (int)$status_sborki;
		}		
		if ($status_sborki !== -1) {	
			$count_posts = wp_count_posts('product');
			$vsegotovarov = $count_posts->publish;
			$step_export = (int)yfym_optionGET('yfym_step_export', $numFeed);
			if ($step_export === 0) {$step_export = 500;}
			$vobrabotke = $status_sborki-$step_export;
			if ($vsegotovarov > $vobrabotke) {
				$vyvod = 'FEED № '.$numFeed.' '. __('Progress', 'yfym').': '.$vobrabotke.' '. __('from', 'yfym').' '.$vsegotovarov.' '. __('products', 'yfym') .'.<br />'.__('If the progress indicators have not changed within 20 minutes, try reducing the "Step of export" in the plugin settings', 'yfym');
			} else {
				$vyvod = 'FEED № '.$numFeed.' '. __('Prior to the completion of less than 70 seconds', 'yfym');
			}	
			print '<div class="updated notice notice-success is-dismissible"><p>'. __('We are working on automatic file creation. YML will be developed soon', 'yfym').'. '.$vyvod.'.</p></div>';
		}
		$numFeed++;
	}
}
	if (class_exists('YmlforYandexMarketBookExport')) {
		if (defined('yfymbe_VER')) {	
			if (version_compare(yfymbe_VER, '2.5.0', '<')) {
				print '<div class="notice error is-dismissible"><p>'. __('You are using the', 'yfym'). ' <strong>Yml for Yandex Market Book Export</strong> ' . __('plugin of obsolete version', 'yfym'). ' '. yfymbe_VER .'. '. __('This version does not know how to work with multiple YML-feeds. I highly recommend upgrading the', 'yfym'). ' <strong>Yml for Yandex Market Book Export</strong> ' .  __('version to', 'yfym'). ' 2.5.0 '.  __('or higher', 'yfym'). '.</p></div>';
			}
		}	
	}

	if (class_exists('YmlforYandexMarketPro')) {
		if (defined('yfymp_VER')) {	
			if (version_compare(yfymp_VER, '4.0.0', '<')) {
				print '<div class="notice error is-dismissible"><p>'. __('You are using the', 'yfym'). ' <strong>Yml for Yandex Market Pro</strong> ' . __('plugin of obsolete version', 'yfym'). ' '. yfymp_VER .'. '. __('This version does not know how to work with multiple YML-feeds. I highly recommend upgrading the', 'yfym'). ' <strong>Yml for Yandex Market Pro</strong> ' .  __('version to', 'yfym'). ' 4.0.0 '.  __('or higher', 'yfym'). '.</p></div>';
			}
		}	
	}

	if (class_exists('YmlforYandexMarketPromos')) {
		if (defined('yfympe_VER')) {	
			if (version_compare(yfympe_VER, '4.0.0', '<')) {
				print '<div class="notice error is-dismissible"><p>'. __('You are using the', 'yfym'). ' <strong>Yml for Yandex Market Promos Export</strong> ' . __('plugin of obsolete version', 'yfym'). ' '. yfympe_VER .'. '. __('This version does not know how to work with multiple YML-feeds. I highly recommend upgrading the', 'yfym'). ' <strong>Yml for Yandex Market Promos Export</strong> ' .  __('version to', 'yfym'). ' 4.0.0 '.  __('or higher', 'yfym'). '.</p></div>';
			}
		}	
	}	
	
	if (class_exists('YmlforYandexMarketProm')) {
		if (defined('yfympr_VER')) {	
			if (version_compare(yfympr_VER, '2.0.0', '<')) {
				print '<div class="notice error is-dismissible"><p>'. __('You are using the', 'yfym'). ' <strong>Yml for Yandex Market Prom Export</strong> ' . __('plugin of obsolete version', 'yfym'). ' '. yfympr_VER .'. '. __('This version does not know how to work with multiple YML-feeds. I highly recommend upgrading the', 'yfym'). ' <strong>Yml for Yandex Market Prom Export</strong> ' .  __('version to', 'yfym'). ' 2.0.0 '.  __('or higher', 'yfym'). '.</p></div>';
			}
		}	
	}	
		

	if (yfym_optionGET('yfym_magazin_type', $numFeed) === 'woocommerce') { 
		if (!class_exists('WooCommerce')) {
			print '<div class="notice error is-dismissible"><p>'. __('WooCommerce is not active!', 'yfym'). '.</p></div>';
		}
	}

	if (is_multisite()) {		
		$yfym_version = get_blog_option(get_current_blog_id(), 'yfym_version');
	} else {
		$yfym_version = get_option('yfym_version');
	}
  	if ($yfym_version !== '3.1.1') {
		print '<div class="notice error is-dismissible"><p>'. __('Plugin has been updated to version', 'yfym').' '. yfym_VER .'. '. __('Please resave the plugin settings', 'yfym'). ' YML for Yandex Market!</p></div>';
	}	
	  
	if (isset($_REQUEST['yfym_submit_action'])) {
		$run_text = '';
		if (sanitize_text_field($_POST['yfym_run_cron']) !== 'off') {
			$run_text = '. '. __('Creating the feed is running. You can continue working with the website', 'yfym');
		}
		print '<div class="updated notice notice-success is-dismissible"><p>'. __('Updated', 'yfym'). $run_text .'.</p></div>';
	}

	if (isset($_REQUEST['yfym_submit_debug_page'])) {
		print '<div class="updated notice notice-success is-dismissible"><p>'. __('Updated', 'yfym'). '.</p></div>';
	}

	if (isset($_REQUEST['yfym_submit_clear_logs'])) {
		$upload_dir = (object)wp_get_upload_dir();
		$name_dir = $upload_dir->basedir."/yfym";
		$filename = $name_dir.'/yfym.log';
		$res = unlink($filename);
		if ($res == true) {
			print '<div class="notice notice-success is-dismissible"><p>' .__('Logs were cleared', 'yfym'). '.</p></div>';					
		} else {
			print '<div class="notice notice-warning is-dismissible"><p>' .__('Error accessing log file. The log file may have been deleted previously', 'yfym'). '.</p></div>';		
		}
	}

	/* сброс настроек */
	if (isset($_REQUEST['yfym_submit_reset'])) {
		if (!empty($_POST) && check_admin_referer('yfym_nonce_action_reset', 'yfym_nonce_field_reset')) {
			$this->on_uninstall();
			$this->on_activation();	
			print '<div class="updated notice notice-success is-dismissible"><p>'. __('The settings have been reset', 'yfym'). '.</p></div>';
		}
	} /* end сброс настроек */
   	
	/* отправка отчёта */
	if (isset($_REQUEST['yfym_submit_send_stat'])) {
		if (!empty($_POST) && check_admin_referer('yfym_nonce_action_send_stat', 'yfym_nonce_field_send_stat')) { 	
		if (is_multisite()) { 
			$yfym_is_multisite = 'включен';	
			$yfym_keeplogs = get_blog_option(get_current_blog_id(), 'yfym_keeplogs');
		} else {
			$yfym_is_multisite = 'отключен'; 
			$yfym_keeplogs = get_option('yfym_keeplogs');
		}
		$numFeed = '1'; // (string)
		$mail_content = "Версия плагина: ". yfym_VER . PHP_EOL;
		$mail_content .= "Версия WP: ".get_bloginfo('version'). PHP_EOL;	 
		$woo_version = yfym_get_woo_version_number();
		$mail_content .= "Версия WC: ".$woo_version. PHP_EOL;	   
		$mail_content .= "Режим мультисайта: ".$yfym_is_multisite. PHP_EOL;
		$mail_content .= "Вести логи: ".$yfym_keeplogs. PHP_EOL;
		$mail_content .= "Расположение логов: ". yfym_UPLOAD_DIR .'/yfym.log'. PHP_EOL;	  
		if (!class_exists('YmlforYandexMarketPro')) {$mail_content .= "Pro: не активна". PHP_EOL;} else {if (!defined('yfymp_VER')) {define('yfymp_VER', 'н/д');} $mail_content .= "Pro: активна (v ".yfymp_VER.")". PHP_EOL;}
		if (!class_exists('YmlforYandexMarketPromosExport')) {$mail_content .= "Promos Export: не активна". PHP_EOL;} else {if (!defined('yfympe_VER')) {define('yfympe_VER', 'н/д');} $mail_content .= "Promos Export: активна (v ".yfympe_VER.")". PHP_EOL;}
		if (!class_exists('YmlforYandexMarketBookExport')) {$mail_content .= "Book Export: не активна". PHP_EOL;} else {if (!defined('yfymbe_VER')) {define('yfymbe_VER', 'н/д');} $mail_content .= "Book Export: активна (v ".yfymbe_VER.")". PHP_EOL;}
		if (!class_exists('YmlforYandexMarketProm')) {$mail_content .= "Prom Export: не активна". PHP_EOL;} else {$mail_content .= "Prom Export: активна (v ".yfympr_VER.")". PHP_EOL;}
		if (isset($_REQUEST['yfym_its_ok'])) {
			$mail_content .= PHP_EOL ."Помог ли плагин: ".sanitize_text_field($_REQUEST['yfym_its_ok']);
		}
		if (isset($_POST['yfym_email'])) {
			$mail_content .= PHP_EOL ."Почта: ".sanitize_text_field($_POST['yfym_email']);
		}
		if (isset($_POST['yfym_message'])) {
			$mail_content .= PHP_EOL ."Сообщение: ".sanitize_text_field($_POST['yfym_message']);
		}
		$argsp = array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );
		$products = new WP_Query($argsp);
		$vsegotovarov = $products->found_posts;
		$mail_content .= PHP_EOL ."Число товаров на выгрузку: ". $vsegotovarov;
		$allNumFeed = (int)yfym_ALLNUMFEED;
		for ($i = 1; $i<$allNumFeed+1; $i++) {
			$status_sborki = (int)yfym_optionGET('yfym_status_sborki', $numFeed);
			$yfym_file_url = urldecode(yfym_optionGET('yfym_file_url', $numFeed));
			$yfym_file_file = urldecode(yfym_optionGET('yfym_file_file', $numFeed));	  
			$yfym_whot_export = yfym_optionGET('yfym_whot_export', $numFeed);
			$yfym_skip_missing_products = yfym_optionGET('yfym_skip_missing_products', $numFeed);	
			$yfym_skip_backorders_products = yfym_optionGET('yfym_skip_backorders_products', $numFeed);
			$yfym_status_cron = yfym_optionGET('yfym_status_cron', $numFeed);
			$yfym_ufup = yfym_optionGET('yfym_ufup', $numFeed);	
			$yfym_date_sborki = yfym_optionGET('yfym_date_sborki', $numFeed);
			$yfym_main_product = yfym_optionGET('yfym_main_product', $numFeed);
			$yfym_errors = yfym_optionGET('yfym_errors', $numFeed);

			$mail_content .= PHP_EOL."ФИД №: ".$i. PHP_EOL . PHP_EOL;
			$mail_content .= "status_sborki: ".$status_sborki. PHP_EOL;
			$mail_content .= "УРЛ: ".get_site_url(). PHP_EOL;
			$mail_content .= "УРЛ YML-фида: ".$yfym_file_url . PHP_EOL;
			$mail_content .= "Временный файл: ".$yfym_file_file. PHP_EOL;
			$mail_content .= "Что экспортировать: ".$yfym_whot_export. PHP_EOL;
			$mail_content .= "Исключать товары которых нет в наличии (кроме предзаказа): ".$yfym_skip_missing_products. PHP_EOL;
			$mail_content .= "Исключать из фида товары для предзаказа: ".$yfym_skip_backorders_products. PHP_EOL;
			$mail_content .= "Автоматическое создание файла: ".$yfym_status_cron. PHP_EOL;
			$mail_content .= "Обновить фид при обновлении карточки товара: ".$yfym_ufup. PHP_EOL;
			$mail_content .= "Дата последней сборки XML: ".$yfym_date_sborki. PHP_EOL;
			$mail_content .= "Что продаёт: ".$yfym_main_product. PHP_EOL;
			$mail_content .= "Ошибки: ".$yfym_errors. PHP_EOL;
			$numFeed++;
		}
		wp_mail('pt070@yandex.ru', 'Отчёт YML for WP', $mail_content);
		print '<div class="updated notice notice-success is-dismissible"><p>'. __('The data has been sent. Thank you', 'yfym'). '.</p></div>';
		}
	} /* end отправка отчёта */   
 }
 
 // сборка
 public static function yfym_construct_yml($numFeed = '1') {
	yfym_error_log('FEED № '.$numFeed.'; Стартовала yfym_construct_yml. Файл: yml-for-yandex-market.php; Строка: '.__LINE__ , 0);

 	$result_yml = '';
	$status_sborki = (int)yfym_optionGET('yfym_status_sborki', $numFeed);
  
	// файл уже собран. На всякий случай отключим крон сборки
	if ($status_sborki == -1 ) {wp_clear_scheduled_hook('yfym_cron_sborki', array($numFeed)); return;}
		  
	$yfym_date_save_set = yfym_optionGET('yfym_date_save_set', $numFeed);
	if ($yfym_date_save_set == '') {
		$unixtime = current_time('timestamp', 1); // 1335808087 - временная зона GMT(Unix формат)
		yfym_optionUPD('yfym_date_save_set', $unixtime, $numFeed);
	}
	$yfym_date_sborki = yfym_optionGET('yfym_date_sborki', $numFeed);
	  
	if ($numFeed === '1') {$prefFeed = '';} else {$prefFeed = $numFeed;}	  
	if (is_multisite()) {
		/*
		* wp_get_upload_dir();
		* 'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
		* 'url'     => 'http://site.ru/wp-content/uploads/2016/04',
		* 'subdir'  => '/2016/04',
		* 'basedir' => '/home/site.ru/public_html/wp-content/uploads',
		* 'baseurl' => 'http://site.ru/wp-content/uploads',
		* 'error'   => false,
		*/
		$upload_dir = (object)wp_get_upload_dir();
		$filenamefeed = $upload_dir->basedir."/".$prefFeed."feed-yml-".get_current_blog_id().".xml";		
	} else {
		$upload_dir = (object)wp_get_upload_dir();
		$filenamefeed = $upload_dir->basedir."/".$prefFeed."feed-yml-0.xml";
	}
	if (file_exists($filenamefeed)) {		
		yfym_error_log('FEED № '.$numFeed.'; Файл с фидом '.$filenamefeed.' есть. Файл: yml-for-yandex-market.php; Строка: '.__LINE__ , 0);
		// return; // файла с фидом нет
		clearstatcache(); // очищаем кэш дат файлов
		$last_upd_file = filemtime($filenamefeed);
		yfym_error_log('FEED № '.$numFeed.'; $yfym_date_save_set='.$yfym_date_save_set.'; $filenamefeed='.$filenamefeed, 0);
		yfym_error_log('FEED № '.$numFeed.'; Начинаем сравнивать даты! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);	
		if ($yfym_date_save_set < $last_upd_file) {
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Нужно лишь обновить цены во всём фиде! Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			yfym_clear_file_ids_in_yml($numFeed); /* С версии 3.1.0 */
			yfym_onlygluing($numFeed);
			return;
		}	
	}
	// далее исходим из того, что файла с фидом нет, либо нужна полная сборка
	  
	$step_export = (int)yfym_optionGET('yfym_step_export', $numFeed);
	if ($step_export == 0) {$step_export = 500;}
	  
	if ($status_sborki == $step_export) { // начинаем сборку файла
		do_action('yfym_before_construct', 'full'); // сборка стартовала
		$result_yml = yfym_feed_header($numFeed);
		/* создаем файл или перезаписываем старый удалив содержимое */
		$result = yfym_write_file($result_yml, 'w+', $numFeed);
		if ($result !== true) {
			yfym_error_log('FEED № '.$numFeed.'; yfym_write_file вернула ошибку! $result ='.$result.'; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			return; 
		} else {
			yfym_error_log('FEED № '.$numFeed.'; yfym_write_file отработала успешно; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
		}
		yfym_clear_file_ids_in_yml($numFeed); /* С версии 3.1.0 */	
	} 
	if ($status_sborki > 1) {
		$result_yml	= '';
		$offset = $status_sborki-$step_export;
		$whot_export = yfym_optionGET('yfym_whot_export', $numFeed);
		if ($whot_export === 'vygruzhat') {
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $step_export,
				'offset' => $offset,
				'relation' => 'AND',
				'meta_query' => array(
					array(
						'key' => 'vygruzhat',
						'value' => 'on'
					)
				)
			);			
		} else { // if ($whot_export == 'all' || $whot_export == 'simple')
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $step_export, // сколько выводить товаров
				'offset' => $offset,
				'relation' => 'AND'
			);
		}
/*
		$yfym_stopusepro = yfym_optionGET('yfym_stopusepro', $numFeed);
		if ($yfym_stopusepro === 'on') {}
*/
		$args = apply_filters('yfym_query_arg_filter', $args, $numFeed);
		$featured_query = new WP_Query($args);
		$prod_id_arr = array(); 
		if ($featured_query->have_posts()) { 		
		 	for ($i = 0; $i < count($featured_query->posts); $i++) {
				// $prod_id_arr[] .= $featured_query->posts[$i]->ID;
				$prod_id_arr[$i]['ID'] = $featured_query->posts[$i]->ID;
				$prod_id_arr[$i]['post_modified_gmt'] =$featured_query->posts[$i]->post_modified_gmt;
		 	}
			wp_reset_query(); /* Remember to reset */
			unset($featured_query); // чутка освободим память
			yfym_gluing($prod_id_arr, $numFeed);
			$status_sborki = $status_sborki + $step_export;
			yfym_error_log('FEED № '.$numFeed.'; status_sborki увеличен на '.$step_export.' и равен '.$status_sborki.'; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);	  
			yfym_optionUPD('yfym_status_sborki', $status_sborki, $numFeed);		   
		} else {
			// если постов нет, пишем концовку файла
			yfym_error_log('FEED № '.$numFeed.'; Постов больше нет, пишем концовку файла; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);	
			$result_yml .= "</offers>". PHP_EOL; 
			$result_yml = apply_filters('yfym_after_offers_filter', $result_yml, $numFeed);
			$result_yml .= "</shop>". PHP_EOL ."</yml_catalog>";
			/* создаем файл или перезаписываем старый удалив содержимое */
			$result = yfym_write_file($result_yml, 'a', $numFeed);
			yfym_error_log('FEED № '.$numFeed.'; Файл фида готов. Осталось только переименовать временный файл в основной; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			yfym_rename_file($numFeed);
			// выставляем статус сборки в "готово"
			$status_sborki = -1;
			if ($result === true) {
				yfym_optionUPD('yfym_status_sborki', $status_sborki, $numFeed);
				// останавливаем крон сборки
				wp_clear_scheduled_hook('yfym_cron_sborki', array($numFeed));
				do_action('yfym_after_construct', 'full'); // сборка закончена
				yfym_error_log('FEED № '.$numFeed.'; SUCCESS: Сборка успешно завершена; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
			} else {
				yfym_error_log('FEED № '.$numFeed.'; ERROR: На завершающем этапе yfym_write_file вернула ошибку! Я не смог записать концовку файла... $result ='.$result.'; Файл: yml-for-yandex-market.php; Строка: '.__LINE__, 0);
				do_action('yfym_after_construct', 'false'); // сборка закончена
				return;
			}
		} // end if ($featured_query->have_posts())
	  } // end if ($status_sborki > 1)
   } // end public static function yfym_construct_yml
} /* end class YmlforYandexMarket */
?>