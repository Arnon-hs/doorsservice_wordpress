<?php if (!defined('ABSPATH')) {exit;} // Защита от прямого вызова скрипта
function yfym_export_page() { 
 $numFeed = '1'; // (string)
 if (isset($_REQUEST['yfym_submit_send_select_feed'])) {
  if (!empty($_POST) && check_admin_referer('yfym_nonce_action_send_select_feed', 'yfym_nonce_field_send_select_feed')) {
	$numFeed = $_POST['yfym_num_feed'];
  } 
 }

 $status_sborki = (int)yfym_optionGET('yfym_status_sborki', $numFeed);

 if (isset($_REQUEST['yfym_submit_action'])) {
  if (!empty($_POST) && check_admin_referer('yfym_nonce_action','yfym_nonce_field')) {
	do_action('yfym_prepend_submit_action', $numFeed);  
	
	$numFeed = sanitize_text_field($_POST['yfym_num_feed_for_save']);
	if (is_multisite()) {
		update_blog_option(get_current_blog_id(), 'yfym_version', '3.1.1');
	} else {
		update_option('yfym_version', '3.1.1');
	}
	
	$unixtime = current_time('timestamp', 1); // 1335808087 - временная зона GMT (Unix формат)
	yfym_optionUPD('yfym_date_save_set', $unixtime, $numFeed);

	if (isset($_POST['yfym_skip_missing_products'])) {
		yfym_optionUPD('yfym_skip_missing_products', sanitize_text_field($_POST['yfym_skip_missing_products']), $numFeed);
	} else {
		yfym_optionUPD('yfym_skip_missing_products', '0', $numFeed);
	}
	if (isset($_POST['yfym_skip_backorders_products'])) {
		yfym_optionUPD('yfym_skip_backorders_products', sanitize_text_field($_POST['yfym_skip_backorders_products']), $numFeed);
	} else {
		yfym_optionUPD('yfym_skip_backorders_products', '0', $numFeed);
	}
	if (isset($_POST['yfym_no_default_png_products'])) {
		yfym_optionUPD('yfym_no_default_png_products', sanitize_text_field($_POST['yfym_no_default_png_products']), $numFeed);
	} else {
		yfym_optionUPD('yfym_no_default_png_products', '0', $numFeed);
	}
	if (isset($_POST['yfym_skip_products_without_pic'])) {
		yfym_optionUPD('yfym_skip_products_without_pic', sanitize_text_field($_POST['yfym_skip_products_without_pic']), $numFeed);
	} else {
		yfym_optionUPD('yfym_skip_products_without_pic', '0', $numFeed);
	}
	if (isset($_POST['yfym_ufup'])) {
		yfym_optionUPD('yfym_ufup', sanitize_text_field($_POST['yfym_ufup']), $numFeed);
	} else {
		yfym_optionUPD('yfym_ufup', '0', $numFeed);
	} 
	yfym_optionUPD('yfym_desc', sanitize_text_field($_POST['yfym_desc']), $numFeed);
	yfym_optionUPD('yfym_barcode', sanitize_text_field($_POST['yfym_barcode']), $numFeed);
	yfym_optionUPD('yfym_vendorcode', sanitize_text_field($_POST['yfym_vendorcode']), $numFeed);

	if (isset($_POST['yfym_enable_auto_discounts'])) {
		yfym_optionUPD('yfym_enable_auto_discounts', sanitize_text_field($_POST['yfym_enable_auto_discounts']), $numFeed);
		} else {
		yfym_optionUPD('yfym_enable_auto_discounts', '0', $numFeed);
	}
	yfym_optionUPD('yfym_expiry', sanitize_text_field($_POST['yfym_expiry']), $numFeed);
	yfym_optionUPD('yfym_downloadable', sanitize_text_field($_POST['yfym_downloadable']), $numFeed);
	yfym_optionUPD('yfym_age', sanitize_text_field($_POST['yfym_age']), $numFeed);
	yfym_optionUPD('yfym_country_of_origin', sanitize_text_field($_POST['yfym_country_of_origin']), $numFeed);
	yfym_optionUPD('yfym_manufacturer_warranty', sanitize_text_field($_POST['yfym_manufacturer_warranty']), $numFeed);
  
	yfym_optionUPD('yfym_whot_export', sanitize_text_field($_POST['yfym_whot_export']), $numFeed);
	yfym_optionUPD('yfym_pickup', sanitize_text_field($_POST['yfym_pickup']), $numFeed);
	
	yfym_optionUPD('yfym_sales_notes_cat', sanitize_text_field($_POST['yfym_sales_notes_cat']), $numFeed);
	yfym_optionUPD('yfym_sales_notes', sanitize_text_field($_POST['yfym_sales_notes']), $numFeed);
	yfym_optionUPD('yfym_delivery', sanitize_text_field($_POST['yfym_delivery']), $numFeed);
	yfym_optionUPD('yfym_store', sanitize_text_field($_POST['yfym_store']), $numFeed);

	yfym_optionUPD('yfym_order_before', sanitize_text_field($_POST['yfym_order_before']), $numFeed);
	
	$yfym_delivery_cost = (int)sanitize_text_field($_POST['yfym_delivery_cost']);
	if ($yfym_delivery_cost >= 0 ) {
		yfym_optionUPD('yfym_delivery_cost', $yfym_delivery_cost, $numFeed); 
	}
	
	yfym_optionUPD('yfym_delivery_days', sanitize_text_field($_POST['yfym_delivery_days']), $numFeed);
	if (isset($_POST['yfym_delivery_options'])) {
		yfym_optionUPD('yfym_delivery_options', sanitize_text_field($_POST['yfym_delivery_options']), $numFeed);
	} else {
		yfym_optionUPD('yfym_delivery_options', '0', $numFeed);
	}
	
	yfym_optionUPD('yfym_shop_name', $_POST['yfym_shop_name'], $numFeed);
	yfym_optionUPD('yfym_company_name', $_POST['yfym_company_name'], $numFeed);
	yfym_optionUPD('yfym_main_product', sanitize_text_field($_POST['yfym_main_product']), $numFeed);
	yfym_optionUPD('yfym_adult', sanitize_text_field($_POST['yfym_adult']), $numFeed);
	yfym_optionUPD('yfym_vendor', sanitize_text_field($_POST['yfym_vendor']), $numFeed);
	yfym_optionUPD('yfym_model', sanitize_text_field($_POST['yfym_model']), $numFeed);
	yfym_optionUPD('yfym_separator_type', sanitize_text_field($_POST['yfym_separator_type']), $numFeed);

	if (isset($_POST['yfym_params_arr'])) {
		yfym_optionUPD('yfym_params_arr', serialize($_POST['yfym_params_arr']), $numFeed);
	} else {yfym_optionUPD('yfym_params_arr', '', $numFeed);}
	if (isset($_POST['yfym_add_in_name_arr'])) {
		yfym_optionUPD('yfym_add_in_name_arr', serialize($_POST['yfym_add_in_name_arr']), $numFeed);
		} else {yfym_optionUPD('yfym_add_in_name_arr', '', $numFeed);}
	if (isset($_POST['yfym_no_group_id_arr'])) {
		yfym_optionUPD('yfym_no_group_id_arr', serialize($_POST['yfym_no_group_id_arr']), $numFeed);
	} else {yfym_optionUPD('yfym_no_group_id_arr', '', $numFeed);}
	yfym_optionUPD('yfym_price_from', sanitize_text_field($_POST['yfym_price_from']), $numFeed);
	yfym_optionUPD('yfym_oldprice', sanitize_text_field($_POST['yfym_oldprice']), $numFeed);
	yfym_optionUPD('yfym_step_export', sanitize_text_field($_POST['yfym_step_export']), $numFeed);
	
	$arr_maybe = array("off", "five_min", "hourly", "six_hours", "twicedaily", "daily");
	$yfym_run_cron = sanitize_text_field($_POST['yfym_run_cron']);
	if (in_array($yfym_run_cron, $arr_maybe)) {		
		yfym_optionUPD('yfym_status_cron', $yfym_run_cron, $numFeed);
		if ($yfym_run_cron === 'off') {
			// отключаем крон
			wp_clear_scheduled_hook('yfym_cron_period', array($numFeed));
			yfym_optionUPD('yfym_status_cron', 'off', $numFeed);
			
			wp_clear_scheduled_hook('yfym_cron_sborki', array($numFeed));
			yfym_optionUPD('yfym_status_sborki', '-1', $numFeed);
		} else {
			$recurrence = $yfym_run_cron;
			wp_clear_scheduled_hook('yfym_cron_period', array($numFeed));
			wp_schedule_event(time(), $recurrence, 'yfym_cron_period', array($numFeed));
			yfym_error_log('FEED № '.$numFeed.'; yfym_cron_period внесен в список заданий; Файл: export.php; Строка: '.__LINE__, 0);
		}
	} else {
		yfym_error_log('Крон '.$yfym_run_cron.' не зарегистрирован. Файл: export.php; Строка: '.__LINE__, 0);
	}
  }
 } 

 $yfym_status_cron = yfym_optionGET('yfym_status_cron', $numFeed);
 $yfym_whot_export = yfym_optionGET('yfym_whot_export', $numFeed); 
 $yfym_desc = yfym_optionGET('yfym_desc', $numFeed);
 $yfym_shop_name = stripslashes(htmlspecialchars(yfym_optionGET('yfym_shop_name', $numFeed)));
 $yfym_company_name = stripslashes(htmlspecialchars(yfym_optionGET('yfym_company_name', $numFeed)));
 $yfym_main_product = yfym_optionGET('yfym_main_product', $numFeed);  
 $yfym_adult = yfym_optionGET('yfym_adult', $numFeed); 	
 $yfym_step_export = yfym_optionGET('yfym_step_export', $numFeed); 
 $yfym_no_group_id_arr = unserialize(yfym_optionGET('yfym_no_group_id_arr', $numFeed)); 
 $yfym_skip_missing_products = yfym_optionGET('yfym_skip_missing_products', $numFeed);
 $yfym_skip_backorders_products = yfym_optionGET('yfym_skip_backorders_products', $numFeed); 
 $yfym_no_default_png_products = yfym_optionGET('yfym_no_default_png_products', $numFeed);
 $yfym_skip_products_without_pic = yfym_optionGET('yfym_skip_products_without_pic', $numFeed); 
 $yfym_ufup = yfym_optionGET('yfym_ufup', $numFeed);
 $yfym_delivery = yfym_optionGET('yfym_delivery', $numFeed); 
 $yfym_delivery_options = yfym_optionGET('yfym_delivery_options', $numFeed);
 $yfym_delivery_cost = yfym_optionGET('yfym_delivery_cost', $numFeed); 
 $yfym_delivery_days = yfym_optionGET('yfym_delivery_days', $numFeed); 
 $yfym_order_before = yfym_optionGET('yfym_order_before', $numFeed);
 $yfym_pickup = yfym_optionGET('yfym_pickup', $numFeed); 
 $yfym_price_from = yfym_optionGET('yfym_price_from', $numFeed); 
 $yfym_oldprice = yfym_optionGET('yfym_oldprice', $numFeed); 
 $yfym_barcode = yfym_optionGET('yfym_barcode', $numFeed);
 $yfym_vendorcode = yfym_optionGET('yfym_vendorcode', $numFeed);

 $yfym_enable_auto_discounts = yfym_optionGET('yfym_enable_auto_discounts', $numFeed); 
 $expiry = yfym_optionGET('yfym_expiry', $numFeed); 
 $downloadable = yfym_optionGET('yfym_downloadable', $numFeed);
 $age = yfym_optionGET('yfym_age', $numFeed);
 $model = yfym_optionGET('yfym_model', $numFeed); 
 $yfym_separator_type = yfym_optionGET('yfym_separator_type', $numFeed); 
 $vendor = yfym_optionGET('yfym_vendor', $numFeed); 
 $country_of_origin = yfym_optionGET('yfym_country_of_origin', $numFeed); 
 $manufacturer_warranty = yfym_optionGET('yfym_manufacturer_warranty', $numFeed); 
 $sales_notes_cat = yfym_optionGET('yfym_sales_notes_cat', $numFeed);
 $sales_notes = htmlspecialchars(yfym_optionGET('yfym_sales_notes', $numFeed));
 $params_arr = unserialize(yfym_optionGET('yfym_params_arr', $numFeed));
 $add_in_name_arr = unserialize(yfym_optionGET('yfym_add_in_name_arr', $numFeed)); 
 $yfym_store = yfym_optionGET('yfym_store', $numFeed);
 $yfym_file_url = urldecode(yfym_optionGET('yfym_file_url', $numFeed));
 $yfym_date_sborki = yfym_optionGET('yfym_date_sborki', $numFeed);
?>
 <div class="wrap">
  <h1><?php _e('Exporter Yandex Market', 'yfym'); ?></h1>
 	<?php $woo_version = yfym_get_woo_version_number();
	if ($woo_version <= 3.0 ) {
		print '<div class="notice notice-error is-dismissible"><p>'. __('For the plugin to function correctly, you need a version of WooCommerce 3.0 and higher! You have version ', 'yfym'). $woo_version . __(' installed. Please, update WooCommerce', 'yfym'). '! <a href="https://icopydoc.ru/minimalnye-trebovaniya-dlya-raboty-yml-for-yandex-market/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings">'. __('Learn More', 'yfym'). '</a>.</p></div>';
	}
	if (defined('DISABLE_WP_CRON')) {
	 if (DISABLE_WP_CRON == true) {
		print '<div class="notice notice-error is-dismissible"><p>'. __('Most likely, the plugin does not work correctly because you turned off the CRON with the help of the ', 'yfym'). 'DISABLE_WP_CRON.</p></div>';
	 }
	}
	
    $check_global_attr_count = wc_get_attribute_taxonomies();
    if (count($check_global_attr_count) < 1) {
		print '<div class="notice notice-warning is-dismissible"><p>'. __('Your site has no global attributes! This may affect the quality of the YML feed. This can also cause difficulties when setting up the plugin', 'yfym'). '. <a href="https://icopydoc.ru/globalnyj-i-lokalnyj-atributy-v-woocommerce/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings">'. __('Please read the recommendations', 'yfym'). '</a>.</p></div>';
		yfym_error_log('WARNING: Cайт не имеет глобальных атрибутов! Это может повлиять на качество YML-фида; Подробности см. тут: https://icopydoc.ru/globalnyj-i-lokalnyj-atributy-v-woocommerce/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=logs Файл: export.php; Строка: '.__LINE__, 0);
	}
	unset($check_global_attr_count);
	
	if (!class_exists('YmlforYandexMarketPromosExport')) {
		print '<div class="notice notice-info is-dismissible"><p><span style="font-weight: 700;">Promos Export</span> '. __('is an extension that allows you to increase sales by unloading Promotions (promos, gifts) on the Yandex Market', 'yfym'). '. <a href="'.ADMIN_COOKIE_PATH.'/admin.php?page=yfymextensions">'. __('Learn More', 'yfym'). '</a>.</p></div>';
	} else {
		print '<div class="notice notice-info is-dismissible"><p>'. __('Thank you for purchasing the Promos Export extension', 'yfym'). '! <a href="https://icopydoc.ru/kejs-dobavlyaem-informatsiyu-o-podarkah-na-yandeks-market/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings">'. __('Read usage case', 'yfym'). '</a>.</p></div>';
	}
	if (!class_exists('YmlforYandexMarketPro')) {
		print '<div class="notice notice-warning is-dismissible"><p>'. __('Go to', 'yfym'). ' <span style="font-weight: 700;">Yml for Yandex Market Pro</span> '. __('and', 'yfym'). ' <span style="color: red;">'. __('keep the advertising budget', 'yfym'). '</span> '. __('on Yandex', 'yfym'). '! <a href="'.ADMIN_COOKIE_PATH.'/admin.php?page=yfymextensions">'. __('Learn More', 'yfym'). '</a>.</p></div>';
	} else {
		print '<div class="notice notice-success is-dismissible"><p>'. __('Thank you for purchasing the Yml for Yandex Market Pro extension', 'yfym'). '! <a href="https://icopydoc.ru/para-sovetov-po-ispolzovaniyu-yml-for-yandex-market-pro/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings">'. __('Read usage tips', 'yfym'). '</a>.</p></div>';		
	} 
	if (!class_exists('YmlforYandexMarketProm')) {
		print '<div class="notice notice-warning is-dismissible"><p><span style="font-weight: 700;">Prom Export</span> - '. __('extension for exporting products to', 'yfym'). ' <a href="//prom.ua" target="_blank">prom.ua</a>. <a href="'.ADMIN_COOKIE_PATH.'/admin.php?page=yfymextensions">'. __('Learn More', 'yfym'). '</a>.</p></div>';
	} else {
		print '<div class="notice notice-success is-dismissible"><p>'. __('Thank you for purchasing the Yml for Yandex Market Prom Export extension', 'yfym'). '!</p></div>';		
	} 
	 ?>  
  <div style="margin: 0 auto; max-width: 1332px" class="clear">
  <div class="icp_wrap">
	<input type="radio" name="icp_slides" id="icp_point1">
	<input type="radio" name="icp_slides" id="icp_point2">
	<input type="radio" name="icp_slides" id="icp_point3" checked>
	<div class="icp_slider">
		<div class="icp_slides icp_img1"><a href="//wordpress.org/plugins/yml-for-yandex-market/" target="_blank"></a></div>
		<div class="icp_slides icp_img2"><a href="//wordpress.org/plugins/import-products-to-ok-ru/" target="_blank"></a></div>
		<div class="icp_slides icp_img3"><a href="//wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank"></a></div>
	</div>
	<div class="icp_control">
		<label for="icp_point1"></label>
		<label for="icp_point2"></label>
		<label for="icp_point3"></label>
	</div>
  </div> 
  </div>
  <div id="dashboard-widgets-wrap"><div id="dashboard-widgets" class="metabox-holder">
  <?php if ($woo_version >= 3.0 ) : ?>
  	<div style="margin: 0 auto; max-width: 320px;" class="clear">
	  <p style="text-align: center;"><strong style="color: green;"><?php _e('Instruction', 'yfym'); ?>:</strong> <a href="https://icopydoc.ru/kak-sozdat-woocommerce-yml-instruktsiya/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings" target="_blank"><?php _e('How to create a YML-feed', 'yfym'); ?></a>.</p>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">	
	  <select style="width: 100%" name="yfym_num_feed" id="yfym_num_feed">
		<?php if (is_multisite()) {$cur_blog_id = get_current_blog_id();} else {$cur_blog_id = '0';}		
		$allNumFeed = (int)yfym_ALLNUMFEED;
		for ($i = 1; $i<$allNumFeed+1; $i++) : ?>
		<option value="<?php echo $i; ?>" <?php selected($numFeed, $i); ?>><?php _e('Feed', 'yfym'); ?> <?php echo $i; ?>: feed-yml-<?php echo $cur_blog_id; ?>.xml</option>
  		<?php endfor; ?>
  		</select>
	  <?php wp_nonce_field('yfym_nonce_action_send_select_feed', 'yfym_nonce_field_send_select_feed'); ?>
	  <input style="width: 100%; margin: 10px 0 10px 0;" class="button" type="submit" name="yfym_submit_send_select_feed" value="<?php _e('Select feed', 'yfym'); ?>" />
   	 </form>
  	</div>

	<div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables" >
     <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="yfym_num_feed_for_save" value="<?php echo $numFeed; ?>">
	 <div class="postbox">
	   <div class="inside">
	    <h1><?php _e('Main parameters', 'yfym'); ?></h1>
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="yfym_run_cron"><?php _e('Automatic file creation', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_run_cron" id="yfym_run_cron">
					<option value="off" <?php selected($yfym_status_cron, 'off' ); ?>><?php _e('Off', 'yfym'); ?></option>
					<?php $yfym_enable_five_min = yfym_optionGET('yfym_enable_five_min'); if ($yfym_enable_five_min === 'on') : ?>
					<option value="five_min" <?php selected($yfym_status_cron, 'five_min' );?> ><?php _e('Every five minutes', 'yfym'); ?></option>
					<?php endif; ?>
					<option value="hourly" <?php selected($yfym_status_cron, 'hourly' );?> ><?php _e('Hourly', 'yfym'); ?></option>
					<option value="six_hours" <?php selected($yfym_status_cron, 'six_hours' ); ?> ><?php _e('Every six hours', 'yfym'); ?></option>	
					<option value="twicedaily" <?php selected($yfym_status_cron, 'twicedaily' );?> ><?php _e('Twice a day', 'yfym'); ?></option>
					<option value="daily" <?php selected($yfym_status_cron, 'daily' );?> ><?php _e('Daily', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('The refresh interval on your feed', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_ufup"><?php _e('Update feed when updating products', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_ufup" id="yfym_ufup" <?php checked($yfym_ufup, 'on' ); ?>/>
			</td>
		 </tr>
		 <?php do_action('yfym_after_ufup_option', $numFeed); /* С версии 3.0.0 */ ?>
		 <tr>
			<th scope="row"><label for="yfym_whot_export"><?php _e('Whot export', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_whot_export" id="yfym_whot_export">
					<option value="all" <?php selected($yfym_whot_export, 'all'); ?>><?php _e('Simple & Variable products', 'yfym'); ?></option>
					<option value="simple" <?php selected($yfym_whot_export, 'simple'); ?>><?php _e('Only simple products', 'yfym'); ?></option>
					<?php do_action('yfym_after_whot_export_option', $numFeed); ?>
				</select><br />
				<span class="description"><?php _e('Whot export', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_desc"><?php _e('Description of the product', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_desc" id="yfym_desc">
				<option value="excerpt" <?php selected($yfym_desc, 'excerpt'); ?>><?php _e('Only Excerpt description', 'yfym'); ?></option>
				<option value="full" <?php selected($yfym_desc, 'full'); ?>><?php _e('Only Full description', 'yfym'); ?></option>
				<option value="excerptfull" <?php selected($yfym_desc, 'excerptfull'); ?>><?php _e('Excerpt or Full description', 'yfym'); ?></option>
				<option value="fullexcerpt" <?php selected($yfym_desc, 'fullexcerpt'); ?>><?php _e('Full or Excerpt description', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('The source of the description', 'yfym'); ?>
				</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_shop_name"><?php _e('Shop name', 'yfym'); ?></label></th>
			<td class="overalldesc">
			 <input maxlength="20" type="text" name="yfym_shop_name" id="yfym_shop_name" value="<?php echo $yfym_shop_name; ?>" /><br />
			 <span class="description"><?php _e('Required element', 'yfym'); ?> <strong>name</strong>. <?php _e('The short name of the store should not exceed 20 characters', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_company_name"><?php _e('Company name', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="text" name="yfym_company_name" id="yfym_company_name" value="<?php echo $yfym_company_name; ?>" /><br />
				<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>company</strong>. <?php _e('Full name of the company that owns the store', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_main_product"><?php _e('What kind of products do you sell?', 'yfym'); ?></label></th>
			<td class="overalldesc">
					<select name="yfym_main_product" id="yfym_main_product">
					<option value="electronics" <?php selected($yfym_main_product, 'electronics'); ?>><?php _e('Electronics', 'yfym'); ?></option>
					<option value="clothes_and_shoes" <?php selected($yfym_main_product, 'clothes_and_shoes'); ?>><?php _e('Clothes and shoes', 'yfym'); ?></option>
					<option value="auto_parts" <?php selected($yfym_main_product, 'auto_parts'); ?>><?php _e('Auto parts', 'yfym'); ?></option>
					<option value="products_for_children" <?php selected($yfym_main_product, 'products_for_children'); ?>><?php _e('Products for children', 'yfym'); ?></option>
					<option value="sporting_goods" <?php selected($yfym_main_product, 'sporting_goods'); ?>><?php _e('Sporting goods', 'yfym'); ?></option>
					<option value="goods_for_pets" <?php selected($yfym_main_product, 'goods_for_pets'); ?>><?php _e('Goods for pets', 'yfym'); ?></option>
					<option value="sexshop" <?php selected($yfym_main_product, 'sexshop'); ?>><?php _e('Sex shop (Adult products)', 'yfym'); ?></option>
					<option value="books" <?php selected($yfym_main_product, 'books'); ?>><?php _e('Books', 'yfym'); ?></option>
					<option value="construction_materials" <?php selected($yfym_main_product, 'construction_materials'); ?>><?php _e('Construction Materials', 'yfym'); ?></option>
					<option value="other" <?php selected($yfym_main_product, 'other'); ?>><?php _e('Other', 'yfym'); ?></option>					
				</select><br />
				<span class="description"><?php _e('Specify the main category', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_adult"><?php _e('Adult Market', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_adult" id="yfym_adult">
				<option value="no" <?php selected($yfym_adult, 'no'); ?>><?php _e('No', 'yfym'); ?></option>
				<option value="yes" <?php selected($yfym_adult, 'yes'); ?>><?php _e('Yes', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>adult</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_step_export"><?php _e('Step of export', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_step_export" id="yfym_step_export">
				<option value="80" <?php selected($yfym_step_export, '80'); ?>>80</option>
				<option value="200" <?php selected($yfym_step_export, '200'); ?>>200</option>
				<option value="300" <?php selected($yfym_step_export, '300'); ?>>300</option>
				<option value="450" <?php selected($yfym_step_export, '450'); ?>>450</option>
				<option value="500" <?php selected($yfym_step_export, '500'); ?>>500</option>
				<option value="800" <?php selected($yfym_step_export, '800'); ?>>800</option>
				<option value="1000" <?php selected($yfym_step_export, '1000'); ?>>1000</option>
				<?php do_action('yfym_step_export_option', $numFeed); ?>
				</select><br />
				<span class="description"><?php _e('The value affects the speed of file creation', 'yfym'); ?>. <?php _e('If you have any problems with the generation of the file - try to reduce the value in this field', 'yfym'); ?>. <?php _e('More than 500 can only be installed on powerful servers', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_no_group_id_arr"><?php _e('Categories of variable products for which group_id is not allowed', 'yfym'); ?></label></th>
			<td class="overalldesc">
			 <select id="yfym_no_group_id_arr" style="width: 100%;" name="yfym_no_group_id_arr[]" size="8" multiple>
		  <?php 
			 foreach (get_terms('product_cat', array('hide_empty'=>0, 'parent'=>0)) as $term) {
				 echo yfym_cat_tree($term->taxonomy, $term->term_id, $yfym_no_group_id_arr);		 
			 } ?>
			 </select><br />
			 <span class="description"><?php _e('According to Yandex Market rules in this field you need to mark ALL categories of products not related to "Clothes, Shoes and Accessories", "Furniture", "Cosmetics, perfumes and care", "Baby products", "Accessories for portable electronics". Ie categories for which it is forbidden to use the attribute group_id', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_add_in_name_arr"><?php _e('Add the following attributes to the name', 'yfym'); ?></label></th>
			<td class="overalldesc">
			 <select id="yfym_add_in_name_arr" style="width: 100%;" name="yfym_add_in_name_arr[]" size="8" multiple>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
					<option value="<?php echo $attribute['id']; ?>"<?php if (!empty($add_in_name_arr)) { foreach ($add_in_name_arr as $value) {selected($value, $attribute['id']);}} ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
			 </select><br />
			 <span class="description"><?php _e('It works only for variable products that are not in the category "Clothes, Shoes and Accessories", "Furniture", "Cosmetics, perfumes and care", "Baby products", "Accessories for portable electronics"', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_separator_type"><?php _e('Separator options', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_separator_type" id="yfym_separator_type">
					<option value="type1" <?php selected($yfym_separator_type, 'type1'); ?>><?php _e('Type', 'yfym'); ?>_1 (В1:З1, В2:З2, ... Вn:Зn)</option>
					<option value="type2" <?php selected($yfym_separator_type, 'type2')?> ><?php _e('Type', 'yfym'); ?>_2 (В1-З1, В2-З2, ... Вn:Зn)</option>
					<option value="type3" <?php selected($yfym_separator_type, 'type3'); ?> ><?php _e('Type', 'yfym'); ?>_3 В1:З1, В2:З2, ... Вn:Зn</option>
					<?php do_action('yfym_after_option_separator_type', $numFeed); ?>
				</select><br />
				<span class="description"><?php _e('Separator options', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_skip_missing_products"><?php _e('Skip missing products', 'yfym'); ?> (<?php _e('except for products for which a pre-order is permitted', 'yfym'); ?>.)</label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_skip_missing_products" id="yfym_skip_missing_products" <?php checked($yfym_skip_missing_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_skip_backorders_products"><?php _e('Skip backorders products', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_skip_backorders_products" id="yfym_skip_backorders_products" <?php checked($yfym_skip_backorders_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_no_default_png_products"><?php _e('Remove default.png from YML', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_no_default_png_products" id="yfym_no_default_png_products" <?php checked($yfym_no_default_png_products, 'on' ); ?>/>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_skip_products_without_pic"><?php _e('Skip products without pictures', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_skip_products_without_pic" id="yfym_skip_products_without_pic" <?php checked($yfym_skip_products_without_pic, 'on' ); ?>/>
			</td>
		 </tr>
		 <?php do_action('yfym_after_step_export', $numFeed); ?>
		</tbody></table>
	   </div>
	 </div>
	 <?php do_action('yfym_before_pad', $numFeed); ?>
	 <div class="postbox">
	   <div class="inside">
	    <h1><?php _e('Price and delivery', 'yfym'); ?></h1>
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="yfym_pickup"><?php _e('Pickup', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_pickup" id="yfym_pickup">
				<option value="true" <?php selected($yfym_pickup, 'true' ); ?>><?php _e( 'True', 'yfym'); ?></option>
				<option value="false" <?php selected($yfym_pickup, 'false' ); ?>><?php _e( 'False', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>pickup</strong>. <?php _e('Option to get order from pickup point', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <?php do_action('yfym_after_pickup', $numFeed); /* с версии 2.0.8 */ ?>
		 <tr>
			<th scope="row"><label for="yfym_price_from"><?php _e('Price from', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_price_from" id="yfym_price_from">
					<option value="yes" <?php selected( $yfym_price_from, 'yes' ); ?>><?php _e( 'Yes', 'yfym'); ?></option>
					<option value="no" <?php selected( $yfym_price_from, 'no' ); ?>><?php _e( 'No', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Apply the setting Price from', 'yfym'); ?> <strong>from="true"</strong> <?php _e('attribute of', 'yfym'); ?> <strong>price</strong><br /><strong><?php _e('Example', 'yfym'); ?>:</strong><br /><code>&lt;price from=&quot;true&quot;&gt;2000&lt;/price&gt;</code></span>
			</td>
		 </tr>	
		 <tr>
			<th scope="row"><label for="yfym_oldprice"><?php _e('Old price', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_oldprice" id="yfym_oldprice">
					<option value="yes" <?php selected( $yfym_oldprice, 'yes' ); ?>><?php _e( 'Yes', 'yfym'); ?></option>
					<option value="no" <?php selected( $yfym_oldprice, 'no' ); ?>><?php _e( 'No', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>oldprice</strong>. <?php _e('In oldprice indicates the old price of the goods, which must necessarily be higher than the new price (price)', 'yfym'); ?>.</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_delivery"><?php _e('Delivery', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_delivery" id="yfym_delivery">
					<option value="true" <?php selected( $yfym_delivery, 'true' ); ?>><?php _e( 'True', 'yfym'); ?></option>
					<option value="false" <?php selected( $yfym_delivery, 'false' ); ?>><?php _e( 'False', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>delivery</strong>. <?php _e('The delivery item must be set to false if the item is prohibited to sell remotely (jewelry, medicines)', 'yfym'); ?>.<br />
				<a target="_blank" href="//yandex.ru/support/partnermarket/delivery.html"><?php _e('Read more on Yandex', 'yfym'); ?></a>
				</span>
			</td>
		 </tr> 
		 <tr>
			<th scope="row"><label for="yfym_delivery_options"><?php _e('Use delivery-options', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_delivery_options" id="yfym_delivery_options" <?php checked($yfym_delivery_options, 'on' ); ?>/><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>delivery-options</strong> <a target="_blank" href="//yandex.ru/support/partnermarket/elements/delivery-options.html#structure"><?php _e('Read more on Yandex', 'yfym'); ?></a></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_delivery_cost"><?php _e('Delivery cost', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input min="0" type="number" name="yfym_delivery_cost" id="yfym_delivery_cost" value="<?php echo $yfym_delivery_cost; ?>" /><br />
				<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>cost</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_delivery_days"><?php _e('Delivery days', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="text" name="yfym_delivery_days" id="yfym_delivery_days" value="<?php echo $yfym_delivery_days; ?>" /><br />
				<span class="description"><?php _e('Required element', 'yfym'); ?> <strong>days</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_order_before"><?php _e('The time', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="text" name="yfym_order_before" id="yfym_order_before" value="<?php echo $yfym_order_before; ?>" /><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>order-before</strong> <?php _e('of attribute', 'yfym'); ?> <strong>delivery-option</strong>.<br /><?php _e('The time in which you need to place an order to get it at this time', 'yfym'); ?></span>
			</td>
		 </tr>
		</tbody></table>
	   </div>
	 </div>	 
	 <div class="postbox">
	   <div class="inside">
		<h1><?php _e('Optional element', 'yfym'); ?></h1>
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="yfym_enable_auto_discounts"><?php _e('Use Auto discounts', 'yfym'); ?></label></th>
			<td class="overalldesc">
			 <input type="checkbox" name="yfym_enable_auto_discounts" id="yfym_enable_auto_discounts" <?php checked($yfym_enable_auto_discounts, 'on' ); ?>/><br /><span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>enable_auto_discounts</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_barcode"><?php _e('Barcode', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_barcode" id="yfym_barcode">
				<option value="off" <?php selected($yfym_barcode, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
				<option value="sku" <?php selected($yfym_barcode, 'sku'); ?>><?php _e( 'Substitute from SKU', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $yfym_barcode, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option><?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>barcode</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_vendorcode"><?php _e('Vendor Code', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_vendorcode" id="yfym_vendorcode">
				<option value="off" <?php selected($yfym_vendorcode, 'off'); ?>><?php _e('None', 'yfym'); ?></option>
				<option value="sku" <?php selected($yfym_vendorcode, 'sku'); ?>><?php _e('Substitute from SKU', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected($yfym_vendorcode, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option><?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>vendorCode</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_expiry"><?php _e('Shelf life / service life', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_expiry" id="yfym_expiry">
				<option value="off" <?php selected($expiry, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $expiry, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>	<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>expiry</strong>.<br /><?php _e('Shelf life / service life. expiry date / service life', 'yfym'); ?>.</span><br /><a target="_blank" href="//yandex.ru/support/partnermarket/offers.html"><?php _e('Read more on Yandex', 'yfym'); ?></a>
			</td>
		 </tr> 

		 <tr>
			<th scope="row"><label for="yfym_downloadable"><?php _e('Mark downloadable products', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_downloadable" id="yfym_downloadable">
				<option value="off" <?php selected($downloadable, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
				<option value="on" <?php selected($downloadable, 'on'); ?>><?php _e( 'On', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>downloadable</strong></span>
			</td>
		 </tr>

		 <tr>
			 <th scope="row"><label for="yfym_age"><?php _e('Age', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_age" id="yfym_age">
				<option value="off" <?php selected($age, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $age, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>	<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>age</strong></span>
			</td>
		 </tr>
		 
		 <tr>
			<th scope="row"><label for="yfym_model"><?php _e('Model', 'yfym'); ?></label></th>
			<td class="overalldesc">
			 <select name="yfym_model" id="yfym_model">
				<option value="off" <?php selected($model, 'off' ); ?>><?php _e( 'None', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $model, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
			 </select><br />
			 <span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>model</strong><br />(<?php _e('Considered obsolete. Do not use the Yandex Market', 'yfym'); ?>.)</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_vendor"><?php _e('Vendor', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_vendor" id="yfym_vendor">
				<option value="off" <?php selected($vendor, 'off' ); ?>><?php _e('None', 'yfym'); ?></option>
				<?php if (class_exists('Perfect_Woocommerce_Brands')) : ?>
				<option value="sfpwb" <?php selected($vendor, 'sfpwb'); ?>><?php _e('Substitute from', 'yfym'); ?> Perfect Woocommerce Brands</option>
				<?php endif; ?>
				<?php if (is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) : ?>
				<option value="premmercebrandsplugin" <?php selected($vendor, 'premmercebrandsplugin'); ?>><?php _e('Substitute from', 'yfym'); ?> Premmerce Brands for WooCommerce</option>
				<?php endif; ?>				
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $vendor, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>vendor</strong></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_country_of_origin"><?php _e('Country of origin', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_country_of_origin" id="yfym_country_of_origin">
				<option value="off" <?php selected($country_of_origin, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>	
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $country_of_origin, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>country_of_origin</strong>. <?php _e('This element indicates the country where the product was manufactured', 'yfym'); ?>.<br /><a href="//partner.market.yandex.ru/pages/help/Countries.pdf" target="_blank"><?php _e('A list of possible values', 'yfym'); ?></a>.</span>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="yfym_manufacturer_warranty"><?php _e('Manufacturer warrant', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_manufacturer_warranty" id="yfym_manufacturer_warranty">	 
					<option value="off" <?php selected($manufacturer_warranty, 'off'); ?>><?php _e( 'None', 'yfym'); ?></option>
					<option value="alltrue" <?php selected($manufacturer_warranty, 'alltrue'); ?>><?php _e('Add to all', 'yfym'); ?> true</option>
					<option value="allfalse" <?php selected($manufacturer_warranty, 'allfalse'); ?>><?php _e('Add to all', 'yfym'); ?> false</option>
					<?php foreach (yfym_get_attributes() as $attribute) : ?>
					<option value="<?php echo $attribute['id']; ?>" <?php selected( $manufacturer_warranty, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
					<?php endforeach; ?>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>manufacturer_warranty</strong>. <?php _e("This element is used for products that have an official manufacturer's warranty", 'yfym'); ?>.</span><ul><li>false — <?php _e('Product does not have an official warranty', 'yfym'); ?></li><li>true — <?php _e('Product has an official warranty', 'yfym'); ?></li></ul>
			</td>
		</tr>
		<?php do_action('yfym_after_manufacturer_warranty', $numFeed); ?>
		 <tr>
			<th scope="row"><label for="yfym_sales_notes"><?php _e('Sales notes', 'yfym'); ?></label><br /><span style="color: red;"><?php _e('Attention!', 'yfym'); ?></span> <a target="_blank" href="https://icopydoc.ru/sales-notes/"><?php _e('Read more about how this works', 'yfym'); ?></a></th>
			<td class="overalldesc">
				<select name="yfym_sales_notes_cat" id="yfym_sales_notes">
				<option value="off" <?php selected($sales_notes_cat, 'off'); ?>><?php _e('Disable use of Sales notes', 'yfym'); ?></option>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
				<option value="<?php echo $attribute['id']; ?>" <?php selected( $sales_notes_cat, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
				</select>
				<p><?php _e('If the attribute from the select is absent from the product, then substitute', 'yfym'); ?>:</p><p>
				<textarea maxlength="50" style="width: 100%;" name="yfym_sales_notes" placeholder="<?php _e('Sales notes', 'yfym'); ?>" class="form-required"><?php echo $sales_notes; ?></textarea></p>
				<span class="description">
				<?php _e('Optional element', 'yfym'); ?> <strong>Sales notes</strong></span><br />
				<span class="description" style="color: red;"><?php _e('Attention!', 'yfym'); ?></span> <span class="description"><?php _e('The text may be up to 50 characters in length. Also in the item is forbidden to specify the terms of delivery and price reduction (discount on merchandise)', 'yfym'); ?>.<br /> 
				<a target="_blank" href="//yandex.ru/support/partnermarket/elements/sales_notes.html"><?php _e('Read more on Yandex', 'yfym'); ?></a></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_params_arr"><?php _e('Include these attributes in the values Param', 'yfym'); ?></label>
			</th>
			<td class="overalldesc">
			 <select id="yfym_params_arr" style="width: 100%;" name="yfym_params_arr[]" size="8" multiple>
				<?php foreach (yfym_get_attributes() as $attribute) : ?>
					<option value="<?php echo $attribute['id']; ?>"<?php if (!empty($params_arr)) { foreach ($params_arr as $value) {selected($value, $attribute['id']);}} ?>><?php echo $attribute['name']; ?></option>
				<?php endforeach; ?>
			 </select><br />
			 <span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>param</strong></span><br />
			 <span class="description" style="color: blue;"><?php _e('Hint', 'yfym'); ?>:</span> <span class="description"><?php _e('To select multiple values, hold down the (ctrl) button on Windows or (cmd) on a Mac. To deselect, press and hold (ctrl) or (cmd), click on the marked items', 'yfym'); ?></span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_store"><?php _e('Store', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<select name="yfym_store" id="yfym_store">
				<option value="true" <?php selected( $yfym_store, 'true' ); ?>><?php _e('True', 'yfym'); ?></option>
				<option value="false" <?php selected( $yfym_store, 'false' ); ?>><?php _e('False', 'yfym'); ?></option>
				</select><br />
				<span class="description"><?php _e('Optional element', 'yfym'); ?> <strong>store</strong></span>
				<ul>
					<li><?php _e('true', 'yfym'); ?> — <?php _e('The product can be purchased in retail stores', 'yfym'); ?></li>
					<li><?php _e('false', 'yfym'); ?> — <?php _e('the product cannot be purchased in retail stores', 'yfym'); ?></li>
				</ul><span class="description">
				<a target="_blank" href="//yandex.ru/support/partnermarket/delivery.html"><?php _e('Read more on Yandex', 'yfym'); ?></a>
				</span>
			</td>
		 </tr>
		</tbody></table> 
	   </div>
	 </div>
	 <div class="postbox">
	  <div class="inside">
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"><?php wp_nonce_field('yfym_nonce_action','yfym_nonce_field'); ?><input id="button-primary" class="button-primary" type="submit" name="yfym_submit_action" value="<?php _e( 'Save', 'yfym'); ?>" /><br />
			<span class="description"><?php _e('Click to save the settings', 'yfym'); ?></span></td>
		 </tr>
		</tbody></table>
	  </div>
	 </div>
	 </form>
	</div></div>
  <?php else: ?>
	<div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">
	 <div class="postbox">
	  <div class="inside">
		<h2><?php _e('For the plugin to function correctly, you need a version of WooCommerce 3.0 and higher! You have version ', 'yfym'); echo $woo_version; _e(' installed. Please, update WooCommerce', 'yfym'); ?>!<br /><a href="https://icopydoc.ru/minimalnye-trebovaniya-dlya-raboty-yml-for-yandex-market/"><?php _e('Learn More', 'yfym'); ?></a>.</h2>
	  </div>
	 </div> 
	</div></div>
  <?php endif; ?>
	<div id="postbox-container-2" class="postbox-container"><div class="meta-box-sortables">
	 <div class="postbox">
	  <div class="inside">
  		<h1><?php _e('Feed', 'yfym'); ?> <?php echo $numFeed; ?>: <?php if ($numFeed !== '1') {echo $numFeed;} ?>feed-yml-<?php echo $cur_blog_id; ?>.xml <?php if (empty($yfym_file_url)) : ?><?php _e('not created yet', 'yfym'); ?><?php else : ?><?php if ($status_sborki !== -1) : ?><?php _e('updating', 'yfym'); ?><?php else : ?><?php _e('created', 'yfym'); ?><?php endif; ?><?php endif; ?></h1>
		<?php if (empty($yfym_file_url)) : ?> 
			<?php if ($status_sborki !== -1) : ?>
				<p><?php _e('We are working on automatic file creation. YML will be developed soon.', 'yfym'); ?></p>
			<?php else : ?>		
				<p><?php _e('In order to do that, select another menu entry (which differs from "off") in the box called "Automatic file creation". You can also change values in other boxes if necessary, then press "Save".', 'yfym'); ?></p>
				<p><?php _e('After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message.', 'yfym'); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<?php if ($status_sborki !== -1) : ?>
				<p><?php _e('We are working on automatic file creation. YML will be developed soon.', 'yfym'); ?></p>
			<?php else : ?>
				<p><strong><?php _e('Your YML feed here', 'yfym'); ?>:</strong><br/><a target="_blank" href="<?php echo $yfym_file_url; ?>"><?php echo $yfym_file_url; ?></a>
				<br/><?php _e('Generated', 'yfym'); ?>: <?php echo $yfym_date_sborki; ?>
				</p>
				<p><?php _e('By clicking on "Save" you will overwrite the upload file for Yandex Market.', 'yfym'); ?></p>
			<?php endif; ?>		
		<?php endif; ?>
		<p><?php _e('Please note that Yandex Market checks YML no more than 3 times a day! This means that the changes on the Yandex Market are not instantaneous!', 'yfym'); ?></p>
	  </div>
	 </div>
	 <?php do_action('yfym_before_support_project', $numFeed); ?>
	 <div class="postbox">
	  <div class="inside">
	  <h1><?php _e('Please support the project!', 'yfym'); ?></h1>
	  <p><?php _e('Thank you for using the plugin', 'yfym'); ?> <strong>Yml for Yandex Market</strong></p>
	  <p><?php _e('Please help make the plugin better', 'yfym'); ?> <a href="https://docs.google.com/forms/d/e/1FAIpQLSdmEXYIQzW-_Hj2mwvVbzKT8UUKaScJWQjDwcgI7Y5D0Xmchw/viewform" target="_blank" ><?php _e('answering 6 questions', 'yfym'); ?>!</a></p>
	  <p><?php _e('If this plugin useful to you, please support the project one way', 'yfym'); ?>:</p>
	  <ul>
		<li>- <a href="//wordpress.org/plugins/yml-for-yandex-market/" target="_blank"><?php _e('Leave a comment on the plugin page', 'yfym'); ?></a>.</li>
		<li>- <?php _e('Support the project financially. Even $2 is a help!', 'yfym'); ?><a href="//yasobe.ru/na/yml_for_yandex_market" target="_blank"> <?php _e('Donate now', 'yfym'); ?></a>.</li>
		<li>- <?php _e('Noticed a bug or have an idea how to improve the quality of the plugin?', 'yfym'); ?> <a href="mailto:pt070@yandex.ru"><?php _e('Let me know', 'yfym'); ?></a>.</li>
	  </ul>
	  <p><?php _e('The author of the plugin Maxim Glazunov', 'yfym'); ?>.</p>
	  <p><span style="color: red;"><?php _e('Accept orders for individual revision of the plugin', 'yfym'); ?></span>:<br /><a href="mailto:pt070@yandex.ru"><?php _e('Leave a request', 'yfym'); ?></a>.</p>
	  </div>
	 </div>
	 <div class="postbox">
	  <div class="inside">
		<h1><?php _e('Send data about the work of the plugin', 'yfym'); ?></h1>
		<p><?php _e('Sending statistics you help make the plugin even better!', 'yfym'); ?>. <?php _e('The following data will be transferred', 'yfym'); ?>:</p>
		<ul>
			<li>- <?php _e('Site URL', 'yfym'); ?></li>
			<li>- <?php _e('File generation status', 'yfym'); ?></li>
			<li>- <?php _e('URL YML-feed', 'yfym'); ?></li>
			<li>- <?php _e('Is the multisite mode enabled?', 'yfym'); ?></li>
		</ul>
		<p><?php _e('The plugin helped you download the products to the Yandex Market', 'yfym'); ?>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
		 <p>
			<input type="radio" name="yfym_its_ok" value="yes"><?php _e( 'Yes', 'yfym'); ?><br />
			<input type="radio" name="yfym_its_ok" value="no"><?php _e( 'No', 'yfym'); ?><br />
		 </p>
		 <p><?php _e("If you don't mind to be contacted in case of problems, please enter your email address", "yfym"); ?>.</p>
		 <p><input type="email" name="yfym_email"></p>
		 <p><?php _e("Your message", "yfym"); ?>:</p>
		 <p><textarea rows="5" cols="40" name="yfym_message"></textarea></p>
		 <?php wp_nonce_field('yfym_nonce_action_send_stat', 'yfym_nonce_field_send_stat'); ?><input class="button-primary" type="submit" name="yfym_submit_send_stat" value="<?php _e('Send data', 'yfym'); ?>" />
		</form>
	  </div>
	 </div> 	 
	</div></div>
  </div></div>
 </div>
<?php
} /* end функция настроек yfym_export_page */ ?>