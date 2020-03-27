<?php if (!defined('ABSPATH')) {exit;}
include_once ABSPATH . 'wp-admin/includes/plugin.php'; // без этого не будет работать вне адмники is_plugin_active
function yfym_feed_header($numFeed = '1') {
 yfym_error_log('FEED № '.$numFeed.'; Стартовала yfym_feed_header; Файл: offer.php; Строка: '.__LINE__, 0);	

 $result_yml = '';
 $unixtime = current_time('Y-m-d H:i'); // время в unix формате 
 yfym_optionUPD('yfym_date_sborki', $unixtime, $numFeed);		
 $shop_name = stripslashes(yfym_optionGET('yfym_shop_name', $numFeed));
 $company_name = stripslashes(yfym_optionGET('yfym_company_name', $numFeed));
 $result_yml .= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
 $result_yml .= '<yml_catalog date="'.$unixtime.'">'.PHP_EOL;
 $result_yml .= "<shop>". PHP_EOL ."<name>$shop_name</name>".PHP_EOL;
 $result_yml .= "<company>$company_name</company>".PHP_EOL;
 $res_home_url = home_url('/');
 $res_home_url = apply_filters('yfym_home_url', $res_home_url, $numFeed);
 $result_yml .= "<url>".$res_home_url."</url>".PHP_EOL;
 $result_yml .= "<platform>WordPress - Yml for Yandex Market</platform>".PHP_EOL;
 $result_yml .= "<version>".get_bloginfo('version')."</version>".PHP_EOL;
		
 /* общие параметры */
 $res = get_woocommerce_currency(); // получаем валюта магазина
 $rateCB = '';
 switch ($res) { /* RUR, USD, EUR, UAH, KZT, BYN */
	case "RUB": $currencyId_yml = "RUR"; break;
	case "USD": $currencyId_yml = "USD"; $rateCB = "CB"; break;
	case "EUR": $currencyId_yml = "EUR"; $rateCB = "CB"; break;
	case "UAH": $currencyId_yml = "UAH"; break;
	case "KZT": $currencyId_yml = "KZT"; break;
	case "BYN": $currencyId_yml = "BYN"; break;	
	case "BYR": $currencyId_yml = "BYN"; break;
	case "ABC": $currencyId_yml = "BYN"; break;	
	default: $currencyId_yml = "RUR"; 
 }
 $rateCB = apply_filters('yfym_rateCB', $rateCB, $numFeed); /* с версии 2.3.1. */
 if ($rateCB == '') {
	$result_yml .= '<currencies>'. PHP_EOL .'<currency id="'.$currencyId_yml.'" rate="1"/>'. PHP_EOL .'</currencies>'.PHP_EOL;
 } else {
	$result_yml .= '<currencies>'. PHP_EOL .'<currency id="RUR" rate="1"/>'. PHP_EOL .'<currency id="'.$currencyId_yml.'" rate="'.$rateCB.'"/>'. PHP_EOL .'</currencies>'.PHP_EOL;		
 }
 // $terms = get_terms("product_cat");
 if (get_bloginfo('version') < '4.5') {
	$terms = get_terms('product_cat', array(
		'hide_empty'  => 0,  
		'orderby'     => 'name'
	));
 } else {
	$terms = get_terms(array(
		'hide_empty'  => 0,  
		'orderby'     => 'name',
		'taxonomy'    => 'product_cat'
	 ));		
 }
 $count = count($terms);
 $result_yml .= '<categories>'.PHP_EOL;
 if ($count > 0) {			
	foreach ($terms as $term) {
		$result_yml .= '<category id="'.$term->term_id.'"';
		if ($term->parent !== 0) {
			$result_yml .= ' parentId="'.$term->parent.'"';
		}		
		$result_yml .= '>'.$term->name.'</category>'.PHP_EOL;
	}			
 }
 $result_yml = apply_filters('yfym_append_categories_filter', $result_yml, $numFeed);
 $result_yml .= '</categories>'.PHP_EOL; 
		 
 $yfym_delivery_options = yfym_optionGET('yfym_delivery_options', $numFeed);
 if ($yfym_delivery_options === 'on') {
	$delivery_cost = yfym_optionGET('yfym_delivery_cost', $numFeed);
	$delivery_days = yfym_optionGET('yfym_delivery_days', $numFeed);
	$order_before = yfym_optionGET('yfym_order_before', $numFeed);
	if ($order_before == '') {$order_before_yml = '';} else {$order_before_yml = ' order-before="'.$order_before.'"';} 
		$result_yml .= '<delivery-options>'.PHP_EOL;
		$result_yml .= '<option cost="'.$delivery_cost.'" days="'.$delivery_days.'"'.$order_before_yml.'/>'.PHP_EOL;
		$result_yml .= '</delivery-options>	'.PHP_EOL;
 }	
			
 // магазин 18+
 $adult = yfym_optionGET('yfym_adult', $numFeed);
 if ($adult === 'yes') {$result_yml .= '<adult>true</adult>'.PHP_EOL;}		
 /* end общие параметры */		
 do_action('yfym_before_offers');
		
 /* индивидуальные параметры товара */
 $result_yml .= '<offers>'.PHP_EOL;	
 return $result_yml;
}
function yfym_unit($postId, $numFeed='1') {	
 yfym_error_log('FEED № '.$numFeed.'; Стартовала yfym_unit. $postId = '.$postId.'; Файл: offer.php; Строка: '.__LINE__, 0);	
 $result_yml = ''; $ids_in_yml = '';

 $product = wc_get_product($postId);
 if ($product == null) {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к get_post вернула null; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}

 if ($product->is_type('grouped')) {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к сгруппированный; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
 
 // что выгружать
 if ($product->is_type('variable')) {
	$yfym_whot_export = yfym_optionGET('yfym_whot_export', $numFeed);
	if ($yfym_whot_export === 'simple') {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к вариативный; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
 }
 
 if (get_post_meta($postId, 'yfymp_removefromyml', true) === 'on')  {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен принудительно; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
		  
 /* общие данные для вариативных и обычных товаров */		  
 $res = get_woocommerce_currency(); // получаем валюта магазина
 switch ($res) { /* RUR, USD, UAH, KZT */
	case "RUB":	$currencyId_yml = "RUR"; break;
	case "USD":	$currencyId_yml = "USD"; break;
	case "EUR":	$currencyId_yml = "EUR"; break;
	case "UAH":	$currencyId_yml = "UAH"; break;
	case "KZT":	$currencyId_yml = "KZT"; break;
	case "BYN":	$currencyId_yml = "BYN"; break;
	case "BYR": $currencyId_yml = "BYN"; break;
	case "ABC": $currencyId_yml = "BYN"; break;
	default: $currencyId_yml = "RUR";
 }
		  
 // Возможность купить товар в розничном магазине. // true или false
 $store = yfym_optionGET('yfym_store', $numFeed);
 if ($store === false || $store == '') {
	yfym_error_log('FEED № '.$numFeed.'; WARNING: Товар с postId = '.$postId.' вернул пустой $result_yml_store; Файл: offer.php; Строка: '.__LINE__, 0);
	$result_yml_store = '';
 } else {
	$result_yml_store = "<store>".$store."</store>".PHP_EOL;
 }

 if (get_post_meta($postId, 'yfym_individual_delivery', true) !== '') {	
	$delivery = get_post_meta($postId, 'yfym_individual_delivery', true);
	if ($delivery === 'off') {$delivery = yfym_optionGET('yfym_delivery', $numFeed);}
 } else {
 	$delivery = yfym_optionGET('yfym_delivery', $numFeed);
 }
 if ($delivery === false || $delivery == '') {
	yfym_error_log('FEED № '.$numFeed.'; WARNING: Товар с postId = '.$postId.' вернул пустой $delivery; Файл: offer.php; Строка: '.__LINE__, 0);
	$result_yml_delivery = '';
 } else {
 	$result_yml_delivery = "<delivery>".$delivery."</delivery>".PHP_EOL; /* !советуют false */
 }
 /*	
 *	== delivery ==
 *	Элемент, отражающий возможность доставки соответствующего товара.
 *	«false» — товар не может быть доставлен («самовывоз»).
 *	«true» — доставка товара осуществлятся в регионы, указанные 
 *	во вкладке «Магазин» в разделе «Товары и цены». 
 *	Стоимость доставки описывается в теге <local_delivery_cost>.
 */	 

 if (get_post_meta($postId, 'yfym_individual_pickup', true) !== '') {	
	$pickup = get_post_meta($postId, 'yfym_individual_pickup', true);
	if ($pickup === 'off') {$pickup = yfym_optionGET('yfym_pickup', $numFeed);}
 } else {
 	$pickup = yfym_optionGET('yfym_pickup', $numFeed);
 }
 if ($pickup === false || $pickup == '') {
	yfym_error_log('FEED № '.$numFeed.'; WARNING: Товар с postId = '.$postId.' вернул пустой $pickup; Файл: offer.php; Строка: '.__LINE__, 0);
	$result_yml_pickup = '';
 } else {
 	$result_yml_pickup = "<pickup>".$pickup."</pickup>".PHP_EOL;
 }

 $result_yml_name = htmlspecialchars($product->get_title()); // htmlspecialchars($cur_post->post_title); // название товара
 $result_yml_name = apply_filters('yfym_change_name', $result_yml_name, get_the_id(), $product, $numFeed);
 /* с версии 2.0.7 в фильтр добавлен параметр $product */
		  
 // описание
 $yfym_desc = yfym_optionGET('yfym_desc', $numFeed);
 $result_xml_desc = ''; 
 switch ($yfym_desc) { 
	case "full": $description_yml = $product->get_description(); break;
	case "excerpt": $description_yml = $product->get_short_description(); break;
	case "fullexcerpt": 
		$description_yml = $product->get_description(); 
		if (empty($description_yml)) {
			 $description_yml = $product->get_short_description();
		}
	break;
	case "excerptfull": 
		$description_yml = $product->get_short_description();		 
		if (empty($description_yml)) {
			 $description_yml = $product->get_description();
		} 
	break;
	default: $description_yml = $product->get_description(); break;
 }	
 $result_yml_desc = '';
 if (!empty($description_yml)) {
	$description_yml = strip_tags($description_yml, '<p>,<h3>,<ul>,<li>,<br/>,<br>');
	$description_yml = str_replace('<br>', '<br/>', $description_yml);
	$description_yml = strip_shortcodes($description_yml);
	$description_yml = apply_filters('yfym_description_filter', $description_yml, $postId, $product, $numFeed);
	/* с версии 2.0.10 в фильтр добавлен параметр $product */
	$description_yml = trim($description_yml);
	if ($description_yml !== '') {
		$result_yml_desc = '<description><![CDATA['.$description_yml.']]></description>'.PHP_EOL;
	} 
 }
		  
 $params_arr = unserialize(yfym_optionGET('yfym_params_arr', $numFeed));
		  
 // echo "Категории ".$product->get_categories();
 $result_yml_cat = '';
 $catpostid = '';	  
 if (class_exists('WPSEO_Primary_Term')) {		  
	$catWPSEO = new WPSEO_Primary_Term('product_cat', $postId);
	$catidWPSEO = $catWPSEO->get_primary_term();	
	if ($catidWPSEO !== false) { 
	 $CurCategoryId = $catidWPSEO;
	 $result_yml_cat .= '<categoryId>'.$catidWPSEO.'</categoryId>'.PHP_EOL;
	} else {
	 $termini = get_the_terms($postId, 'product_cat');	
	 if ($termini !== false) {
	  foreach ($termini as $termin) {
		$catpostid = $termin->term_taxonomy_id;
		$result_yml_cat .= '<categoryId>'.$termin->term_taxonomy_id.'</categoryId>'.PHP_EOL;
		$CurCategoryId = $termin->term_taxonomy_id; // запоминаем id категории для товара
		break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
	  }
	 } else { // если база битая. фиксим id категорий
	  yfym_error_log('FEED № '.$numFeed.'; Warning: Для товара $postId = '.$postId.' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: '.__LINE__, 0);$product_cats = wp_get_post_terms($postId, 'product_cat', array("fields" => "ids" ));	  
	  // Раскомментировать строку ниже для автопочинки категорий в БД (место 1 из 2)
	  // wp_set_object_terms($postId, $product_cats, 'product_cat');
	  if (is_array($product_cats) && count($product_cats)) {
		$catpostid = $product_cats[0];
		$result_yml_cat .= '<categoryId>'.$catpostid.'</categoryId>'.PHP_EOL;
		$CurCategoryId = $product_cats[0]; // запоминаем id категории для товара
		yfym_error_log('FEED № '.$numFeed.'; Warning: Для товара $postId = '.$postId.' база наверняка битая. wp_get_post_terms вернула массив. $catpostid = '.$catpostid.'; Файл: offer.php; Строка: '.__LINE__, 0);
	  }
	 }
	}
 } else {	
	$termini = get_the_terms($postId, 'product_cat');
	if ($termini !== false) {
	 foreach ($termini as $termin) {
		$catpostid = $termin->term_taxonomy_id;
		$result_yml_cat .= '<categoryId>'.$termin->term_taxonomy_id.'</categoryId>'.PHP_EOL;
		$CurCategoryId = $termin->term_taxonomy_id; // запоминаем id категории для товара
		break; // т.к. у товара может быть лишь 1 категория - выходим досрочно.
	 }
	} else { // если база битая. фиксим id категорий
	 yfym_error_log('FEED № '.$numFeed.'; Warning: Для товара $postId = '.$postId.' get_the_terms = false. Возможно база битая. Пробуем задействовать wp_get_post_terms; Файл: offer.php; Строка: '.__LINE__, 0);
	 $product_cats = wp_get_post_terms($postId, 'product_cat', array("fields" => "ids" ));
	 // Раскомментировать строку ниже для автопочинки категорий в БД (место 2 из 2)	 
	 // wp_set_object_terms($postId, $product_cats, 'product_cat');	 
	 if (is_array($product_cats) && count($product_cats)) {
		$catpostid = $product_cats[0];
		$result_yml_cat .= '<categoryId>'.$catpostid.'</categoryId>'.PHP_EOL;
		$CurCategoryId = $product_cats[0]; // запоминаем id категории для товара
		yfym_error_log('FEED № '.$numFeed.'; Warning: Для товара $postId = '.$postId.' база наверняка битая. wp_get_post_terms вернула массив. $catpostid = '.$catpostid.'; Файл: offer.php; Строка: '.__LINE__, 0);		
	 }
	}
 }
 $result_yml_cat = apply_filters('yfym_after_cat_filter', $result_yml_cat, $postId, $numFeed);
 if ($result_yml_cat == '') {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к нет категорий; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
 /* $termin->ID - понятное дело, ID элемента
 * $termin->slug - ярлык элемента
 * $termin->term_group - значение term group
 * $termin->term_taxonomy_id - ID самой таксономии
 * $termin->taxonomy - название таксономии
 * $termin->description - описание элемента
 * $termin->parent - ID родительского элемента
 * $termin->count - количество содержащихся в нем постов
 */			  
 /* end общие данные для вариативных и обычных товаров */
 
 $append_offer_tag = '';
 if (get_post_meta($postId, 'yfym_bid', true) !== '') {
	$yfym_bid = get_post_meta($postId, 'yfym_bid', true);
	$append_offer_tag = 'bid="'.$yfym_bid.'"';
 }
		  
 /* Вариации */
 // если вариация - нам нет смысла выгружать общее предложение
 if ($product->is_type('variable')) {
	yfym_error_log('FEED № '.$numFeed.'; У нас вариативный товар. Файл: offer.php; Строка: '.__LINE__, 0);	

	$variations = array();
	if ($product->is_type('variable')) {
		$variations = $product->get_available_variations();
		$variation_count = count($variations);
	} 
	for ($i = 0; $i<$variation_count; $i++) {
	
		$offer_id = (($product->is_type('variable')) ? $variations[$i]['variation_id'] : $product->get_id());
		$offer = new WC_Product_Variation($offer_id); // получим вариацию
		$result_yml_name = apply_filters('yfym_variable_change_name', $result_yml_name, get_the_id(), $product, $offer, $numFeed);
		/* с версии 2.0.7 в фильтр добавлен параметр $product */

		/*
		* $offer->get_price() - актуальная цена (равна sale_price или regular_price если sale_price пуст)
		* $offer->get_regular_price() - обычная цена
		* $offer->get_sale_price() - цена скидки
		*/
			 
		$price_yml = $offer->get_price(); // цена вариации
		$price_yml = apply_filters('yfym_variable_price_filter', $price_yml, $product, $offer, $offer_id, $numFeed); /* с версии 3.0.0 */ 
		// если цены нет - пропускаем вариацию 			 
		if ($price_yml == 0 || empty($price_yml)) {yfym_error_log('FEED № '.$numFeed.'; Вариация товара с postId = '.$postId.' пропущена т.к нет цены; Файл: offer.php; Строка: '.__LINE__, 0); continue;}
		
		if (class_exists('YmlforYandexMarketPro')) {
			if ((yfym_optionGET('yfymp_compare_value', $numFeed) !== false) && (yfym_optionGET('yfymp_compare_value', $numFeed) !== '')) {
			 $yfymp_compare_value = yfym_optionGET('yfymp_compare_value', $numFeed);
			 $yfymp_compare = yfym_optionGET('yfymp_compare', $numFeed);			 
			 if ($yfymp_compare == '>=') {
				if ($price_yml < $yfymp_compare_value) {continue;}
			 } else {
				if ($price_yml >= $yfymp_compare_value) {continue;}
			 }
			}
		}
		// пропуск вариаций, которых нет в наличии
		$yfym_skip_missing_products = yfym_optionGET('yfym_skip_missing_products', $numFeed);
		if ($yfym_skip_missing_products === 'on') {
			if ($offer->is_in_stock() == false) {yfym_error_log('FEED № '.$numFeed.'; Вариация товара с postId = '.$postId.' пропущена т.к ее нет в наличии; Файл: offer.php; Строка: '.__LINE__, 0); continue;}
		}
			 
		// пропускаем вариации на предзаказ
		$skip_backorders_products = yfym_optionGET('yfym_skip_backorders_products', $numFeed);
		if ($skip_backorders_products === 'on') {
		 if ($offer->get_manage_stock() == true) { // включено управление запасом			  
			if (($offer->get_stock_quantity() < 1) && ($offer->get_backorders() !== 'no')) {yfym_error_log('FEED № '.$numFeed.'; Вариация товара с postId = '.$postId.' пропущена т.к запрещен предзаказ и включено управление запасом; Файл: offer.php; Строка: '.__LINE__, 0); continue;}
		 }
		}
		
		$thumb_yml = get_the_post_thumbnail_url($offer->get_id(), 'full');
		if (empty($thumb_yml)) {			
			// убираем default.png из фида
			$no_default_png_products = yfym_optionGET('yfym_no_default_png_products', $numFeed);
			if (($no_default_png_products === 'on') && (!has_post_thumbnail($postId))) {$picture_yml = '';} else {
				$thumb_id = get_post_thumbnail_id($postId);
				$thumb_url = wp_get_attachment_image_src($thumb_id,'full', true);	
				$thumb_yml = $thumb_url[0]; /* урл оригинал миниатюры товара */
				$picture_yml = '<picture>'.deleteGET($thumb_yml).'</picture>'.PHP_EOL;
			}
		} else {
			$picture_yml = '<picture>'.deleteGET($thumb_yml).'</picture>'.PHP_EOL;
		}
		$picture_yml = apply_filters('yfym_pic_variable_offer_filter', $picture_yml, $product, $numFeed);					
			
		// пропускаем вариации без картинок
		$yfym_skip_products_without_pic = yfym_optionGET('yfym_skip_products_without_pic', $numFeed); 
		if (($yfym_skip_products_without_pic === 'on') && ($picture_yml == '')) {	  
			yfym_error_log('FEED № '.$numFeed.'; Вариация товара с postId = '.$postId.' пропущена т.к нет картинки даже в галерее; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml; /*continue;*/  
		}
			
		do_action('yfym_before_variable_offer');

		if ($offer->get_manage_stock() == true) { // включено управление запасом
		 if ($offer->get_stock_quantity() > 0) {
			$available = 'true';
		 } else {
			$available = 'false';
		 }
		} else { // отключено управление запасом
		 if ($offer->is_in_stock() == true) {$available = 'true';} else {$available = 'false';}
		}	

		// массив категорий для которых запрещен group_id
		$no_group_id_arr = unserialize(yfym_optionGET('yfym_no_group_id_arr', $numFeed));
		if (empty($no_group_id_arr)) {
			// массив пуст. все категории выгружаем с group_id
			$gi = 'group_id="'.$product->get_id().'"';
			$result_yml_name_itog = $result_yml_name;
		} else {
			// массив с group_id заполнен
			$CurCategoryId = (string)$CurCategoryId;
			// если id текущей категории совпал со списком категорий без group_id			  
			if (in_array($CurCategoryId, $no_group_id_arr)) {
			 $gi = '';
				
			 $add_in_name_arr = unserialize(yfym_optionGET('yfym_add_in_name_arr', $numFeed));
			 $attributes = $product->get_attributes(); // получили все атрибуты товара
			 $param_at_name = '';		

			 $separator_type = yfym_optionGET('yfym_separator_type', $numFeed);			 
			 switch ($separator_type) { /* off, sku, или id */
				case "type1":
					$so = '('; $sz = ')';
					$sd = ':'; $sr = ',';
				case "type2":	
					$so = '('; $sz = ')';
					$sd = '-'; $sr = ',';
				break; 
				case "type3":
					$so = ''; $sz = '';
					$sd = ':'; $sr = ',';
				break;
				default: 
					$so = ''; $sz = '';
					$sd = ':'; $sr = ',';
			 }		 
			 
			 foreach ($attributes as $param) {					
				if ($param->get_variation() == false) {
					// это обычный атрибут
					continue;
					$param_val = $product->get_attribute(wc_attribute_taxonomy_name_by_id($param->get_id()));
				} else { 
					// это атрибут вариации
					$param_val = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($param->get_id()));
				}
				// если этот параметр не нужно выгружать - пропускаем
				$variation_id_string = (string)$param->get_id(); // важно, т.к. в настройках id как строки
				if (!in_array($variation_id_string, $add_in_name_arr, true)) {continue;}
				$param_name = wc_attribute_label(wc_attribute_taxonomy_name_by_id($param->get_id()));
				// если пустое имя атрибута или значение - пропускаем
				if (empty($param_name) || empty($param_val)) {continue;}
				$param_at_name .= $param_name.$sd.ucfirst(urldecode($param_val)).' ';
			 }
				$param_at_name = trim($param_at_name);
				if ($param_at_name == '') {
					yfym_error_log('FEED № '.$numFeed.'; Пропускаем товар т.к. нет атрибутов вариаций для уникальзации заголовков. Файл: offer.php; Строка: '.__LINE__, 0);	
					return $result_yml;	/*continue;*/
				}
				$result_yml_name_itog = $result_yml_name.' '.$so.$param_at_name.$sz;
				$result_yml_name_itog = apply_filters('yfym_name_no_groupid_filter', $result_yml_name_itog, $result_yml_name, $product, $so, $sz, $sd, $sr, $numFeed);
			} else {
				// совпадений нет. подставляем group_id
				$gi = 'group_id="'.$product->get_id().'"';
				$result_yml_name_itog = $result_yml_name;
			}
		}
				 
		// $result_yml .= '<offer '.$gi.' id="'.$product->get_id().'var'.$offer_id.'" available="'.$available.'">'.PHP_EOL;
		/* с версии 2.1.2 */
		$append_offer_tag = apply_filters('yfym_append_offer_tag_filter', $append_offer_tag, $product, $numFeed);		
		$result_yml .= '<offer '.$gi.' id="'.$offer_id.'" available="'.$available.'" '.$append_offer_tag.'>'.PHP_EOL;		
		do_action('yfym_prepend_variable_offer');
			 
		// $param_at_name = '';
		// Param в вариациях
		if (!empty($params_arr)) {
			$attributes = $product->get_attributes(); // получили все атрибуты товара		 
			foreach ($attributes as $param) {					
			 if ($param->get_variation() == false) {
				// это обычный атрибут
				$param_val = $product->get_attribute(wc_attribute_taxonomy_name_by_id($param->get_id())); 
			 } else { 
				$param_val = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($param->get_id()));
			 }				
			 // если этот параметр не нужно выгружать - пропускаем
			 $variation_id_string = (string)$param->get_id(); // важно, т.к. в настройках id как строки
			 if (!in_array($variation_id_string, $params_arr, true)) {continue;}
			 $param_name = wc_attribute_label(wc_attribute_taxonomy_name_by_id($param->get_id()));
			 // если пустое имя атрибута или значение - пропускаем
			 if (empty($param_name) || empty($param_val)) {continue;}
			 $result_yml .= '<param name="'.$param_name.'">'.ucfirst(urldecode($param_val)).'</param>'.PHP_EOL;
			 // $param_at_name .= ucfirst(urldecode($param_val)).' ';
			}	
		}

		$result_yml .= "<name>".htmlspecialchars($result_yml_name_itog)."</name>".PHP_EOL;
		$yfym_enable_auto_discounts = yfym_optionGET('yfym_enable_auto_discounts', $numFeed);
		if ($yfym_enable_auto_discounts === 'on') {
			$result_yml .= "<enable_auto_discounts>yes</enable_auto_discounts>".PHP_EOL;
		}
		// $result_yml .= "<name>".$result_yml_name." (".$param_at_name.")</name>".PHP_EOL;

		// Описание.
		$description_yml = $offer->get_description();
		if (!empty($description_yml)) {
			$description_yml = strip_tags($description_yml, '<p>,<h3>,<ul>,<li>,<br/>,<br>');
			$description_yml = str_replace('<br>', '<br/>', $description_yml);
			$description_yml = strip_shortcodes($description_yml);
			$description_yml = apply_filters('yfym_description_filter', $description_yml, $postId, $product, $offer, $numFeed);
			/* с версии 2.0.10 в фильтр добавлен параметры $product и $offer */
			$description_yml = trim($description_yml);
			if ($description_yml !== '') {
				$result_yml .= '<description><![CDATA['.$description_yml.']]></description>'.PHP_EOL;
			}
		} else {
			// если у вариации нет своего описания - пробуем подставить общее
			if (!empty($result_yml_desc)) {$result_yml .= $result_yml_desc;}
		}
		 
		$result_yml .= $picture_yml;	
	 
		$result_url = htmlspecialchars(get_permalink($offer->get_id()));
		$result_url = apply_filters('yfym_url_filter', $result_url, $product, $CurCategoryId, $numFeed);
		/* с версии 2.0.12 в фильтр добавлен параметр $CurCategoryId */
		 
		$result_yml .= "<url>".$result_url."</url>".PHP_EOL;
		 
		$price_yml = apply_filters('yfym_variable_price_yml_filter', $price_yml, $product, $offer, $numFeed); /* с версии 3.1.0 */
		$yfym_price_from = yfym_optionGET('yfym_price_from', $numFeed);
		if ($yfym_price_from === 'yes') {
			$result_yml .= "<price from='true'>".$price_yml."</price>".PHP_EOL;
		} else {
			$result_yml .= "<price>".$price_yml."</price>".PHP_EOL;
		}
		// старая цена
		$yfym_oldprice = yfym_optionGET('yfym_oldprice', $numFeed);
		if ($yfym_oldprice === 'yes') {
			$sale_price = (int)$offer->get_sale_price();
			$price_yml = (int)$price_yml;
			if ($sale_price > 0) {
				if ($price_yml === $sale_price) {		
					$oldprice_yml = $offer->get_regular_price();
					$result_yml .= "<oldprice>".$oldprice_yml."</oldprice>".PHP_EOL;
				}
			}
		}	 
		$result_yml .= '<currencyId>'.$currencyId_yml.'</currencyId>'.PHP_EOL;			 
			 			 
		// штрихкод			 
		$yfym_barcode = yfym_optionGET('yfym_barcode', $numFeed);
		if ($yfym_barcode !== 'none') {
			switch ($yfym_barcode) { /* off, sku, или id */
			 case "off":	
				// выгружать штрихкод нет нужды
			 break; 
			 case "sku":
				// выгружать из артикула
				$sku_yml = $offer->get_sku(); // артикул
				if (!empty($sku_yml)) {
					$result_yml .= "<barcode>".$sku_yml."</barcode>".PHP_EOL;
				} else {
					// своего артикула у вариации нет. Пробуем подставить общий sku
					$sku_yml = $product->get_sku();
					if (!empty($sku_yml)) {
						$result_yml .= "<barcode>".$sku_yml."</barcode>".PHP_EOL;
					}
				}
			 break;
			 default:
				$yfym_barcode = (int)$yfym_barcode;
				$yfym_barcode_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($yfym_barcode));					
				if (!empty($yfym_barcode_yml)) {
					$result_yml .= '<barcode>'.urldecode($yfym_barcode_yml).'</barcode>'.PHP_EOL;
				}
			}
		}
			 
		$weight_yml = $offer->get_weight(); // вес
		if (!empty($weight_yml)) {
			$weight_yml = round(wc_get_weight($weight_yml, 'kg'), 3);
			$result_yml .= "<weight>".$weight_yml."</weight>".PHP_EOL;
		}
			 
		/* $dimensions = $offer->get_dimensions();
		if (!empty($dimensions)) { */
		$dimensions = wc_format_dimensions($offer->get_dimensions(false));
        if ($offer->has_dimensions()) {
			$length_yml = $offer->get_length();
			if (!empty($length_yml)) {$length_yml = round(wc_get_dimension($length_yml, 'cm'), 3);}
			   
			$width_yml = $offer->get_width();
			if (!empty($length_yml)) {$width_yml = round(wc_get_dimension($width_yml, 'cm'), 3);}
			  
			$height_yml = $offer->get_height();
			if (!empty($length_yml)) {$height_yml = round(wc_get_dimension($height_yml, 'cm'), 3);}		  
			   
			if (($length_yml > 0) && ($width_yml > 0) && ($height_yml > 0)) {
				$result_yml .= '<dimensions>'.$length_yml.'/'.$width_yml.'/'.$height_yml.'</dimensions>'.PHP_EOL;
			}
		}

		$expiry = yfym_optionGET('yfym_expiry', $numFeed);
		if (!empty($expiry) && $expiry !== 'off') {
			$expiry = (int)$expiry;
			$expiry_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($expiry));
			if (!empty($expiry_yml)) {	
				$result_yml .= "<expiry>".ucfirst(urldecode($expiry_yml))."</expiry>".PHP_EOL;		
			} else {
				$expiry_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($expiry));
				if (!empty($expiry_yml)) {	
					$result_yml .= "<expiry>".ucfirst(urldecode($expiry_yml))."</expiry>".PHP_EOL;		
				}		
			}
		}
		$age = yfym_optionGET('yfym_age', $numFeed);
		if (!empty($age) && $age !== 'off') {
		 $age = (int)$age;
		 $age_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($age));
		 if (!empty($age_yml)) {	
			$result_yml .= "<age>".ucfirst(urldecode($age_yml))."</age>".PHP_EOL;		
		 } else {
			$age_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($age));
			if (!empty($age_yml)) {	
				$result_yml .= "<age>".ucfirst(urldecode($age_yml))."</age>".PHP_EOL;		
			}
		 }
		}
		$downloadable = yfym_optionGET('yfym_downloadable', $numFeed);
		if (!empty($downloadable) && $downloadable !== 'off') {
			if ($offer->is_downloadable('yes')) {
				$result_yml .= "<downloadable>true</downloadable>".PHP_EOL;	
			} else {
				$result_yml .= "<downloadable>false</downloadable>".PHP_EOL;							
			}
		}
			 
		// страна производитель
		$country_of_origin = yfym_optionGET('yfym_country_of_origin', $numFeed);
		if (!empty($country_of_origin) && $country_of_origin !== 'off') {
			$country_of_origin = (int)$country_of_origin;
			$country_of_origin_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($country_of_origin));
			if (!empty($country_of_origin_yml)) {	
				$result_yml .= "<country_of_origin>".ucfirst(urldecode($country_of_origin_yml))."</country_of_origin>".PHP_EOL;		
			} else {
				$country_of_origin_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($country_of_origin));
				if (!empty($country_of_origin_yml)) {	
					$result_yml .= "<country_of_origin>".ucfirst(urldecode($country_of_origin_yml))."</country_of_origin>".PHP_EOL;		
				}
			}
		}
			 
		$sales_notes_cat = yfym_optionGET('yfym_sales_notes_cat', $numFeed);
		if (!empty($sales_notes_cat) && $sales_notes_cat !== 'off') {
			$sales_notes_cat = (int)$sales_notes_cat;
			$sales_notes_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($sales_notes_cat));
			if (empty($sales_notes_yml)) {
				$sales_notes_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($sales_notes_cat));
			}    
			if (!empty($sales_notes_yml)) {	
				$result_yml .= "<sales_notes>".ucfirst(urldecode($sales_notes_yml))."</sales_notes>".PHP_EOL;		
			} else {
				$sales_notes = yfym_optionGET('yfym_sales_notes', $numFeed);
				if (!empty($sales_notes)) {
					$result_yml .= "<sales_notes>$sales_notes</sales_notes>".PHP_EOL;
				}
			}
		}
		// гарантия
		$manufacturer_warranty = yfym_optionGET('yfym_manufacturer_warranty', $numFeed);
		if (!empty($manufacturer_warranty) && $manufacturer_warranty !== 'off') {			
			if ($manufacturer_warranty === 'alltrue') {
				$result_yml .= "<manufacturer_warranty>true</manufacturer_warranty>".PHP_EOL;
			} else if ($manufacturer_warranty === 'allfalse') {
				$result_yml .= "<manufacturer_warranty>false</manufacturer_warranty>".PHP_EOL;
			} else {
			 $manufacturer_warranty = (int)$manufacturer_warranty;
			 $manufacturer_warranty_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer_warranty));
			 if (!empty($manufacturer_warranty_yml)) {	
				$result_yml .= "<manufacturer_warranty>".urldecode($manufacturer_warranty_yml)."</manufacturer_warranty>".PHP_EOL;
			 } else {$manufacturer_warranty_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer_warranty));
				if (!empty($manufacturer_warranty_yml)) {	
					$result_yml .= "<manufacturer_warranty>".urldecode($manufacturer_warranty_yml)."</manufacturer_warranty>".PHP_EOL;
				}
			 }
			}			
		}
		
		$vendor = yfym_optionGET('yfym_vendor', $numFeed);
		if (class_exists('Perfect_Woocommerce_Brands') && $vendor === 'sfpwb') {
			$barnd_terms = get_the_terms($product->get_id(), 'pwb-brand');
			if ($barnd_terms !== false) {
			 foreach($barnd_terms as $barnd_term) {
				$result_yml .= '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
				break;
			 }
			}
		} else if ((is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) && ($vendor === 'premmercebrandsplugin')) {
			$barnd_terms = get_the_terms($product->get_id(), 'product_brand');
			if ($barnd_terms !== false) {
			 foreach($barnd_terms as $barnd_term) {
				$result_yml .= '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
				break;
			 }
			}			
		} else {
		 if ($vendor !== 'none') {
			$vendor = (int)$vendor;
			$vendor_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($vendor));
			if (!empty($vendor_yml)) {
				$result_yml .= '<vendor>'.ucfirst(urldecode($vendor_yml)).'</vendor>'.PHP_EOL;
			} else {
				$vendor_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($vendor));
				if (!empty($vendor_yml)) {
					$result_yml .= '<vendor>'.ucfirst(urldecode($vendor_yml)).'</vendor>'.PHP_EOL;
				}
			}
		 }
		}	
		$model = yfym_optionGET('yfym_model', $numFeed);
		if ($model !== 'none') {
			$model = (int)$model;
			$model_yml = $offer->get_attribute(wc_attribute_taxonomy_name_by_id($model));
			if (!empty($model_yml)) {				 
				$result_yml .= '<model>'.ucfirst(urldecode($model_yml)).'</model>'.PHP_EOL;
			} else {
				$model_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($model));
				if (!empty($model_yml)) {				 
					$result_yml .= '<model>'.ucfirst(urldecode($model_yml)).'</model>'.PHP_EOL;
				}
			}
		}
			 
		// вариция. если offer_type пуст, то можно выгружать vendorCode
		// if ($offer_type =='') { 
		$yfym_vendorcode = yfym_optionGET('yfym_vendorcode', $numFeed);
		if ($yfym_vendorcode !== 'none') {
			switch ($yfym_vendorcode) { /* off, sku, или id */
			 case "off":	
				// выгружать штрихкод нет нужды
			 break; 
			 case "sku":
				// выгружать из артикула
				$sku_yml = $offer->get_sku(); // артикул
				if (!empty($sku_yml)) {
					$result_yml .= "<vendorCode>".$sku_yml."</vendorCode>".PHP_EOL;
				} else {
					// своего артикула у вариации нет. Пробуем подставить общий sku
					$sku_yml = $product->get_sku();
					if (!empty($sku_yml)) {
						$result_yml .= "<vendorCode>".$sku_yml."</vendorCode>".PHP_EOL;
					}
				}
			 break;
			 default:
				$yfym_vendorcode = (int)$yfym_vendorcode;
				$yfym_vendorcode_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($yfym_vendorcode));					
				if (!empty($yfym_vendorcode_yml)) {
					$result_yml .= '<vendorCode>'.urldecode($yfym_vendorcode_yml).'</vendorCode>'.PHP_EOL;
				}
			}
		}
		// }
		/* c версии 2.0.8 */
		$result_yml_pickup = apply_filters('yfym_pickup_filter', $result_yml_pickup, $catpostid, $product, $pickup, $numFeed);
			 
		$result_yml .= $result_yml_store;
		$result_yml .= $result_yml_pickup;
		$result_yml .= $result_yml_delivery;
		$result_yml .= $result_yml_cat; // Категории		 

		do_action('yfym_append_variable_offer');
		$result_yml = apply_filters('yfym_append_variable_offer_filter', $result_yml, $product, $offer, $numFeed);	 

		if ((get_post_meta($postId, 'yfym_cost', true) !== '') && (get_post_meta($postId, 'yfym_days', true) !== '')) {
			$yfym_cost = get_post_meta($postId, 'yfym_cost', true);
			$yfym_days = get_post_meta($postId, 'yfym_days', true);	
			if (get_post_meta($postId, 'yfym_order_before', true) !== '') {
				$yfym_order_before = get_post_meta($postId, 'yfym_order_before', true);
				$yfym_order_before_yml = ' order-before="'.$yfym_order_before.'"';
			} else {
				$yfym_order_before_yml = '';
			}	
			$result_yml .= '<delivery-options>'.PHP_EOL;
			$result_yml .= '<option cost="'.$yfym_cost.'" days="'.$yfym_days.'"'.$yfym_order_before_yml.'/>'.PHP_EOL;
			$result_yml .= '</delivery-options>	'.PHP_EOL;
		}
		
		if ((get_post_meta($postId, 'yfym_pickup_cost', true) !== '') && (get_post_meta($postId, 'yfym_pickup_days', true) !== '')) {
			$yfym_pickup_cost = get_post_meta($postId, 'yfym_pickup_cost', true);
			$yfym_pickup_days = get_post_meta($postId, 'yfym_pickup_days', true);	
			if (get_post_meta($postId, 'yfym_pickup_order_before', true) !== '') {
				$yfym_pickup_order_before = get_post_meta($postId, 'yfym_pickup_order_before', true);
				$yfym_pickup_order_before_yml = ' order-before="'.$yfym_pickup_order_before.'"';
			} else {
				$yfym_pickup_order_before_yml = '';
			}	
			$result_yml .= '<pickup-options>'.PHP_EOL;
			$result_yml .= '<option cost="'.$yfym_pickup_cost.'" days="'.$yfym_pickup_days.'"'.$yfym_pickup_order_before_yml.'/>'.PHP_EOL;
			$result_yml .= '</pickup-options>	'.PHP_EOL;
		}
		
		if ((get_post_meta($postId, 'yfym_condition', true) !== '') && (get_post_meta($postId, 'yfym_condition', true) !== 'off') && (get_post_meta($postId, 'yfym_reason', true) !== '')) {
			$yfym_condition = get_post_meta($postId, 'yfym_condition', true);
			$yfym_reason = get_post_meta($postId, 'yfym_reason', true);	
			$result_yml .= '<condition type="'.$yfym_condition.'">'.PHP_EOL;
				$result_yml .= '<reason>'.$yfym_reason.'</reason>'.PHP_EOL;
			$result_yml .= '</condition>'.PHP_EOL;	
		}	

		if ((get_post_meta($postId, 'yfym_credit_template', true) !== '') && (get_post_meta($postId, 'yfym_credit_template', true) !== '')) {
			$yfym_credit_template = get_post_meta($postId, 'yfym_credit_template', true);
			$result_yml .= '<credit-template id="'.$yfym_credit_template.'"/>'.PHP_EOL;
		}		
		$result_yml .= '</offer>'.PHP_EOL;

		do_action('yfym_after_variable_offer');

		$ids_in_yml .= $postId.';'.$offer_id.';'.$price_yml.';'.$CurCategoryId.PHP_EOL; /* с версии 3.1.0 */

		/* с версии 2.3.0 */
		$stop_flag = false;
		$stop_flag = apply_filters('yfym_after_variable_offer_stop_flag', $stop_flag, $i, $variation_count, $offer_id, $offer, $numFeed);
		if ($stop_flag == true) {break;}
	} // end for ($i = 0; $i<$variation_count; $i++) 
	yfym_error_log('FEED № '.$numFeed.'; Все вариации выгрузили. '.$ids_in_yml.' Файл: offer.php; Строка: '.__LINE__, 0);	
	
	return array($result_yml, $ids_in_yml); // все вариации выгрузили	
 } // end if ($product->is_type('variable'))	 
 /* end Вариации */

 yfym_error_log('FEED № '.$numFeed.'; У нас обычный товар. Файл: offer.php; Строка: '.__LINE__, 0);
 // если цена не указана - пропускаем товар
 $price_yml = $product->get_price();
 $price_yml = apply_filters('yfym_simple_price_filter', $price_yml, $product, $numFeed); /* с версии 3.0.0 */ 
 if ($price_yml == 0 || empty($price_yml)) {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к нет цены; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
 if (class_exists('YmlforYandexMarketPro')) {
	if ((yfym_optionGET('yfymp_compare_value', $numFeed) !== false) && (yfym_optionGET('yfymp_compare_value', $numFeed) !== '')) {
		$yfymp_compare_value = yfym_optionGET('yfymp_compare_value', $numFeed);
		$yfymp_compare = yfym_optionGET('yfymp_compare', $numFeed);			 
		if ($yfymp_compare == '>=') {
			if ($price_yml < $yfymp_compare_value) {return $result_yml;}
		} else {
			if ($price_yml >= $yfymp_compare_value) {return $result_yml;}
		}
	}
 }
 // пропуск товаров, которых нет в наличии
 $yfym_skip_missing_products = yfym_optionGET('yfym_skip_missing_products', $numFeed);
 yfym_error_log('FEED № '.$numFeed.'; $yfym_skip_missing_products = '.$yfym_skip_missing_products.'; gettype = '.gettype($yfym_skip_missing_products).'; Файл: offer.php; Строка: '.__LINE__, 0);
 if ($yfym_skip_missing_products === 'on') {
	if ($product->is_in_stock() == false) {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к нет в наличии; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml;}
 }		  

 // пропускаем товары на предзаказ
 $skip_backorders_products = yfym_optionGET('yfym_skip_backorders_products', $numFeed);
 if ($skip_backorders_products === 'on') {
	if ($product->get_manage_stock() == true) { // включено управление запасом  
		if (($product->get_stock_quantity() < 1) && ($product->get_backorders() !== 'no')) {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к запрещен предзаказ и включено управление запасом; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml; /*continue;*/}
	} else {
		if ($product->get_stock_status() !== 'instock') {yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к запрещен предзаказ; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml; /*continue;*/}
	}
 }  

 // убираем default.png из фида
 $no_default_png_products = yfym_optionGET('yfym_no_default_png_products', $numFeed);
 if (($no_default_png_products === 'on') && (!has_post_thumbnail($postId))) {$picture_yml = '';} else {
	$thumb_id = get_post_thumbnail_id($postId);
	$thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);	
	$thumb_yml = $thumb_url[0]; /* урл оригинал миниатюры товара */
	$picture_yml = '<picture>'.deleteGET($thumb_yml).'</picture>'.PHP_EOL;
 }
 $picture_yml = apply_filters('yfym_pic_simple_offer_filter', $picture_yml, $product, $numFeed);

 // пропускаем товары без картинок
 $yfym_skip_products_without_pic = yfym_optionGET('yfym_skip_products_without_pic', $numFeed); 
 if (($yfym_skip_products_without_pic === 'on') && ($picture_yml == '')) {	  
	yfym_error_log('FEED № '.$numFeed.'; Товар с postId = '.$postId.' пропущен т.к нет картинки даже в галерее; Файл: offer.php; Строка: '.__LINE__, 0); return $result_yml; /*continue;*/  
 }
		  
 do_action('yfym_before_simple_offer');
		  
 /* Обычный товар */
 if ($product->get_manage_stock() == true) { // включено управление запасом
	if ($product->get_stock_quantity() > 0) {
		$available = 'true';
	} else {
		$available = 'false';
	}
 } else { // отключено управление запасом
	if ($product->is_in_stock() == true) {$available = 'true';} else {$available = 'false';}
 }
		  		  
 $offer_type = '';
 $offer_type = apply_filters('yfym_offer_type_filter', $offer_type, $catpostid, $numFeed);

 /* с версии 2.1.2 */ 
 $append_offer_tag = apply_filters('yfym_append_offer_tag_filter', $append_offer_tag, $product, $numFeed);
 $result_yml .= '<offer '.$offer_type.' id="'.$postId.'" available="'.$available.'" '.$append_offer_tag.'>'.PHP_EOL;
 do_action('yfym_prepend_simple_offer');
 $params_arr = unserialize(yfym_optionGET('yfym_params_arr', $numFeed));		  
 if (!empty($params_arr)) {		
	$attributes = $product->get_attributes();				
	foreach ($attributes as $param) {
		 // проверка на вариативность атрибута не нужна
		 $param_val = $product->get_attribute(wc_attribute_taxonomy_name_by_id($param->get_id()));		
		 // если этот параметр не нужно выгружать - пропускаем
		 $variation_id_string = (string)$param->get_id(); // важно, т.к. в настройках id как строки
		 if (!in_array($variation_id_string, $params_arr, true)) {continue;}
		 $param_name = wc_attribute_label(wc_attribute_taxonomy_name_by_id($param->get_id()));
		 // если пустое имя атрибута или значение - пропускаем
		 if (empty($param_name) || empty($param_val)) {continue;}
		 $result_yml .= '<param name="'.$param_name.'">'.ucfirst(urldecode($param_val)).'</param>'.PHP_EOL;
		}
	}
		  			
 $result_yml .= "<name>".htmlspecialchars($result_yml_name)."</name>".PHP_EOL;
 $yfym_enable_auto_discounts = yfym_optionGET('yfym_enable_auto_discounts', $numFeed);
 if ($yfym_enable_auto_discounts === 'on') {
	$result_yml .= "<enable_auto_discounts>yes</enable_auto_discounts>".PHP_EOL;
 }			
 // описание
 $result_yml .= $result_yml_desc;
 
 $result_yml .= $picture_yml;  
		   
 $result_url = htmlspecialchars(get_permalink($product->get_id())); // урл товара
 $result_url = apply_filters('yfym_url_filter', $result_url, $product, $CurCategoryId, $numFeed);
 /* с версии 2.0.12 в фильтр добавлен параметр $CurCategoryId */
 
 $result_yml .= "<url>".$result_url."</url>".PHP_EOL;

 $price_yml = apply_filters('yfym_simple_price_yml_filter', $price_yml, $product, $numFeed); /* с версии 3.1.0 */ 
 $yfym_price_from = yfym_optionGET('yfym_price_from', $numFeed);
 if ($yfym_price_from === 'yes') {
	$result_yml .= "<price from='true'>".$price_yml."</price>".PHP_EOL;
 } else {
	$result_yml .= "<price>".$price_yml."</price>".PHP_EOL;
 }
 // старая цена
 $yfym_oldprice = yfym_optionGET('yfym_oldprice', $numFeed);
 if ($yfym_oldprice === 'yes') {
	$sale_price = (int)$product->get_sale_price();
	$price_yml = (int)$price_yml;
	if ($sale_price > 0) {
		if ($price_yml === $sale_price) {		
			$oldprice_yml = $product->get_regular_price();
			$result_yml .= "<oldprice>".$oldprice_yml."</oldprice>".PHP_EOL;
		}
	}
 }
		  
 $result_yml .= '<currencyId>'.$currencyId_yml.'</currencyId>'.PHP_EOL;		  
		  
 // штрихкод			 
 $yfym_barcode = yfym_optionGET('yfym_barcode', $numFeed);
 if ($yfym_barcode !== 'none') {
	switch ($yfym_barcode) { /* off, sku, или id */
		case "off":	
			// выгружать штрихкод нет нужды
		break; 
		case "sku":
		// выгружать из артикула
		$sku_yml = $product->get_sku();
		if (!empty($sku_yml)) {
			$result_yml .= "<barcode>".$sku_yml."</barcode>".PHP_EOL;
		}			
		break;
		default:
			$yfym_barcode = (int)$yfym_barcode;
			$yfym_barcode_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($yfym_barcode));				
			if (!empty($yfym_barcode_yml)) {
				$result_yml .= '<barcode>'.urldecode($yfym_barcode_yml).'</barcode>'.PHP_EOL;
			}
	}
 }			  

 $weight_yml = $product->get_weight(); // вес
 if (!empty($weight_yml)) {
	$weight_yml = round(wc_get_weight($weight_yml, 'kg'), 3);
	$result_yml .= "<weight>".$weight_yml."</weight>".PHP_EOL;
 }

 /*$dimensions = $product->get_dimensions();
 if (!empty($dimensions)) {*/
 $dimensions = wc_format_dimensions($product->get_dimensions(false));
 if ($product->has_dimensions()) {
	$length_yml = $product->get_length();
	if (!empty($length_yml)) {$length_yml = round(wc_get_dimension($length_yml, 'cm'), 3);}
	   
	$width_yml = $product->get_width();
	if (!empty($length_yml)) {$width_yml = round(wc_get_dimension($width_yml, 'cm'), 3);}
	   
	$height_yml = $product->get_height();
	if (!empty($length_yml)) {$height_yml = round(wc_get_dimension($height_yml, 'cm'), 3);}		  
		   
	if (($length_yml > 0) && ($width_yml > 0) && ($height_yml > 0)) {
		$result_yml .= '<dimensions>'.$length_yml.'/'.$width_yml.'/'.$height_yml.'</dimensions>'.PHP_EOL;
	}
 }

 $expiry = yfym_optionGET('yfym_expiry', $numFeed);
 if (!empty($expiry) && $expiry !== 'off') {
	$expiry = (int)$expiry;
	$expiry_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($expiry));
	if (!empty($expiry_yml)) {	
		$result_yml .= "<expiry>".ucfirst(urldecode($expiry_yml))."</expiry>".PHP_EOL;		
	}
 }
 $age = yfym_optionGET('yfym_age', $numFeed);
 if (!empty($age) && $age !== 'off') {	
	$age = (int)$age;
	$age_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($age));
	if (!empty($age_yml)) {	
		$result_yml .= "<age>".ucfirst(urldecode($age_yml))."</age>".PHP_EOL;		
	}
 }
 $downloadable = yfym_optionGET('yfym_downloadable', $numFeed);
 if (!empty($downloadable) && $downloadable !== 'off') {
	if ($product->is_downloadable('yes')) {
		$result_yml .= "<downloadable>true</downloadable>".PHP_EOL;	
	} else {
		$result_yml .= "<downloadable>false</downloadable>".PHP_EOL;							
	}
 }
		  
 $sales_notes_cat = yfym_optionGET('yfym_sales_notes_cat', $numFeed);
 if (!empty($sales_notes_cat) && $sales_notes_cat !== 'off') {
	$sales_notes_cat = (int)$sales_notes_cat;
	$sales_notes_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($sales_notes_cat));
	if (!empty($sales_notes_yml)) {	
		$result_yml .= "<sales_notes>".ucfirst(urldecode($sales_notes_yml))."</sales_notes>".PHP_EOL;		
	} else {
		$sales_notes = yfym_optionGET('yfym_sales_notes', $numFeed);
		if (!empty($sales_notes)) {
			$result_yml .= "<sales_notes>$sales_notes</sales_notes>".PHP_EOL;
		}
	}
 }			  
		  
 // страна производитель
 $country_of_origin = yfym_optionGET('yfym_country_of_origin', $numFeed);
 if (!empty($country_of_origin) && $country_of_origin !== 'off') {
	$country_of_origin = (int)$country_of_origin;
	$country_of_origin_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($country_of_origin));
	if (!empty($country_of_origin_yml)) {	
		$result_yml .= "<country_of_origin>".ucfirst(urldecode($country_of_origin_yml))."</country_of_origin>".PHP_EOL;		
	}				
 }
 
 // гарантия
 $manufacturer_warranty = yfym_optionGET('yfym_manufacturer_warranty', $numFeed);
 if (!empty($manufacturer_warranty) && $manufacturer_warranty !== 'off') {	
	if ($manufacturer_warranty === 'alltrue') {
		$result_yml .= "<manufacturer_warranty>true</manufacturer_warranty>".PHP_EOL;
	} else if ($manufacturer_warranty === 'allfalse') {
		$result_yml .= "<manufacturer_warranty>false</manufacturer_warranty>".PHP_EOL;
	} else {
	 $manufacturer_warranty = (int)$manufacturer_warranty;		
	 $manufacturer_warranty_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($manufacturer_warranty));
	 if (!empty($manufacturer_warranty_yml)) {	
		$result_yml .= "<manufacturer_warranty>".urldecode($manufacturer_warranty_yml)."</manufacturer_warranty>".PHP_EOL;
	 }
	}					
 }

 $vendor = yfym_optionGET('yfym_vendor', $numFeed);
 if (class_exists('Perfect_Woocommerce_Brands') && $vendor === 'sfpwb') {
	$barnd_terms = get_the_terms($product->get_id(), 'pwb-brand');
	if ($barnd_terms !== false) {
	 foreach($barnd_terms as $barnd_term) {
		$result_yml .= '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
		break;
	 }
	}
 } else if ((is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php')) && ($vendor === 'premmercebrandsplugin')) {
	$barnd_terms = get_the_terms($product->get_id(), 'product_brand');
	if ($barnd_terms !== false) {
	 foreach($barnd_terms as $barnd_term) {
		$result_yml .= '<vendor>'. $barnd_term->name .'</vendor>'.PHP_EOL;
		break;
	 }
	}
 } else { 
	if ($vendor !== 'none') {
		$vendor = (int)$vendor;
		$vendor_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($vendor));
		if (!empty($vendor_yml)) {
			$result_yml .= '<vendor>'.$vendor_yml.'</vendor>'.PHP_EOL;
		}
	}	
 }
 
 $model = yfym_optionGET('yfym_model', $numFeed);
 if ($model !== 'none') {
	$model = (int)$model;
 	$model_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($model));
	if (!empty($model_yml)) {				
		$result_yml .= '<model>'.$model_yml.'</model>'.PHP_EOL;
	}
 }			

 // если offer_type пуст, то можно выгружать vendorCode
 if ($offer_type == '') {
  $yfym_vendorcode = yfym_optionGET('yfym_vendorcode', $numFeed);
  if ($yfym_vendorcode !== 'none') {
	 switch ($yfym_vendorcode) { /* off, sku, или id */
		case "off":	
			// выгружать штрихкод нет нужды
		break; 
		case "sku":
		// выгружать из артикула
		$sku_yml = $product->get_sku();
		if (!empty($sku_yml)) {
			$result_yml .= "<vendorCode>".$sku_yml."</vendorCode>".PHP_EOL;
		}			
		break;
		default:
			$yfym_vendorcode = (int)$yfym_vendorcode;
			$yfym_vendorcode_yml = $product->get_attribute(wc_attribute_taxonomy_name_by_id($yfym_vendorcode));				
			if (!empty($yfym_vendorcode_yml)) {
				$result_yml .= '<vendorCode>'.urldecode($yfym_vendorcode_yml).'</vendorCode>'.PHP_EOL;
			}
	}
  }
 }
		  
 // do_action('yfym_after_sku_simple_offer');
 /* c версии 2.0.8 */
 $result_yml_pickup = apply_filters('yfym_pickup_filter', $result_yml_pickup, $catpostid, $product, $pickup, $numFeed);
 		  
 $result_yml .= $result_yml_store;
 $result_yml .= $result_yml_pickup;
 $result_yml .= $result_yml_delivery;
 $result_yml .= $result_yml_cat; // Категории
		  
 do_action('yfym_append_simple_offer'); 
 $result_yml = apply_filters('yfym_append_simple_offer_filter', $result_yml, $product, $numFeed);

 if ((get_post_meta($postId, 'yfym_cost', true) !== '') && (get_post_meta($postId, 'yfym_days', true) !== '')) {
	$yfym_cost = get_post_meta($postId, 'yfym_cost', true);
	$yfym_days = get_post_meta($postId, 'yfym_days', true);	
	if (get_post_meta($postId, 'yfym_order_before', true) !== '') {
		$yfym_order_before = get_post_meta($postId, 'yfym_order_before', true);
		$yfym_order_before_yml = ' order-before="'.$yfym_order_before.'"';
	} else {
		$yfym_order_before_yml = '';
	}	
	$result_yml .= '<delivery-options>'.PHP_EOL;
	$result_yml .= '<option cost="'.$yfym_cost.'" days="'.$yfym_days.'"'.$yfym_order_before_yml.'/>'.PHP_EOL;
	$result_yml .= '</delivery-options>	'.PHP_EOL;
 }
 
 if ((get_post_meta($postId, 'yfym_pickup_cost', true) !== '') && (get_post_meta($postId, 'yfym_pickup_days', true) !== '')) {
	$yfym_pickup_cost = get_post_meta($postId, 'yfym_pickup_cost', true);
	$yfym_pickup_days = get_post_meta($postId, 'yfym_pickup_days', true);	
	if (get_post_meta($postId, 'yfym_pickup_order_before', true) !== '') {
		$yfym_pickup_order_before = get_post_meta($postId, 'yfym_pickup_order_before', true);
		$yfym_pickup_order_before_yml = ' order-before="'.$yfym_pickup_order_before.'"';
	} else {
		$yfym_pickup_order_before_yml = '';
	}	
	$result_yml .= '<pickup-options>'.PHP_EOL;
	$result_yml .= '<option cost="'.$yfym_pickup_cost.'" days="'.$yfym_pickup_days.'"'.$yfym_pickup_order_before_yml.'/>'.PHP_EOL;
	$result_yml .= '</pickup-options>	'.PHP_EOL;
 }
 
 if ((get_post_meta($postId, 'yfym_condition', true) !== '') && (get_post_meta($postId, 'yfym_condition', true) !== 'off') && (get_post_meta($postId, 'yfym_reason', true) !== '')) {
	$yfym_condition = get_post_meta($postId, 'yfym_condition', true);
	$yfym_reason = get_post_meta($postId, 'yfym_reason', true);	
	$result_yml .= '<condition type="'.$yfym_condition.'">'.PHP_EOL;
		$result_yml .= '<reason>'.$yfym_reason.'</reason>'.PHP_EOL;
	$result_yml .= '</condition>'.PHP_EOL;	
 } 
 
 if ((get_post_meta($postId, 'yfym_credit_template', true) !== '') && (get_post_meta($postId, 'yfym_credit_template', true) !== '')) {
	$yfym_credit_template = get_post_meta($postId, 'yfym_credit_template', true);
	$result_yml .= '<credit-template id="'.$yfym_credit_template.'"/>'.PHP_EOL;
 } 
 $result_yml .= '</offer>'.PHP_EOL;
		  
 do_action('yfym_after_simple_offer');

 $ids_in_yml .= $postId.';'.$postId.';'.$price_yml.';'.$CurCategoryId.PHP_EOL;
 
 return array($result_yml, $ids_in_yml);
} // end function yfym_unit($postId) {
?>