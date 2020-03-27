<?php if (!defined('ABSPATH')) {exit;}
/*
* С версии 1.0.0
* Обновлён с версии 3.0.0 
* Добавлен параметр $n
* Записывает или обновляет файл фида.
* Возвращает всегда true
*/
function yfym_write_file($result_yml, $cc, $numFeed = '1') {
 /* $cc = 'w+' или 'a'; */	 
 yfym_error_log('FEED № '.$numFeed.'; Стартовала yfym_write_file c параметром cc = '.$cc.'; Файл: functions.php; Строка: '.__LINE__, 0);
 $filename = urldecode(yfym_optionGET('yfym_file_file', $numFeed));
 if ($numFeed === '1') {$prefFeed = '';} else {$prefFeed = $numFeed;}

 if ($filename == '') {	
	$upload_dir = (object)wp_get_upload_dir(); // $upload_dir->basedir
	$filename = $upload_dir->basedir.$prefFeed."feed-yml-0-tmp.xml"; // $upload_dir->path
 }
		
 // if ((validate_file($filename) === 0)&&(file_exists($filename))) {
 if (file_exists($filename)) {
	// файл есть
	if (!$handle = fopen($filename, $cc)) {
		yfym_error_log('FEED № '.$numFeed.'; Не могу открыть файл '.$filename.'; Файл: functions.php; Строка: '.__LINE__, 0);
		yfym_errors_log('FEED № '.$numFeed.'; Не могу открыть файл '.$filename.'; Файл: functions.php; Строка: '.__LINE__, 0);
	}
	if (fwrite($handle, $result_yml) === FALSE) {
		yfym_error_log('FEED № '.$numFeed.'; Не могу произвести запись в файл '.$handle.'; Файл: functions.php; Строка: '.__LINE__, 0);
		yfym_errors_log('FEED № '.$numFeed.'; Не могу произвести запись в файл '.$handle.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		yfym_error_log('FEED № '.$numFeed.'; Ура! Записали; Файл: Файл: functions.php; Строка: '.__LINE__, 0);
		yfym_error_log($filename, 0);
		return true;
	}
	fclose($handle);
 } else {
	yfym_error_log('FEED № '.$numFeed.'; Файла $filename = '.$filename.' еще нет. Файл: functions.php; Строка: '.__LINE__, 0);
	// файла еще нет
	// попытаемся создать файл
	if (is_multisite()) {
		$upload = wp_upload_bits($prefFeed.'feed-yml-'.get_current_blog_id().'-tmp.xml', null, $result_yml ); // загружаем shop2_295221-yml в папку загрузок
	} else {
		$upload = wp_upload_bits($prefFeed.'feed-yml-0-tmp.xml', null, $result_yml ); // загружаем shop2_295221-yml в папку загрузок
	}
	/*
	*	для работы с csv или xml требуется в плагине разрешить загрузку таких файлов
	*	$upload['file'] => '/var/www/wordpress/wp-content/uploads/2010/03/feed-yml.xml', // путь
	*	$upload['url'] => 'http://site.ru/wp-content/uploads/2010/03/feed-yml.xml', // урл
	*	$upload['error'] => false, // сюда записывается сообщение об ошибке в случае ошибки
	*/
	// проверим получилась ли запись
	if ($upload['error']) {
		yfym_error_log('FEED № '.$numFeed.'; Запись вызвала ошибку: '. $upload['error'].'; Файл: functions.php; Строка: '.__LINE__, 0);
		$err = 'FEED № '.$numFeed.'; Запись вызвала ошибку: '. $upload['error'].'; Файл: functions.php; Строка: '.__LINE__ ;
		yfym_errors_log($err);
	} else {
		yfym_optionUPD('yfym_file_file', urlencode($upload['file']), $numFeed);
		yfym_error_log('FEED № '.$numFeed.'; Запись удалась! Путь файла: '. $upload['file'] .'; УРЛ файла: '. $upload['url'], 0);
		return true;
	}		
 }
}
/*
* С версии 1.2
* Перименовывает временный файл фида в основной.
* Возвращает false/true
*/
function yfym_rename_file($numFeed = '1') {
 yfym_error_log('FEED № '.$numFeed.'; Cтартовала yfym_rename_file; Файл: functions.php; Строка: '.__LINE__, 0);	
 if ($numFeed === '1') {$prefFeed = '';} else {$prefFeed = $numFeed;}	
 /* Перименовывает временный файл в основной. Возвращает true/false */
 if (is_multisite()) {
	$upload_dir = (object)wp_get_upload_dir();
	$filenamenew = $upload_dir->basedir."/".$prefFeed."feed-yml-".get_current_blog_id().".xml";
	$filenamenewurl = $upload_dir->baseurl."/".$prefFeed."/feed-yml-".get_current_blog_id().".xml";		
	// $filenamenew = BLOGUPLOADDIR."feed-yml-".get_current_blog_id().".xml";
	// надо придумать как поулчить урл загрузок конкретного блога
 } else {
	$upload_dir = (object)wp_get_upload_dir();
	/*
	*   'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
	*	'url'     => 'http://site.ru/wp-content/uploads/2016/04',
	*	'subdir'  => '/2016/04',
	*	'basedir' => '/home/site.ru/public_html/wp-content/uploads',
	*	'baseurl' => 'http://site.ru/wp-content/uploads',
	*	'error'   => false,
	*/
	$filenamenew = $upload_dir->basedir."/".$prefFeed."feed-yml-0.xml";
	$filenamenewurl = $upload_dir->baseurl."/".$prefFeed."feed-yml-0.xml";
 }
 $filenameold = urldecode(yfym_optionGET('yfym_file_file', $numFeed));

 yfym_error_log('FEED № '.$numFeed.'; $filenameold = '.$filenameold.'; Файл: functions.php; Строка: '.__LINE__, 0);
 yfym_error_log('FEED № '.$numFeed.'; $filenamenew = '.$filenamenew.'; Файл: functions.php; Строка: '.__LINE__, 0);

 if (rename($filenameold, $filenamenew) === FALSE) {
	yfym_error_log('FEED № '.$numFeed.'; Не могу переименовать файл из '.$filenameold.' в '.$filenamenew.'! Файл: functions.php; Строка: '.__LINE__, 0);
	return false;
 } else {
	yfym_optionUPD('yfym_file_url', urlencode($filenamenewurl), $numFeed);
	yfym_error_log('FEED № '.$numFeed.'; Файл переименован! Файл: functions.php; Строка: '.__LINE__, 0);
	return true;
 }
}
/*
* С версии 1.2.5
* Возвращает URL без get-параметров или возвращаем только get-параметры
*/	
function deleteGET($url, $whot = 'url') {
 $url = str_replace("&amp;", "&", $url); // Заменяем сущности на амперсанд, если требуется
 list($url_part, $get_part) = array_pad(explode("?", $url), 2, ""); // Разбиваем URL на 2 части: до знака ? и после
 if ($whot == 'url') {
	return $url_part; // Возвращаем URL без get-параметров (до знака вопроса)
 } else if ($whot == 'get') {
	return $get_part; // Возвращаем get-параметры (без знака вопроса)
 } else {
	return false;
 }
}
/*
* С версии 1.3.3
* Записывает текст ошибки, чтобы потом можно было отправить в отчет
*/
function yfym_errors_log($message) {
 if (is_multisite()) {
	update_blog_option(get_current_blog_id(), 'yfym_errors', $message);
 } else {
	update_option('yfym_errors', $message);
 }
}
/*
* С версии 1.4.2
* Возвращает версию Woocommerce (string) или (null)
*/ 
function yfym_get_woo_version_number() {
 // If get_plugins() isn't available, require it
 if (!function_exists('get_plugins')) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php');
 }
 // Create the plugins folder and file variables
 $plugin_folder = get_plugins('/' . 'woocommerce');
 $plugin_file = 'woocommerce.php';
	
 // If the plugin version number is set, return it 
 if (isset( $plugin_folder[$plugin_file]['Version'] ) ) {
	return $plugin_folder[$plugin_file]['Version'];
 } else {	
	return NULL;
 }
}
/*
* С версии 1.4.6
* Возвращает дерево таксономий, обернутое в <option></option>
*/
function yfym_cat_tree($TermName='', $termID, $value_arr, $separator='', $parent_shown=true) {
 /* 
 * $value_arr - массив id отмеченных ранее select-ов
 */
 $result = '';
 $args = 'hierarchical=1&taxonomy='.$TermName.'&hide_empty=0&orderby=id&parent=';
 if ($parent_shown) {
	$term = get_term($termID , $TermName); 
	$selected = '';
	if (!empty($value_arr)) {
	 foreach ($value_arr as $value) {		
	  if ($value == $term->term_id) {
		$selected = 'selected'; break;
	  }
	 }
	}
	// $result = $separator.$term->name.'('.$term->term_id.')<br/>';
	$result = '<option title="'.$term->name.'; ID: '.$term->term_id.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';		
	$parent_shown = false;
 }
 $separator .= '-';  
 $terms = get_terms($TermName, $args . $termID);
 if (count($terms) > 0) {
	foreach ($terms as $term) {
	 $selected = '';
	 if (!empty($value_arr)) {
	  foreach ($value_arr as $value) {
	   if ($value == $term->term_id) {
		$selected = 'selected'; break;
	   }
	  }
	 }
	 $result .= '<option title="'.$term->name.'; ID: '.$term->term_id.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';
	 // $result .=  $separator.$term->name.'('.$term->term_id.')<br/>';
	 $result .= yfym_cat_tree($TermName, $term->term_id, $value_arr, $separator, $parent_shown);
	}
 }
 return $result; 
}
/*
* @since 3.0.0
* С версии 3.0.0
*
* @param string $optName (require)
* @param string $value (require)
* @param string $n (not require)
*
* @return true/false
* Возвращает то, что может быть результатом add_blog_option, add_option
*/
function yfym_optionADD($optName, $value='', $n='') {
	if ($optName == '') {return false;}
	if ($n === '1') {$n='';}
  	 $optName = $optName.$n;
  	 if (is_multisite()) { 
	  return add_blog_option(get_current_blog_id(), $optName, $value);
  	 } else {
	  return add_option($optName, $value);
   }
}
/*
* @since 3.0.0
* С версии 3.0.0
*
* @param string $optName (require)
* @param string $value (require)
* @param string $n (not require)
*
* @return true/false
* Возвращает то, что может быть результатом update_blog_option, update_option
*/
function yfym_optionUPD($optName, $value='', $n='') {
	if ($optName == '') {return false;}
	if ($n === '1') {$n='';}
	$optName = $optName.$n;
	if (is_multisite()) { 
	  return update_blog_option(get_current_blog_id(), $optName, $value);
	} else {
	  return update_option($optName, $value);
	}
}
/*
* С версии 2.0.0
* С версии 3.0.0 добавлен параметн $n
*
* @param string $optName (require)
* @param string $n (not require)
*
* @return Значение опции или false
* Возвращает то, что может быть результатом get_blog_option, get_option
*/
function yfym_optionGET($optName, $n='') {
   if ($optName == '') {return false;}
   if ($n === '1') {$n='';}
   $optName = $optName.$n;
   if (is_multisite()) { 
	  return get_blog_option(get_current_blog_id(), $optName);
   } else {
	  return get_option($optName);
   }
}
/*
* С версии 3.0.0
*
* @param string $optName (require)
* @param string $n (not require)
*
* @return true/false
* Возвращает то, что может быть результатом delete_blog_option, delete_option
*/
function yfym_optionDEL($optName, $n='') {
   if ($optName == '') {return false;}
   if ($n === '1') {$n='';}   
   $optName = $optName.$n;
   if (is_multisite()) { 
	  return delete_blog_option(get_current_blog_id(), $optName);
   } else {
	  return delete_option($optName);
   }
} 
/*
* С версии 2.0.0
* C версии 3.0.0 добавлена поддержка нескольких фидов
* Создает tmp файл-кэш товара
* С версии 3.0.2 исправлена критическая ошибка
* C версии 3.1.0 добавлен параметр ids_in_yml
*/
function yfym_wf($result_yml, $postId, $numFeed = '1', $ids_in_yml = '') {
 // $numFeed = '1'; // (string) создадим строковую переменную
 /*$allNumFeed = (int)yfym_ALLNUMFEED;
 for ($i = 1; $i<$allNumFeed+1; $i++) {*/
	$upload_dir = (object)wp_get_upload_dir();
	$name_dir = $upload_dir->basedir.'/yfym/feed'.$numFeed;
	if (!is_dir($name_dir)) {
		error_log('WARNING: Папкт $name_dir ='.$name_dir.' нет; Файл: functions.php; Строка: '.__LINE__, 0);
		if (!mkdir($name_dir)) {
			error_log('ERROR: Создать папку $name_dir ='.$name_dir.' не вышло; Файл: functions.php; Строка: '.__LINE__, 0);
		}
	}
	if (is_dir($name_dir)) {
		$filename = $name_dir.'/'.$postId.'.tmp';
		$fp = fopen($filename, "w");
		fwrite($fp, $result_yml); // записываем в файл текст
		fclose($fp); // закрываем

		/* C версии 3.1.0 */
		$filename = $name_dir.'/'.$postId.'-in.tmp';
		$fp = fopen($filename, "w");
		fwrite($fp, $ids_in_yml);
		fclose($fp);
		/* end с версии 3.1.0 */
	} else {
		error_log('ERROR: Нет папки yfym! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	}
	/*$numFeed++;
 }*/
}
/*
* С версии 2.0.0
* Функция склейки/сборки
*/
function yfym_gluing($id_arr, $numFeed = '1') {
 /*	
 * $id_arr[$i]['ID'] - ID товара
 * $id_arr[$i]['post_modified_gmt'] - Время обновления карточки товара
 * global $wpdb;
 * $res = $wpdb->get_results("SELECT ID, post_modified_gmt FROM $wpdb->posts WHERE post_type = 'product' AND post_status = 'publish'");	
 */	
 yfym_error_log('FEED № '.$numFeed.'; Стартовала yfym_gluing; Файл: functions.php; Строка: '.__LINE__, 0);
 if ($numFeed === '1') {$prefFeed = '';} else {$prefFeed = $numFeed;} 
 $upload_dir = (object)wp_get_upload_dir();
 $name_dir = $upload_dir->basedir.'/yfym/feed'.$numFeed;
 if (!is_dir($name_dir)) {
	if (!mkdir($name_dir)) {
		error_log('FEED № '.$numFeed.'; Нет папки yfym! И создать не вышло! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		error_log('FEED № '.$numFeed.'; Создали папку yfym! Файл: functions.php; Строка: '.__LINE__, 0);
	}
 }
 
 $yfym_file_file = urldecode(yfym_optionGET('yfym_file_file', $numFeed));
 $yfym_file_ids_in_yml = urldecode(yfym_optionGET('yfym_file_ids_in_yml', $numFeed));

 $yfym_date_save_set = yfym_optionGET('yfym_date_save_set', $numFeed);
 clearstatcache(); // очищаем кэш дат файлов
 // $prod_id
 foreach ($id_arr as $product) {
	$filename = $name_dir.'/'.$product['ID'].'.tmp';
	$filenameIn = $name_dir.'/'.$product['ID'].'-in.tmp'; /* с версии 3.1.0 */
	yfym_error_log('FEED № '.$numFeed.'; RAM '.round(memory_get_usage()/1024, 1).' Кб. ID товара/файл = '.$product['ID'].'.tmp; Файл: functions.php; Строка: '.__LINE__, 0);
	if (is_file($filename) && is_file($filenameIn)) { // if (file_exists($filename)) {
		$last_upd_file = filemtime($filename); // 1318189167			
		if (($last_upd_file < strtotime($product['post_modified_gmt'])) || ($yfym_date_save_set > $last_upd_file)) {
			// Файл кэша обновлен раньше чем время модификации товара
			// или файл обновлен раньше чем время обновления настроек фида
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Файл кэша '.$filename.' обновлен РАНЬШЕ чем время модификации товара или время сохранения настроек фида! Файл: functions.php; Строка: '.__LINE__, 0);	
			$result_yml_unit = yfym_unit($product['ID'], $numFeed);
			if (is_array($result_yml_unit)) {
				$result_yml = $result_yml_unit[0];
				$ids_in_yml = $result_yml_unit[1];
			} else {
				$result_yml = $result_yml_unit;
				$ids_in_yml = '';
			}	
			yfym_wf($result_yml, $product['ID'], $numFeed, $ids_in_yml);
			file_put_contents($yfym_file_file, $result_yml, FILE_APPEND);			
			file_put_contents($yfym_file_ids_in_yml, $ids_in_yml, FILE_APPEND);
		} else {
			// Файл кэша обновлен позже чем время модификации товара
			// или файл обновлен позже чем время обновления настроек фида
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Файл кэша '.$filename.' обновлен ПОЗЖЕ чем время модификации товара или время сохранения настроек фида; Файл: functions.php; Строка: '.__LINE__, 0);
			yfym_error_log('FEED № '.$numFeed.'; Пристыковываем файл кэша без изменений; Файл: functions.php; Строка: '.__LINE__, 0);
			$result_yml = file_get_contents($filename);
			file_put_contents($yfym_file_file, $result_yml, FILE_APPEND);
			$ids_in_yml = file_get_contents($filenameIn);
			file_put_contents($yfym_file_ids_in_yml, $ids_in_yml, FILE_APPEND);
		}
	} else { // Файла нет
		yfym_error_log('FEED № '.$numFeed.'; NOTICE: Файла кэша товара '.$filename.' ещё нет! Создаем... Файл: functions.php; Строка: '.__LINE__, 0);		
		$result_yml_unit = yfym_unit($product['ID'], $numFeed);
		if (is_array($result_yml_unit)) {
			$result_yml = $result_yml_unit[0];
			$ids_in_yml = $result_yml_unit[1];
		} else {
			$result_yml = $result_yml_unit;
			$ids_in_yml = '';
		}
		yfym_wf($result_yml, $product['ID'], $numFeed, $ids_in_yml);
		yfym_error_log('FEED № '.$numFeed.'; Создали! Файл: functions.php; Строка: '.__LINE__, 0);
		file_put_contents($yfym_file_file, $result_yml, FILE_APPEND);
		file_put_contents($yfym_file_ids_in_yml, $ids_in_yml, FILE_APPEND);
	}
 }
} // end function yfym_gluing()
/*
* С версии 2.0.0
* Функция склейки
*/
function yfym_onlygluing($numFeed = '1') {
 yfym_error_log('FEED № '.$numFeed.'; NOTICE: Стартовала yfym_onlygluing; Файл: functions.php; Строка: '.__LINE__, 0); 	
 do_action('yfym_before_construct', 'cache');
 $result_yml = yfym_feed_header($numFeed);
 /* создаем файл или перезаписываем старый удалив содержимое */
 $result = yfym_write_file($result_yml, 'w+', $numFeed);
 if ($result !== true) {
	yfym_error_log('FEED № '.$numFeed.'; yfym_write_file вернула ошибку! $result ='.$result.'; Файл: functions.php; Строка: '.__LINE__, 0);
 } 
 
 yfym_optionUPD('yfym_status_sborki', '-1', $numFeed); 
 $whot_export = yfym_optionGET('yfym_whot_export', $numFeed);

 $result_yml = '';
 $step_export = -1;
 $prod_id_arr = array(); 
 
 if ($whot_export === 'vygruzhat') {
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $step_export, // сколько выводить товаров
		// 'offset' => $offset,
		'relation' => 'AND',
		'fields'  => 'ids',
		'meta_query' => array(
			array(
				'key' => 'vygruzhat',
				'value' => 'on'
			)
		)
	);	
 } else { //  if ($whot_export == 'all' || $whot_export == 'simple')
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $step_export, // сколько выводить товаров
		// 'offset' => $offset,
		'relation' => 'AND',
		'fields'  => 'ids'
	);
 }

 $args = apply_filters('yfym_query_arg_filter', $args, $numFeed);
 yfym_error_log('FEED № '.$numFeed.'; NOTICE: yfym_onlygluing до запуска WP_Query RAM '.round(memory_get_usage()/1024, 1) . ' Кб; Файл: functions.php; Строка: '.__LINE__, 0); 
 $featured_query = new WP_Query($args);
 yfym_error_log('FEED № '.$numFeed.'; NOTICE: yfym_onlygluing после запуска WP_Query RAM '.round(memory_get_usage()/1024, 1) . ' Кб; Файл: functions.php; Строка: '.__LINE__, 0); 
 
 global $wpdb;
 if ($featured_query->have_posts()) { 
	for ($i = 0; $i < count($featured_query->posts); $i++) {
		/*	
		*	если не юзаем 'fields'  => 'ids'
		*	$prod_id_arr[$i]['ID'] = $featured_query->posts[$i]->ID;
		*	$prod_id_arr[$i]['post_modified_gmt'] = $featured_query->posts[$i]->post_modified_gmt;
		*/
		$curID = $featured_query->posts[$i];
		$prod_id_arr[$i]['ID'] = $curID;

		$res = $wpdb->get_results("SELECT post_modified_gmt FROM $wpdb->posts WHERE id=$curID", ARRAY_A);
		$prod_id_arr[$i]['post_modified_gmt'] = $res[0]['post_modified_gmt']; 	
		// get_post_modified_time('Y-m-j H:i:s', true, $featured_query->posts[$i]);
	}
	wp_reset_query(); /* Remember to reset */
	unset($featured_query); // чутка освободим память
 }
 if (!empty($prod_id_arr)) {
	yfym_error_log('FEED № '.$numFeed.'; NOTICE: yfym_onlygluing передала управление yfym_gluing; Файл: functions.php; Строка: '.__LINE__, 0);
	yfym_gluing($prod_id_arr, $numFeed);
 }
 
 // если постов нет, пишем концовку файла
 $result_yml = "</offers>". PHP_EOL; 
 $result_yml = apply_filters('yfym_after_offers_filter', $result_yml, $numFeed);
 $result_yml .= "</shop>". PHP_EOL ."</yml_catalog>";
 /* создаем файл или перезаписываем старый удалив содержимое */
 $result = yfym_write_file($result_yml, 'a', $numFeed);
 yfym_rename_file($numFeed);	 
 // выставляем статус сборки в "готово"
 $status_sborki = -1;
 if ($result == true) {
	yfym_optionGET('yfym_status_sborki', $status_sborki, $numFeed);	
	// останавливаем крон сборки
	wp_clear_scheduled_hook('yfym_cron_sborki');
	do_action('yfym_after_construct', 'cache');
 } else {
	yfym_error_log('FEED № '.$numFeed.'; yfym_write_file вернула ошибку! Я не смог записать концовку файла... $result ='.$result.'; Файл: functions.php; Строка: '.__LINE__, 0);
	do_action('yfym_after_construct', 'false');
 }
} // end function yfym_onlygluing()
/*
* С версии 2.0.0
* Записывает файл логов /wp-content/uploads/yfym/yfym.log
*/
function yfym_error_log($text, $i) {
 // $yfym_keeplogs = yfym_optionGET('yfym_keeplogs');	
 if (yfym_KEEPLOGS !== 'on') {return;}
 $upload_dir = (object)wp_get_upload_dir();
 $name_dir = $upload_dir->basedir."/yfym";
 // подготовим массив для записи в файл логов
 if (is_array($text)) {$r = yfym_array_to_log($text); unset($text); $text = $r;}
 if (is_dir($name_dir)) {
	$filename = $name_dir.'/yfym.log';
	file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);		
 } else {
	if (!mkdir($name_dir)) {
		error_log('Нет папки yfym! И создать не вышло! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
	} else {
		error_log('Создали папку yfym!; Файл: functions.php; Строка: '.__LINE__, 0);
		$filename = $name_dir.'/yfym.log';
		file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);
	}
 } 
 return;
}
/*
* С версии 2.1.0
* Позволяте писать в логи массив /wp-content/uploads/yfym/yfym.log
*/
function yfym_array_to_log($text, $i=0, $res = '') {
 $tab = ''; for ($x = 0; $x<$i; $x++) {$tab = '---'.$tab;}
 if (is_array($text)) { 
  $i++;
  foreach ($text as $key => $value) {
	if (is_array($value)) {	// массив
		$res .= PHP_EOL .$tab."[$key] => ";
		$res .= $tab.yfym_array_to_log($value, $i);
	} else { // не массив
		$res .= PHP_EOL .$tab."[$key] => ". $value;
	}
  }
 } else {
	$res .= PHP_EOL .$tab.$text;
 }
 return $res;
}
/*
* С версии 3.0.0
* получить все атрибуты вукомерца 
*/
function yfym_get_attributes() {
 $result = array();
 $attribute_taxonomies = wc_get_attribute_taxonomies();
 if (count($attribute_taxonomies) > 0) {
	$i = 0;
    foreach($attribute_taxonomies as $one_tax ) {
		/**
		* $one_tax->attribute_id => 6
		* $one_tax->attribute_name] => слаг (на инглише или русском)
		* $one_tax->attribute_label] => Еще один атрибут (это как раз название)
		* $one_tax->attribute_type] => select 
		* $one_tax->attribute_orderby] => menu_order
		* $one_tax->attribute_public] => 0			
		*/
		$result[$i]['id'] = $one_tax->attribute_id;
		$result[$i]['name'] = $one_tax->attribute_label;
		$i++;
    }
 }
 return $result;
}
// клон для работы старых версий PRO
function get_attributes() {
	$result = array();
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if (count($attribute_taxonomies) > 0) {
	   $i = 0;
	   foreach($attribute_taxonomies as $one_tax ) {
		   /**
		   * $one_tax->attribute_id => 6
		   * $one_tax->attribute_name] => слаг (на инглише или русском)
		   * $one_tax->attribute_label] => Еще один атрибут (это как раз название)
		   * $one_tax->attribute_type] => select 
		   * $one_tax->attribute_orderby] => menu_order
		   * $one_tax->attribute_public] => 0			
		   */
		   $result[$i]['id'] = $one_tax->attribute_id;
		   $result[$i]['name'] = $one_tax->attribute_label;
		   $i++;
	   }
	}
	return $result;
}
/*
* С версии 3.1.0
* Создает пустой файл ids_in_yml.tmp или очищает уже имеющийся
*/
function yfym_clear_file_ids_in_yml($numFeed = '1') {
	$yfym_file_ids_in_yml = urldecode(yfym_optionGET('yfym_file_ids_in_yml', $numFeed));
	if (!is_file($yfym_file_ids_in_yml)) {
		yfym_error_log('FEED № '.$numFeed.'; WARNING: Файла c idшниками $yfym_file_ids_in_yml = '.$yfym_file_ids_in_yml.' нет! Создадим пустой; Файл: function.php; Строка: '.__LINE__, 0);
		$yfym_file_ids_in_yml = yfym_NAME_DIR .'/feed'.$numFeed.'/ids_in_yml.tmp';		
		$res = file_put_contents($yfym_file_ids_in_yml, '');
		if ($res !== false) {
			yfym_error_log('FEED № '.$numFeed.'; NOTICE: Файл c idшниками $yfym_file_ids_in_yml = '.$yfym_file_ids_in_yml.' успешно создан; Файл: function.php; Строка: '.__LINE__, 0);
			yfym_optionUPD('yfym_file_ids_in_yml', urlencode($yfym_file_ids_in_yml), $numFeed);
		} else {
			yfym_error_log('FEED № '.$numFeed.'; ERROR: Ошибка создания файла $yfym_file_ids_in_yml = '.$yfym_file_ids_in_yml.'; Файл: function.php; Строка: '.__LINE__, 0);
		}
	} else {
		yfym_error_log('FEED № '.$numFeed.'; NOTICE: Обнуляем файл $yfym_file_ids_in_yml = '.$yfym_file_ids_in_yml.'; Файл: function.php; Строка: '.__LINE__, 0);
		file_put_contents($yfym_file_ids_in_yml, '');
	}
}	 
?>