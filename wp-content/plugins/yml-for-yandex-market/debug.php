<?php if (!defined('ABSPATH')) {exit;}
function yfym_debug_page() { 
 if (isset($_REQUEST['yfym_submit_debug_page'])) {
	if (!empty($_POST) && check_admin_referer('yfym_nonce_action','yfym_nonce_field')) {
		if (isset($_POST['yfym_keeplogs'])) {
			yfym_optionUPD('yfym_keeplogs', sanitize_text_field($_POST['yfym_keeplogs']));
		} else {
			yfym_optionUPD('yfym_keeplogs', '0');
		}
		if (isset($_POST['yfym_disable_notices'])) {
			yfym_optionUPD('yfym_disable_notices', sanitize_text_field($_POST['yfym_disable_notices']));
		} else {
			yfym_optionUPD('yfym_disable_notices', '0');
		}
		if (isset($_POST['yfym_enable_five_min'])) {
			yfym_optionUPD('yfym_enable_five_min', sanitize_text_field($_POST['yfym_enable_five_min']));
		} else {
			yfym_optionUPD('yfym_enable_five_min', '0');
		}		
	}
 }	
 $yfym_keeplogs = yfym_optionGET('yfym_keeplogs');
 $yfym_disable_notices = yfym_optionGET('yfym_disable_notices');
 $yfym_enable_five_min = yfym_optionGET('yfym_enable_five_min');
 ?>
 <div class="wrap"><h1><?php _e('Debug page', 'yfym'); ?></h1>
  <div id="dashboard-widgets-wrap"><div id="dashboard-widgets" class="metabox-holder">
  <div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">
     <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">	 
	 <div class="postbox">
	   <div class="inside">
		<h1><?php _e('Logs', 'yfym'); ?></h1>
		<p><?php if ($yfym_keeplogs === 'on') {echo '<strong>'. __("Log-file here", 'yfym').':</strong><br />'. yfym_UPLOAD_DIR .'/yfym.log';	} ?></p>		
		<table class="form-table"><tbody>
		 <tr>
			<th scope="row"><label for="yfym_keeplogs"><?php _e('Keep logs', 'yfym'); ?></label><br />
				<input class="button" id="yfym_submit_clear_logs" type="submit" name="yfym_submit_clear_logs" value="<?php _e('Clear logs', 'yfym'); ?>" />
			</th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_keeplogs" id="yfym_keeplogs" <?php checked($yfym_keeplogs, 'on' ); ?>/><br />
				<span class="description"><?php _e('Do not check this box if you are not a developer', 'yfym'); ?>!</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_disable_notices"><?php _e('Disable notices', 'yfym'); ?></label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_disable_notices" id="yfym_disable_notices" <?php checked($yfym_disable_notices, 'on' ); ?>/><br />
				<span class="description"><?php _e('Disable notices about YML-construct', 'yfym'); ?>!</span>
			</td>
		 </tr>
		 <tr>
			<th scope="row"><label for="yfym_enable_five_min"><?php _e('Enable', 'yfym'); ?> five_min</label></th>
			<td class="overalldesc">
				<input type="checkbox" name="yfym_enable_five_min" id="yfym_enable_five_min" <?php checked($yfym_enable_five_min, 'on' ); ?>/><br />
				<span class="description"><?php _e('Enable the five minute interval for CRON', 'yfym'); ?></span>
			</td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"></td>
		 </tr>		 
		 <tr>
			<th scope="row"><label for="button-primary"></label></th>
			<td class="overalldesc"><?php wp_nonce_field('yfym_nonce_action', 'yfym_nonce_field'); ?><input id="button-primary" class="button-primary" type="submit" name="yfym_submit_debug_page" value="<?php _e( 'Save', 'yfym'); ?>" /><br />
			<span class="description"><?php _e('Click to save the settings', 'yfym'); ?></span></td>
		 </tr>         
        </tbody></table>
       </div>
     </div>
     </form>

	 <div class="postbox">
	  <div class="inside">
	  	<h1><?php _e('Possible problems', 'yfym'); ?></h1>
		  <?php
		  	$possibleProblems = ''; $possibleProblemsCount = 0; $conflictWithPlugins = 0; $conflictWithPluginsList = ''; 
			$check_global_attr_count = wc_get_attribute_taxonomies();
			if (count($check_global_attr_count) < 1) {
				$possibleProblemsCount++;
				$possibleProblems .= '<li>'. __('Your site has no global attributes! This may affect the quality of the YML feed. This can also cause difficulties when setting up the plugin', 'yfym'). '. <a href="https://icopydoc.ru/globalnyj-i-lokalnyj-atributy-v-woocommerce/?utm_source=link&utm_medium=yml-for-yandex-market&utm_campaign=in-plugin&utm_content=settings">'. __('Please read the recommendations', 'yfym'). '</a>.</li>';
			}			
			if (is_plugin_active('snow-storm/snow-storm.php')) {
				$possibleProblemsCount++;
				$conflictWithPlugins++;
				$conflictWithPluginsList .= 'Snow Storm<br/>';
			}
			if (is_plugin_active('email-subscribers/email-subscribers.php')) {
				$possibleProblemsCount++;
				$conflictWithPlugins++;
				$conflictWithPluginsList .= 'Email Subscribers & Newsletters<br/>';
			}
			if ($conflictWithPlugins > 0) {
				$possibleProblemsCount++;
				$possibleProblems .= '<li><p>'. __('Most likely, these plugins negatively affect the operation of', 'yfym'). ' YML for Yandex Market:</p>'.$conflictWithPluginsList.'<p>'. __('If you are a developer of one of the plugins from the list above, please contact me', 'yfym').': <a href="mailto:pt070@yandex.ru">pt070@yandex.ru</a>.</p></li>';
			}
			if ($possibleProblemsCount > 0) {
				echo '<ol>'.$possibleProblems.'</ol>';
			} else {
				echo '<p>'. __('Self-diagnosis functions did not reveal potential problems', 'yfym').'.</p>';
			}
			unset($possibleProblems);
			unset($possibleProblemsCount);
			unset($check_global_attr_count); 
			unset($conflictWithPlugins); 
			unset($conflictWithPluginsList); 
		  ?>
	  </div>
     </div>	
	 <div class="postbox">
	  <div class="inside">
		<h1><?php _e('Reset plugin settings', 'yfym'); ?></h1>
		<p><?php _e('Reset plugin settings can be useful in the event of a problem', 'yfym'); ?>.</p>
		<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('yfym_nonce_action_reset', 'yfym_nonce_field_reset'); ?><input class="button-primary" type="submit" name="yfym_submit_reset" value="<?php _e('Reset plugin settings', 'yfym'); ?>" />	 
		</form>
	  </div>
	 </div>	 
  </div></div>
  <div id="postbox-container-2" class="postbox-container"><div class="meta-box-sortables">
  	<?php do_action('yfym_before_support_project'); ?>

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
} /* end функция страницы debug-а yfym_debug_page */
?>