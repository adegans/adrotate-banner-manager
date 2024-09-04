<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_rand
 Purpose:   Generate a random string
-------------------------------------------------------------*/
function adrotate_rand($length = 8) {
	$available_chars = 'abcdefghijklmnopqrstuvwxyz';

	$result = '';
	$size = strlen($available_chars);
	for($i = 0; $i < $length; $i++) {
		$result .= $available_chars[rand(0, $size - 1)];
	}

	return $result;
}

/*-------------------------------------------------------------
 Name:      adrotate_select_categories
 Purpose:   Create scrolling menu of all categories.
-------------------------------------------------------------*/
function adrotate_select_categories($saved, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($saved)) $saved = explode(',', $saved);
	$categories = get_categories(array('child_of' => $parent, 'parent' => $parent,  'orderby' => 'id', 'order' => 'asc', 'hide_empty' => 0));

	if(!empty($categories)) {
		$output = "";
		if($parent == 0) {
			$output .= "<table width=\"100%\">";
			$output .= "<thead><tr><td class=\"check-column\" style=\"padding: 0px;\"><input type=\"checkbox\" /></td><td style=\"padding: 0px;\">Select All</td></tr></thead>";
			$output .= "<tbody>";
		}
		foreach($categories as $category) {
			if($category->parent > 0) {
				if($category->parent != $child_of) {
					$count = $count + 1;
				}
				$indent = "&nbsp;".str_repeat('-', $count * 2)."&nbsp;";
			} else {
				$indent = "";
			}
			$output .= "<tr>";

			$output .= "<td class=\"check-column\" style=\"padding: 0px;\"><input type=\"checkbox\" name=\"adrotate_categories[]\" value=\"".$category->cat_ID."\"";
			$output .= (in_array($category->cat_ID, $saved)) ? ' checked' : '';
			$output .= "></td><td style=\"padding: 0px;\">".$indent.$category->name." (".$category->category_count.")</td>";

			$output .= "</tr>";
			$output .= adrotate_select_categories($saved, $count, $category->parent, $category->cat_ID);
			$child_of = $parent;
		}
		if($parent == 0) {
			$output .= "</tbody></table>";
		}
		return $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_select_pages
 Purpose:   Create scrolling menu of all pages.
-------------------------------------------------------------*/
function adrotate_select_pages($saved, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($saved)) $saved = explode(',', $saved);
	$pages = get_pages(array('child_of' => $parent, 'parent' => $parent, 'sort_column' => 'ID', 'sort_order' => 'asc'));

	if(!empty($pages)) {
		$output = "";
		if($parent == 0) {
			$output = "<table width=\"100%\">";
			$output .= "<thead><tr><td class=\"check-column\" style=\"padding: 0px;\"><input type=\"checkbox\" /></td><td style=\"padding: 0px;\">Select All</td></tr></thead>";
			$output .= "<tbody>";
		}
		foreach($pages as $page) {
			if($page->post_parent > 0) {
				if($page->post_parent != $child_of) {
					$count = $count + 1;
				}
				$indent = "&nbsp;".str_repeat('-', $count * 2)."&nbsp;";
			} else {
				$indent = "";
			}
			$output .= "<tr>";
			$output .= "<td class=\"check-column\" style=\"padding: 0px;\"><input type=\"checkbox\" name=\"adrotate_pages[]\" value=\"".$page->ID."\"";
			$output .= (in_array($page->ID, $saved)) ? ' checked' : '';
			$output .= "></td><td style=\"padding: 0px;\">".$indent.$page->post_title."</td>";
			$output .= "</tr>";
			$output .= adrotate_select_pages($saved, $count, $page->post_parent, $page->ID);
			$child_of = $parent;
		}
		if($parent == 0) {
			$output .= "</tbody></table>";
		}
		return $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ads
 Purpose:   Initiate evaluations for errors and determine the ad status
-------------------------------------------------------------*/
function adrotate_evaluate_ads() {
	global $wpdb;

	// Fetch ads
	$ads = $wpdb->get_results("SELECT `id` FROM `{$wpdb->prefix}adrotate` WHERE `type` != 'disabled' AND `type` != 'generator' AND `type` != 'a_empty' AND `type` != 'a_error' AND `type` != 'queue' AND `type` != 'reject' AND `type` != 'archived' AND `type` != 'trash' AND `type` != 'empty' ORDER BY `id` ASC;");

	// Determine error states
	$error = $limit = $expired = $expiressoon = $expiresweek = $normal = $unknown = 0;
	foreach($ads as $ad) {
		$result = adrotate_evaluate_ad($ad->id);
		if($result == 'error') {
			$error++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = 'error' WHERE `id` = {$ad->id};");
		}

		if($result == 'limit') {
			$limit++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = 'limit' WHERE `id` = {$ad->id};");
		}

		if($result == 'expired') {
			$expired++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = 'expired' WHERE `id` = {$ad->id};");
		}

		if($result == '2days') {
			$expiressoon++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = '2days' WHERE `id` = {$ad->id};");
		}

		if($result == '7days') {
			$expiresweek++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = '7days' WHERE `id` = {$ad->id};");
		}

		if($result == 'active') {
			$normal++;
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `type` = 'active' WHERE `id` = {$ad->id};");
		}

		if($result == 'unknown') {
			$unknown++;
		}
		unset($ad);
	}

	$result = array('error' => $error, 'limit' => $limit, 'expired' => $expired, 'expiressoon' => $expiressoon, 'expiresweek' => $expiresweek, 'normal' => $normal, 'unknown' => $unknown);
	update_option('adrotate_advert_status', $result);
	unset($ads, $result);
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ad
 Purpose:   Evaluates ads for errors
-------------------------------------------------------------*/
function adrotate_evaluate_ad($ad_id) {
	global $wpdb, $adrotate_config;

	$now = current_time('timestamp');
	$in2days = $now + 172800;
	$in7days = $now + 604800;

	// Fetch ad
	$ad = $wpdb->get_row($wpdb->prepare("SELECT `id`, `bannercode`, `tracker`, `imagetype`, `image` FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d;", $ad_id));
	$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '{$ad->id}' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");
	$schedules = $wpdb->get_var("SELECT COUNT(`schedule`) FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = ".$ad->id." AND `group` = 0 AND `user` = 0;");

	$bannercode = stripslashes(htmlspecialchars_decode($ad->bannercode, ENT_QUOTES));
	// Determine error states
	if(
		strlen($bannercode) < 1 // AdCode empty
		OR ((!preg_match_all('/<(a)[^>](.*?)>/i', stripslashes(htmlspecialchars_decode($ad->bannercode, ENT_QUOTES)), $things) OR preg_match_all('/<(ins|script|embed|iframe)[^>](.*?)>/i', stripslashes(htmlspecialchars_decode($ad->bannercode, ENT_QUOTES)), $things)) AND $ad->tracker == 'Y') // Stats active but no valid link/tag present
		OR (preg_match_all('/(%asset%)/i', $bannercode, $things) AND $ad->image == '' AND $ad->imagetype == '') // Did use %image% but didn't select an image
		OR (!preg_match_all('/(%asset%)/i', $bannercode, $things) AND $ad->image != '' AND $ad->imagetype != '') // Didn't use %image% but selected an image
		OR (($ad->image == '' AND $ad->imagetype != '') OR ($ad->image != '' AND $ad->imagetype == '')) // Image and Imagetype mismatch
		OR $schedules == 0 // No Schedules for this ad
	) {
		return 'error';
	} else if(
		$stoptime <= $now // Past the enddate
	){
		return 'expired';
	} else if(
		$stoptime <= $in2days AND $stoptime >= $now // Expires in 2 days
	){
		return '2days';
	} else if(
		$stoptime <= $in7days AND $stoptime >= $now	// Expires in 7 days
	){
		return '7days';
	} else {
		return 'active';
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_color
 Purpose:   Check if ads are expired and set a color for its end date
-------------------------------------------------------------*/
function adrotate_prepare_color($enddate) {
	$now = current_time('timestamp');
	$in2days = $now + 172800;
	$in7days = $now + 604800;

	if($enddate <= $now) {
		return '#CC2900'; // red
	} else if($enddate <= $in2days AND $enddate >= $now) {
		return '#F90'; // orange
	} else if($enddate <= $in7days AND $enddate >= $now) {
		return '#E6B800'; // yellow
	} else {
		return '#009900'; // green
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_is_in_groups
 Purpose:   Build list of groups the advert is in (overview)
-------------------------------------------------------------*/
function adrotate_ad_is_in_groups($id) {
	global $wpdb;

	$output = '';
	$groups	= $wpdb->get_results("
		SELECT
			`{$wpdb->prefix}adrotate_groups`.`name`
		FROM
			`{$wpdb->prefix}adrotate_groups`,
			`{$wpdb->prefix}adrotate_linkmeta`
		WHERE
			`{$wpdb->prefix}adrotate_linkmeta`.`ad` = ".$id."
			AND `{$wpdb->prefix}adrotate_linkmeta`.`group` = `{$wpdb->prefix}adrotate_groups`.`id`
			AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
		;");
	if($groups) {
		foreach($groups as $group) {
			$output .= $group->name.', ';
		}
	}
	$output = rtrim($output, ', ');

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_dropdown_roles
 Purpose:   Returns all roles that can be used
-------------------------------------------------------------*/
function adrotate_dropdown_roles($selected = '') {
	$return = '';

	$editable_roles = get_editable_roles();
	unset($editable_roles['author'], $editable_roles['contributor'], $editable_roles['subscriber']);

	foreach($editable_roles as $role => $details) {
		$name = translate_user_role($details['name']);

		// Preselect specified role.
		if($selected === $role) {
			$return .= "\n\t<option selected=\"selected\" value=\"" . esc_attr($role) . "\">$name</option>";
		} else {
			$return .= "\n\t<option value=\"" . esc_attr($role) . "\">$name</option>";
		}
	}

	echo $return;
}

/*-------------------------------------------------------------
 Name:      adrotate_get_sorted_roles
 Purpose:   Returns all roles and capabilities, sorted by user level. Lowest to highest.
-------------------------------------------------------------*/
function adrotate_get_sorted_roles() {
	global $wp_roles;

	$editable_roles = apply_filters('editable_roles', $wp_roles->roles);
	$sorted = array();

	foreach($editable_roles as $role => $details) {
		$sorted[$details['name']] = get_role($role);
	}

	$sorted = array_reverse($sorted);

	return $sorted;
}

/*-------------------------------------------------------------
 Name:      adrotate_set_capability
 Purpose:   Grant or revoke capabilities to a role and all higher roles
-------------------------------------------------------------*/
function adrotate_set_capability($lowest_role, $capability){
	$check_order = adrotate_get_sorted_roles();
	$add_capability = false;

	foreach($check_order as $role) {
		if($lowest_role == $role->name) $add_capability = true;
		if(empty($role)) continue;
		$add_capability ? $role->add_cap($capability) : $role->remove_cap($capability) ;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_remove_capability
 Purpose:   Remove the $capability from the all roles
-------------------------------------------------------------*/
function adrotate_remove_capability($capability){
	$check_order = adrotate_get_sorted_roles();

	foreach($check_order as $role) {
		$role = get_role($role->name);
		$role->remove_cap($capability);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard_scripts
 Purpose:   Load file uploaded popup
-------------------------------------------------------------*/
function adrotate_dashboard_scripts() {
	$page = (isset($_GET['page'])) ? sanitize_key($_GET['page']) : '';
    if(strpos($page, 'adrotate') !== false) {
		wp_enqueue_style('jquery-ui-datepicker');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('raphael', plugins_url('/library/raphael-min.js', __FILE__), array('jquery'));
		wp_enqueue_script('elycharts', plugins_url('/library/elycharts.min.js', __FILE__), array('jquery', 'raphael'));
		wp_enqueue_script('textatcursor', plugins_url('/library/textatcursor.js', __FILE__), ADROTATE_VERSION);
		wp_enqueue_script('goosebox', plugins_url('/library/goosebox.js', __FILE__), ADROTATE_VERSION);
		wp_enqueue_script('adrotate-datepicker', plugins_url('/library/jquery.datepicker.js', __FILE__), array('jquery'), ADROTATE_VERSION);
	}

	wp_enqueue_style('adrotate-admin-stylesheet', plugins_url('library/dashboard.css', __FILE__));
}

/*-------------------------------------------------------------
 Name:      adrotate_notifications_dashboard
 Purpose:   Notify user of expired banners in the dashboard
-------------------------------------------------------------*/
function adrotate_notifications_dashboard() {
	global $current_user;

	$displayname = (strlen($current_user->user_firstname) > 0) ? $current_user->user_firstname : $current_user->display_name;

	if(current_user_can('adrotate_ad_manage')) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';

		// These only show on AdRotate pages
		if(strpos($page, 'adrotate') !== false) {
			if(isset($_GET['hide']) AND $_GET['hide'] == 0) update_option('adrotate_hide_getpro', current_time('timestamp') + (31 * DAY_IN_SECONDS));
			if(isset($_GET['hide']) AND $_GET['hide'] == 1) update_option('adrotate_hide_review', 1);
			if(isset($_GET['hide']) AND $_GET['hide'] == 2) update_option('adrotate_hide_birthday', current_time('timestamp') + (10 * MONTH_IN_SECONDS));

			// Get AdRotate Pro
			$getpro_banner = get_option('adrotate_hide_getpro');
			if($getpro_banner < current_time('timestamp')) {
				echo "<div class=\"ajdg-notification notice\">";
				echo "	<div class=\"ajdg-notification-logo\" style=\"background-image: url('".plugins_url('/images/notification.png', __FILE__)."');\"><span></span></div>";
				echo "	<div class=\"ajdg-notification-message\">Hello <strong>".$displayname."</strong>. Have you considered upgrading to <strong>AdRotate Professional</strong> yet?<br />Get extra features such as Geo Targeting, Scheduling, Mobile Adverts, More advanced Post Injection, access to premium ticket support and much more...<br />";
				echo " Licenses start as low as &euro; 49 and you can use coupon code <strong>GETADROTATEPRO</strong> to get a 10% discount on any <strong>AdRotate Professional</strong> license!</div>";
				echo "	<div class=\"ajdg-notification-cta\">";
				echo "		<a href=\"".admin_url('admin.php?page=adrotate-pro')."\" class=\"ajdg-notification-act button-primary\">GET ADROTATE PRO</a>";
				echo "		<a href=\"".admin_url('admin.php?page=adrotate')."&hide=0\" class=\"ajdg-notification-dismiss\">Maybe later</a>";
				echo "	</div>";
				echo "</div>";
			}

			// Write a review
			$review_banner = get_option('adrotate_hide_review');
			if($review_banner != 1 AND $review_banner < (current_time('timestamp') - (8 * DAY_IN_SECONDS))) {
				echo "<div class=\"ajdg-notification notice\">";
				echo "	<div class=\"ajdg-notification-logo\" style=\"background-image: url('".plugins_url('/images/notification.png', __FILE__)."');\"><span></span></div>";
				echo "	<div class=\"ajdg-notification-message\">Hello <strong>".$displayname."</strong>! You have been using <strong>AdRotate</strong> for a few days. If you like or found a use for AdRotate Banner Manager, please share <strong>your experience</strong> and write a review. Thanks for being awesome!<br />If you have questions, complaints or something else that does not belong in a review, please use the <a href=\"".admin_url('admin.php?page=adrotate-support')."\">support forum</a>!</div>";
				echo "	<div class=\"ajdg-notification-cta\">";
				echo "		<a href=\"https://wordpress.org/support/view/plugin-reviews/adrotate?rate=5#postform\" class=\"ajdg-notification-act button-primary\">Write Review</a>";
				echo "		<a href=\"".admin_url('admin.php?page=adrotate')."&hide=1\" class=\"ajdg-notification-dismiss\">Maybe later</a>";
				echo "	</div>";
				echo "</div>";
			}

			// Birthday
			$birthday_banner = get_option('adrotate_hide_birthday');
			if($birthday_banner < current_time('timestamp') AND date('M', current_time('timestamp')) == 'Feb') {
				echo "<div class=\"ajdg-notification notice\">";
				echo "	<div class=\"ajdg-notification-logo\" style=\"background-image: url('".plugins_url('/images/birthday.png', __FILE__)."');\"><span></span></div>";
				echo "	<div class=\"ajdg-notification-message\">Hey <strong>".$displayname."</strong>! Did you know it is Arnan his birtyday this month? February 9th to be exact. Wish him a happy birthday via Telegram!<br />Who is Arnan? He made AdRotate for you - Check out his <a href=\"https://www.arnan.me/?mtm_campaign=adrotate&mtm_keyword=birthday_banner\" target=\"_blank\">website</a>.</div>";
				echo "	<div class=\"ajdg-notification-cta\">";
				echo "		<a href=\"https://t.me/arnandegans\" target=\"_blank\" class=\"ajdg-notification-act button-primary goosebox\"><i class=\"icn-tg\"></i>Wish Happy Birthday</a>";
				echo "		<a href=\"".admin_url('admin.php?page=adrotate')."&hide=2\" class=\"ajdg-notification-dismiss\">Not now</a>";
				echo "	</div>";
				echo "</div>";
			}
		}

		// Advert notifications, errors, important stuff
		$adrotate_has_error = adrotate_dashboard_error();
		if($adrotate_has_error) {
			echo "<div class=\"ajdg-notification notice\">";
			echo "	<div class=\"ajdg-notification-logo\" style=\"background-image: url('".plugins_url('/images/notification.png', __FILE__)."');\"><span></span></div>";
			echo "	<div class=\"ajdg-notification-message\"><strong>AdRotate</strong> has detected "._n("one issue that requires", "several issues that require", count($adrotate_has_error), 'adrotate')." ".__("your attention:", 'adrotate')."<br />";
			foreach($adrotate_has_error as $error => $message) {
				echo "&raquo; ".$message."<br />";
			}
			echo "	</div>";
			echo "</div>";
		}
	}

	if(current_user_can('update_plugins')) {
		// Finish update
		$adrotate_db_version = get_option('adrotate_db_version');
		$adrotate_version = get_option('adrotate_version');

		if($adrotate_db_version['current'] < ADROTATE_DB_VERSION OR $adrotate_version['current'] < ADROTATE_VERSION) {
			$plugin_version = get_plugins();
			$plugin_version = $plugin_version['adrotate/adrotate.php']['Version'];

			// Do the update
			adrotate_finish_upgrade();

			// Thank user for updating
			echo "<div class=\"ajdg-notification notice\">";
			echo "	<div class=\"ajdg-notification-logo\" style=\"background-image:url('".plugins_url('/images/notification.png', __FILE__)."');\"><span></span></div>";
			echo "	<div class=\"ajdg-notification-message\">Hi there <strong>".$displayname."</strong>! You have just updated <strong>AdRotate Professional</strong> to version <strong>".$plugin_version."</strong>!<br />Thanks for staying up-to-date! Your <strong>Database and settings</strong> have been updated to the latest version.<br />For an overview of what has changed take a look at the <a href=\"https://ajdg.solutions/support/adrotate-development/?mtm_campaign=adrotate&mtm_keyword=finish_update_notification\" target=\"_blank\">development page</a> and usually there is an article on <a href=\"https://ajdg.solutions/blog/\" target=\"_blank\">the blog</a> with more information as well.</div>";
			echo "</div>";
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_dropdown_folder_contents
 Purpose:   List folder contents for dropdown menu
-------------------------------------------------------------*/
function adrotate_dropdown_folder_contents($base_dir, $extensions = array('jpg', 'jpeg', 'gif', 'png', 'html', 'htm', 'js'), $max_level = 1, $level = 0, $parent = '') {
	$index = array();

	// List the folders and files
	foreach(scandir($base_dir) as $file) {
		if($file == '.' || $file == '..' || $file == '.DS_Store' || $file == 'index.php') continue;

		$dir = $base_dir.'/'.$file;
		if(is_dir($dir)) {
			if($level >= $max_level) continue;
			$index[]= adrotate_dropdown_folder_contents($dir, array('html', 'htm'), $max_level, $level+1, $file);
		} else {
			$fileinfo = pathinfo($file);
			if(in_array($fileinfo['extension'], $extensions)) {
				if($level > 0) $file = $parent.'/'.$file;
				$index[]= $file;
			}
		}
	}
	unset($file);

	// Clean up and sort ascending
	$items = array();
	foreach($index as $key => $item) {
		if(is_array($item)) {
			unset($index[$key]);
			if(count($item) > 0) {
				foreach($item as $k => $v) {
					$index[] = $v;
				}
				unset($k, $v);
			}
		}
	}
	unset($key, $item);
	sort($index);

	return $index;
}

/*-------------------------------------------------------------
 Name:      adrotate_mediapage_folder_contents
 Purpose:   List sub-folder contents for media manager
-------------------------------------------------------------*/
function adrotate_mediapage_folder_contents($asset_folder, $level = 1) {
	$index = $assets = array();

	// Read Banner folder
	if($handle = opendir($asset_folder)) {
	    while(false !== ($file = readdir($handle))) {
	        if($file != '.' AND $file != '..' AND $file != 'index.php' AND $file != '.DS_Store') {
	            $assets[] = $file;
	        }
	    }
	    closedir($handle);

	    if(count($assets) > 0) {
			$new_level = $level + 1;
			$extensions = array('jpg', 'jpeg', 'gif', 'png', 'svg', 'swf', 'flv', 'html', 'htm', 'js');

			foreach($assets as $key => $asset) {
				$fileinfo = pathinfo($asset);
				unset($fileinfo['dirname']);
				if(is_dir($asset_folder.'/'.$asset)) { // Read subfolder
					if($level <= 2) { // Not to deep
						$fileinfo['contents'] = adrotate_mediapage_folder_contents($asset_folder.'/'.$asset, $new_level);
						$index[] = $fileinfo;
					}
				} else { // It's a file
					if(in_array($fileinfo['extension'], $extensions)) {
						$index[] = $fileinfo;
					}
				}
				unset($fileinfo);
			}
			unset($level, $new_level);
		}
	}

	return $index;
}

/*-------------------------------------------------------------
 Name:      adrotate_clean_folder_contents
 Purpose:   Delete unwanted advert assets after uploading a zip file
-------------------------------------------------------------*/
function adrotate_clean_folder_contents($asset_folder) {
	$index = $assets = array();

	// Read asset folder
	if($handle = opendir($asset_folder)) {
		$extensions = array('jpg', 'jpeg', 'gif', 'png', 'svg', 'swf', 'flv', 'html', 'htm', 'js');

	    while(false !== ($asset = readdir($handle))) {
	        if($asset != '.' AND $asset != '..') {
				$fileinfo = pathinfo($asset);
				unset($fileinfo['dirname']);
				if(is_dir($asset_folder.'/'.$asset)) { // Read subfolder
					adrotate_clean_folder_contents($asset_folder.'/'.$asset);
					if(count(scandir($asset_folder.'/'.$asset)) == 2) { // Remove empty folder
						adrotate_unlink($asset, $asset_folder);
					}
				} else { // It's a file
					if(array_key_exists('extension', $fileinfo)) {
						if(!in_array($fileinfo['extension'], $extensions)) {
							adrotate_unlink($asset, $asset_folder);
						}
					}
				}
				unset($fileinfo);
	        }
	    }
	    closedir($handle);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_unlink
 Purpose:   Delete a file or folder from the banners folder
-------------------------------------------------------------*/
function adrotate_unlink($asset, $path = '') {
	global $adrotate_config;

	$access_type = get_filesystem_method();
	if($access_type === 'direct') {
		if($path == '') {
			$path = WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder'].'/'.$asset;
		} else {
			$path = $path.'/'.$asset;
		}

		$credentials = request_filesystem_credentials(site_url().'/wp-admin/', '', false, false, array());

		if(!WP_Filesystem($credentials)) {
			return false;
		}

		global $wp_filesystem;

		if(!is_dir($path)) { // It's a file
			if(unlink($path)) {
				return true;
			} else {
				return false;
			}
		} else { // It's a folder
			if($wp_filesystem->rmdir($path, true)) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_action_links
 Purpose:	Plugin page link
-------------------------------------------------------------*/
function adrotate_action_links($links) {
	$extra_links = array();
	$extra_links['ajdg-adrotate-pro'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/cart/?add-to-cart=1124&mtm_campaign=adrotate&mtm_keyword=action_links', '<strong>Get AdRotate Pro</strong>');
	$extra_links['ajdg-adrotate-more'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/plugins/?mtm_campaign=adrotatepro', 'More plugins');

	return array_merge($extra_links, $links);
}

/*-------------------------------------------------------------
 Name:      adrotate_credits
 Purpose:   Promotional stuff shown throughout the plugin
-------------------------------------------------------------*/
function adrotate_credits() {
	echo "<table class=\"widefat\" style=\"margin-top: 2em\">";

	echo "<thead>";
	echo "<tr valign=\"top\">";
	echo "	<th width=\"70%\"><strong>".__("Get more features with AdRotate Professional", 'adrotate')."</strong></th>";
	echo "	<th><strong>".__("Starting at &euro; 49.00", 'adrotate')." - <a href=\"https://ajdg.solutions/product-category/adrotate-pro/?mtm_campaign=adrotate&mtm_keyword=credits_license\" target=\"_blank\">".__("Compare Licenses", 'adrotate')." &raquo;</a></strong></th>";
	echo "</tr>";
	echo "</thead>";

	echo "<tbody>";
	echo "<tr>";

	echo "<td><a href=\"https://ajdg.solutions/plugins/adrotate-for-wordpress/?mtm_campaign=adrotate&mtm_keyword=credits_license\" target=\"_blank\"><img src=\"".plugins_url('/images/logo-60x60.png', __FILE__)."\" class=\"alignleft pro-image\" /></a><p>".__("<strong>AdRotate Professional</strong> has a lot more to offer for even better advertising management and premium support. Enjoy features like <strong>Geo Targeting</strong>, <strong>Schedules</strong>, more advanced <strong>Post Injection</strong> and much more. Check out the feature comparison tab on any of the product pages to see what AdRotate Pro has to offer for you! When you upgrade to <strong>AdRotate Professional</strong> make sure you use coupon <strong>GETADROTATEPRO</strong> on checkout for 10 percent off on any license.", 'adrotate')." <a href=\"https://ajdg.solutions/product-category/adrotate-pro/?mtm_campaign=adrotate&mtm_keyword=credits_license\" target=\"_blank\">".__("Compare Licenses", 'adrotate')." &raquo;</a></p></td>";

	echo "<td><p><a href=\"https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=adrotate&mtm_keyword=credits_license\" target=\"_blank\"><strong>".__("Get a Single License", 'adrotate')."</strong></a><br /><em>".__("One year of updates for one WordPress website.", 'adrotate')."</em></p>"."<p><a href=\"https://ajdg.solutions/product/adrotate-pro-multi/?mtm_campaign=adrotate&mtm_keyword=credits_license\" target=\"_blank\"><strong>".__("Go big with the Multi License", 'adrotate')."</strong></a><br /><em>".__("One year of updates for up-to five WordPress websites.", 'adrotate')."</em></p></td>";

	echo "</tr>";

	echo "</tbody>";
	echo "</table>";
	echo "<table class=\"widefat\" style=\"margin-top: 2em\">";

	echo "<thead>";
	echo "<tr valign=\"top\">";
	echo "	<th width=\"50%\"><strong>".__("Do you have a question?", 'adrotate')."</strong></th>";
	echo "	<th><strong>".__("Support AdRotate Banner Manager", 'adrotate')."</strong></th>";
	echo "</tr>";
	echo "</thead>";

	echo "<tbody>";
	echo "<tr>";
	echo "<td><a href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/\" title=\"Getting help with AdRotate\"><img src=\"".plugins_url('/images/icon-support.png', __FILE__)."\" alt=\"AdRotate Logo\" width=\"60\" height=\"60\" align=\"left\" style=\"padding:5px;\" /></a><p>".__("If you need help, or have questions about AdRotate, the best and fastest way to get your answer is via the AdRotate support forum. Usually I answer questions the same day, often with a solution in the first answer.", 'adrotate')."</p>"."<p><a href=\"https://ajdg.solutions/support/adrotate-manuals/?mtm_campaign=adrotate&mtm_keyword=credits\" target=\"_blank\" class=\"button-primary\">".__("AdRotate Manuals", 'adrotate')."</a> <a href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/?mtm_campaign=adrotate&mtm_keyword=credits\" target=\"_blank\" class=\"button-primary\">".__("Support Forums", 'adrotate')."</a> <a href=\"https://ajdg.solutions/product/support-ticket/?mtm_campaign=adrotate&mtm_keyword=credits\" target=\"_blank\" class=\"button-secondary\">".__("Buy Support Ticket", 'adrotate')."</a></p></td>";

	echo "<td><a href=\"https://wordpress.org/support/view/plugin-reviews/adrotate?rate=5#postform\" title=\"Review AdRotate for WordPress\"><img src=\"".plugins_url('/images/icon-contact.png', __FILE__)."\" alt=\"AdRotate Banner Manager\" width=\"60\" height=\"60\" align=\"left\" style=\"padding:5px;\" /></a><p>".__("Arnan needs your help. Please consider writing a review or sharing AdRotate in Social media if you find the plugin useful. Writing a review and sharing AdRotate on social media costs you nothing but doing so is super helpful.", 'adrotate')."</p>"."<p><a class=\"button-primary\" target=\"_blank\" href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/reviews/?rate=5#new-post\">".__("Write review on WordPress.org", 'adrotate')."</a> <a class=\"button\" target=\"_blank\" href=\"https://ajdg.solutions/product/adrotate-banner-manager/#tab-reviews\">".__("Write review on ajdg.solutions", 'adrotate')."</a></p></td>";

	echo "</tr>";

	echo "</tbody>";
	echo "</table>";
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard_error
 Purpose:   Show errors for problems in using AdRotate
-------------------------------------------------------------*/
function adrotate_dashboard_error() {
	global $adrotate_config;

	// Adverts
	$status = get_option('adrotate_advert_status');
	$adrotate_notifications	= get_option("adrotate_notifications");

	if($adrotate_notifications['notification_dash'] == "Y") {
		if($status['expired'] > 0 AND $adrotate_notifications['notification_dash_expired'] == "Y") {
			$error['advert_expired'] = sprintf(_n("One advert is expired.", "%s adverts expired!", $status['expired'], 'adrotate'), $status['expired'])." <a href=\"".admin_url('admin.php?page=adrotate')."\">".__("Check adverts", 'adrotate')."</a>!";
		}
		if($status['expiressoon'] > 0 AND $adrotate_notifications['notification_dash_soon'] == "Y") {
			$error['advert_soon'] = sprintf(_n("One advert expires soon.", "%s adverts are almost expiring!", $status['expiressoon'], 'adrotate'), $status['expiressoon'])." <a href=\"".admin_url('admin.php?page=adrotate')."\">".__("Check adverts", 'adrotate')."</a>!";
		}
	}
	if($status['error'] > 0) {
		$error['advert_config'] = sprintf(_n("One advert with configuration errors.", "%s adverts have configuration errors!", $status['error'], 'adrotate'), $status['error'])." <a href=\"".admin_url('admin.php?page=adrotate')."\">".__("Check adverts", 'adrotate')."</a>!";
	}

	// Caching
	if($adrotate_config['w3caching'] == "Y" AND !is_plugin_active('w3-total-cache/w3-total-cache.php')) {
		$error['w3tc_not_active'] = __("You have enabled caching support but W3 Total Cache is not active on your site!", 'adrotate')." <a href=\"".admin_url('/admin.php?page=adrotate-settings&tab=misc')."\">".__("Disable W3 Total Cache Support", 'adrotate')."</a>.";
	}
	if($adrotate_config['w3caching'] == "Y" AND !defined('W3TC_DYNAMIC_SECURITY')) {
		$error['w3tc_no_hash'] = __("You have enable caching support but the W3TC_DYNAMIC_SECURITY definition is not set.", 'adrotate')." <a href=\"".admin_url('/admin.php?page=adrotate-settings&tab=misc')."\">".__("How to configure W3 Total Cache", 'adrotate')."</a>.";
	}

	if($adrotate_config['borlabscache'] == "Y" AND !is_plugin_active('borlabs-cache/borlabs-cache.php')) {
		$error['borlabs_not_active'] = __("You have enable caching support but Borlabs Cache is not active on your site!", 'adrotate')." <a href=\"".admin_url('/admin.php?page=adrotate-settings&tab=misc')."\">".__("Disable Borlabs Cache Support", 'adrotate')."</a>.";
	}
	if($adrotate_config['borlabscache'] == "Y" AND is_plugin_active('borlabs-cache/borlabs-cache.php')) {
		$borlabs_config = get_option('BorlabsCacheConfigInactive');
		if($borlabs_config['cacheActivated'] == 'yes' AND strlen($borlabs_config['fragmentCaching']) < 1) {
			$error['borlabs_fragment_error'] = __("You have enabled Borlabs Cache support but Fragment caching is not enabled!", 'adrotate')." <a href=\"".admin_url('/admin.php?page=borlabs-cache-fragments')."\">".__("Enable Fragment Caching", 'adrotate')."</a>.";
		}
		unset($borlabs_config);
	}

	// Misc
	if(!is_writable(WP_CONTENT_DIR."/".$adrotate_config['banner_folder'])) {
		$error['banners_folder'] = __("Your AdRotate Banner folder is not writable or does not exist.", 'adrotate')." <a href=\"https://ajdg.solutions/support/adrotate-manuals/manage-banner-images/\" target=\"_blank\">".__("Set up your banner folder", 'adrotate')."</a>.";
	}
	if(is_dir(WP_PLUGIN_DIR."/adrotate-pro/")) {
		$error['adrotate_exists'] = __("You have AdRotate Professional installed. Please switch to AdRotate Pro! You can delete this plugin after AdRotate Pro is activated.", 'adrotate')." <a href=\"".admin_url('/plugins.php?s=adrotate&plugin_status=all')."\">".__("Switch plugins", 'adrotate')."</a>.";
	}
	if(basename(__DIR__) != 'adrotate' AND basename(__DIR__) != 'adrotate-pro') {
		$error['adrotate_folder_names'] = __("Something is wrong with your installation of AdRotate. Either the plugin is installed twice or your current installation has the wrong folder name. Please install the plugin properly!", 'adrotate').' <a href="https://ajdg.solutions/support/adrotate-manuals/installing-adrotate-on-your-website/" target="_blank">'.__("Installation instructions", 'adrotate').'</a>.';
	}

	$error = (isset($error) AND is_array($error)) ? $error : false;

	return $error;
}

/*-------------------------------------------------------------
 Name:      adrotate_return
 Purpose:   Internal redirects
-------------------------------------------------------------*/
function adrotate_return($page, $status, $args = null) {
	if(strlen($page) > 0 AND ($status > 0 AND $status < 1000)) {
		$defaults = array(
			'status' => $status
		);

		$arguments = wp_parse_args($args, $defaults);
		$redirect = 'admin.php?page=' . $page . '&'.http_build_query($arguments);
	} else {
		$redirect = 'admin.php?page=adrotate&status=1'; // Unexpected error
	}

	wp_redirect($redirect);
}

/*-------------------------------------------------------------
 Name:      adrotate_status
 Purpose:   Internal redirects
-------------------------------------------------------------*/
function adrotate_status($status, $args = null) {
	$defaults = array(
		'ad' => '',
		'group' => '',
		'file' => ''
	);
	$arguments = wp_parse_args($args, $defaults);

	switch($status) {
		// Management messages
		case '200' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert saved", 'adrotate') ."</p></div>";
		break;

		case '201' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Group saved", 'adrotate') ."</p></div>";
		break;

		case '202' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Banner image saved", 'adrotate') ."</p></div>";
		break;

		case '203' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Ad(s) deleted", 'adrotate') ."</p></div>";
		break;

		case '204' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Group deleted", 'adrotate') ."</p></div>";
		break;

		case '206' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Asset(s) deleted", 'adrotate') ."</p></div>";
		break;

		case '207' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Something went wrong deleting the file or folder. Make sure your permissions are in order.", 'adrotate') ."</p></div>";
		break;

		case '208' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert(s) statistics reset", 'adrotate') ."</p></div>";
		break;

		case '209' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert(s) renewed", 'adrotate') ."</p></div>";
		break;

		case '210' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert(s) deactivated", 'adrotate') ."</p></div>";
		break;

		case '211' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert(s) activated", 'adrotate') ."</p></div>";
		break;

		case '213' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Group including the Adverts in it deleted", 'adrotate') ."</p></div>";
		break;

		case '223' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Folder created", 'adrotate') ."</p></div>";
		break;

		case '226' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Advert HTML generated and placed in the AdCode field. Configure your advert below. Do not forget to check all settings and schedule the advert.", 'adrotate') ."</p></div>";
		break;

		// Settings
		case '400' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Settings saved", 'adrotate') ."</p></div>";
		break;

		case '403' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Database optimized", 'adrotate') ."</p></div>";
		break;

		case '404' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Database repaired", 'adrotate') ."</p></div>";
		break;

		case '405' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Adverts evaluated and statuses have been corrected where required", 'adrotate') ."</p></div>";
		break;

		case '406' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Cleanup complete", 'adrotate') ."</p></div>";
		break;

		case '407' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Scheduled tasks reset", 'adrotate') ."</p></div>";
		break;

		case '408' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("If there were any 3rd party plugins interfering with AdRotate they have been disabled", 'adrotate') ."</p></div>";
		break;

		case '409' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Database updated", 'adrotate') ."</p></div>";
		break;

		case '410' :
			echo "<div class=\"ajdg-notification notice\"><div class=\"ajdg-notification-logo\" style=\"background-image: url('".plugins_url('/images/notification.png', __FILE__)."');\"><span></span></div><div class=\"ajdg-notification-message\"><strong>". __("Database and core settings updated", 'adrotate') ."</strong><br />". __("Thanks for updating AdRotate Banner Manager!", 'adrotate') ."<br />". __("If you run into any issues with the new version please send a email through the support dashboard as soon as possible with any errors or symptoms you encounter.", 'adrotate') ."</div></div>";
		break;

		// (all) Error messages
		case '500' :
			echo "<div id=\"message\" class=\"error\"><p>". __("Action prohibited", 'adrotate') ."</p></div>";
		break;

		case '501' :
			echo "<div id=\"message\" class=\"error\"><p>". __("The advert was saved but has an issue which might prevent it from working properly. Review the colored advert.", 'adrotate') ."</p></div>";
		break;

		case '503' :
			echo "<div id=\"message\" class=\"error\"><p>". __("No data found in selected time period", 'adrotate') ."</p></div>";
		break;

		case '504' :
			echo "<div id=\"message\" class=\"error\"><p>". __("Database can only be optimized or cleaned once every hour", 'adrotate') ."</p></div>";
		break;

		case '505' :
			echo "<div id=\"message\" class=\"error\"><p>". __("Form can not be (partially) empty!", 'adrotate') ."</p></div>";
		break;

		case '506' :
			echo "<div id=\"message\" class=\"error\"><p>". __("No file uploaded.", 'adrotate') ."</p></div>";
		break;

		case '509' :
			echo "<div id=\"message\" class=\"updated\"><p>". __("No adverts found.", 'adrotate') ."</p></div>";
		break;

		case '510' :
			echo "<div id=\"message\" class=\"error\"><p>". __("Wrong file type. No file uploaded.", 'adrotate') ."</p></div>";
		break;

		case '511' :
			echo "<div id=\"message\" class=\"error\"><p>". __("No file selected or file is too large.", 'adrotate') ."</p></div>";
		break;

		case '512' :
			echo "<div id=\"message\" class=\"error\"><p>". __("There was an error unzipping the file. Please try again later.", 'adrotate') ."</p></div>";
		break;

		case '513' :
			echo "<div id=\"message\" class=\"error\"><p>". __("The advert hash is not usable or is missing required data. Please copy the hash correctly and try again.", 'adrotate') ."</p></div>";
		break;

		case '514' :
			echo "<div id=\"message\" class=\"error\"><p>". __("The advert hash can not be used on the same site as it originated from or is not a valid hash for importing.", 'adrotate') ."</p></div>";
		break;

		default :
			echo "<div id=\"message\" class=\"updated\"><p>". __("Unexpected error", 'adrotate') ."</p></div>";
		break;
	}

	unset($arguments, $args);
}
?>
