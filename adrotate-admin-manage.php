<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_generate_input
 Purpose:   Generate advert code based on user input
-------------------------------------------------------------*/
function adrotate_generate_input() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_generate_ad')) {
		$id = '';
		if(isset($_POST['adrotate_id'])) $id = sanitize_key($_POST['adrotate_id']);

		$fullsize_image = $targeturl = $new_window = $portability = $nofollow = $adwidth = $adheight = '';
		if(isset($_POST['adrotate_fullsize_dropdown'])) $fullsize_image = strip_tags(trim($_POST['adrotate_fullsize_dropdown']));
		if(isset($_POST['adrotate_targeturl'])) $targeturl = strip_tags(trim($_POST['adrotate_targeturl']));
		if(isset($_POST['adrotate_newwindow'])) $new_window = strip_tags(trim($_POST['adrotate_newwindow']));
		if(isset($_POST['adrotate_portability'])) $portability = strip_tags(trim($_POST['adrotate_portability']));

		if(current_user_can('adrotate_ad_manage')) {
			if(strlen($portability) == 0) {
				// Fullsize Image & figure out adwidth and adheight
				$fullsize_size = @getimagesize(WP_CONTENT_URL."/".$adrotate_config['banner_folder']."/".$fullsize_image);
				if($fullsize_size){
					$adwidth = " width=\"".$fullsize_size[0]."\"";
					$adheight = " height=\"".$fullsize_size[1]."\"";
				}

				// Open in a new window?
				if(isset($new_window) AND strlen($new_window) != 0) {
					$new_window = " target=\"_blank\"";
				}

				// Determine image settings
				$imagetype = 'dropdown';
				$image = WP_CONTENT_URL."/banners/".$fullsize_image;
				$asset = "<img src=\"%asset%\"".$adwidth.$adheight." />";

				// Generate code
				$bannercode = htmlspecialchars("<a href=\"".$targeturl."\"".$new_window.">".$asset."</a>");

				// Save the advert to the DB
				$wpdb->update($wpdb->prefix.'adrotate', array('bannercode' => $bannercode, 'imagetype' => $imagetype, 'image' => $image), array('id' => $id));
			} else {
				$portability = adrotate_portable_hash('import', $portability);

				// Save the advert to the DB
				$wpdb->update($wpdb->prefix.'adrotate', array('title' => $portability['title'], 'bannercode' => $portability['bannercode'], 'thetime' => $portability['thetime'], 'updated' => current_time('timestamp'), 'author' => $portability['author'],  'imagetype' => $portability['imagetype'], 'image' => $portability['image'], 'tracker' => $portability['tracker'], 'show_everyone' => $portability['show_everyone'], 'desktop' => $portability['desktop'], 'mobile' => $portability['mobile'], 'tablet' => $portability['tablet'], 'os_ios' => $portability['os_ios'], 'os_android' => $portability['os_android'], 'weight' => $portability['weight'], 'autodelete' => $portability['autodelete'], 'budget' => $portability['budget'], 'crate' => $portability['crate'], 'irate' => $portability['irate'], 'state_req' => $portability['state_req'], 'cities' => $portability['cities'], 'states' => $portability['states'], 'countries' => $portability['countries']), array('id' => $id));
			}

			adrotate_return('adrotate', 226, array('view' => 'edit', 'ad'=> $id));
			exit;
		} else {
			adrotate_return('adrotate', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_advert
 Purpose:   Prepare input form on saving new or updated banners
-------------------------------------------------------------*/
function adrotate_insert_advert() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_ad')) {
		$id = $schedule_id = $author = $title = $bannercode = $active = '';
		if(isset($_POST['adrotate_id'])) $id = sanitize_key($_POST['adrotate_id']);
		if(isset($_POST['adrotate_schedule'])) $schedule_id = sanitize_key($_POST['adrotate_schedule']);
		if(isset($_POST['adrotate_username'])) $author = sanitize_key($_POST['adrotate_username']);
		if(isset($_POST['adrotate_title'])) $title = sanitize_text_field($_POST['adrotate_title']);
		if(isset($_POST['adrotate_bannercode'])) $bannercode = trim($_POST['adrotate_bannercode']); // Sanitized later on
		$thetime = current_time('timestamp');
		if(isset($_POST['adrotate_active'])) $active = sanitize_key($_POST['adrotate_active']);

		// Schedules
		$start_date = $start_hour = $start_minute = $end_date = $end_hour = $end_minute = '';
		if(isset($_POST['adrotate_start_date'])) $start_date = sanitize_key(trim($_POST['adrotate_start_date']));
		if(isset($_POST['adrotate_start_hour'])) $start_hour = sanitize_key(trim($_POST['adrotate_start_hour']));
		if(isset($_POST['adrotate_start_minute'])) $start_minute = sanitize_key(trim($_POST['adrotate_start_minute']));
		if(isset($_POST['adrotate_end_date'])) $end_date = sanitize_key(trim($_POST['adrotate_end_date']));
		if(isset($_POST['adrotate_end_hour'])) $end_hour = sanitize_key(trim($_POST['adrotate_end_hour']));
		if(isset($_POST['adrotate_end_minute'])) $end_minute = sanitize_key(trim($_POST['adrotate_end_minute']));

		$maxclicks = $maxshown = '';
		if(isset($_POST['adrotate_maxclicks'])) $maxclicks = sanitize_key(trim($_POST['adrotate_maxclicks']));
		if(isset($_POST['adrotate_maxshown'])) $maxshown = sanitize_key(trim($_POST['adrotate_maxshown']));

		// Advanced options
		$image_field = $image_dropdown = $tracker = $show_everyone = '';
		$advertiser = 0;
		if(isset($_POST['adrotate_image'])) $image_field = strip_tags(trim($_POST['adrotate_image']));
		if(isset($_POST['adrotate_image_dropdown'])) $image_dropdown = strip_tags(trim($_POST['adrotate_image_dropdown']));
		if(isset($_POST['adrotate_tracker'])) $tracker = strip_tags(trim($_POST['adrotate_tracker']));

		// Misc variables
		$type = '';
		$groups = array();
		if(isset($_POST['groupselect'])) $groups = $_POST['groupselect'];
		if(isset($_POST['adrotate_type'])) $type = sanitize_key(trim($_POST['adrotate_type']));


		if(current_user_can('adrotate_ad_manage')) {
			if(strlen($title) < 1) $title = 'Advert '.$id;
			$title = str_replace('"', "", $title);

			// Just in case someone with a role manager stars messing around with lower level users
			if(!current_user_can('unfiltered_html')) {
				$bannercode = wp_kses_post($bannercode);
			}
			$bannercode = htmlspecialchars($bannercode, ENT_QUOTES);

			// Clean up bannercode
			if(preg_match('/%ID%/', $bannercode)) $bannercode = str_replace('%ID%', '%id%', $bannercode);
			if(preg_match('/%IMAGE%/', $bannercode)) $bannercode = str_replace('%IMAGE%', '%image%', $bannercode);
			if(preg_match('/%TITLE%/', $bannercode)) $bannercode = str_replace('%TITLE%', '%title%', $bannercode);
			if(preg_match('/%RANDOM%/', $bannercode)) $bannercode = str_replace('%RANDOM%', '%random%', $bannercode);

			// Sort out start dates
			if(strlen($start_date) > 0) {
				list($start_day, $start_month, $start_year) = explode('-', $start_date); // dd/mm/yyyy
			} else {
				$start_year = $start_month = $start_day = 0;
			}

			if(($start_year > 0 AND $start_month > 0 AND $start_day > 0) AND strlen($start_hour) == 0) $start_hour = '00';
			if(($start_year > 0 AND $start_month > 0 AND $start_day > 0) AND strlen($start_minute) == 0) $start_minute = '00';

			if($start_month > 0 AND $start_day > 0 AND $start_year > 0) {
				$start_date = mktime($start_hour, $start_minute, 0, $start_month, $start_day, $start_year);
			} else {
				$start_date = 0;
			}

			// Sort out end dates
			if(strlen($end_date) > 0) {
				list($end_day, $end_month, $end_year) = explode('-', $end_date); // dd/mm/yyyy
			} else {
				$end_year = $end_month = $end_day = 0;
			}

			if(($end_year > 0 AND $end_month > 0 AND $end_day > 0) AND strlen($end_hour) == 0) $end_hour = '00';
			if(($end_year > 0 AND $end_month > 0 AND $end_day > 0) AND strlen($end_minute) == 0) $end_minute = '00';

			if($end_month > 0 AND $end_day > 0 AND $end_year > 0) {
				$end_date = mktime($end_hour, $end_minute, 0, $end_month, $end_day, $end_year);
			} else {
				$end_date = 0;
			}

			// Enddate is too early, reset to default
			if($end_date <= $start_date) $end_date = $start_date + 7257600; // 84 days (12 weeks)

			// Sort out click and impressions restrictions
			if(strlen($maxclicks) < 1 OR !is_numeric($maxclicks)) $maxclicks = 0;
			if(strlen($maxshown) < 1 OR !is_numeric($maxshown)) $maxshown = 0;

			if(isset($tracker) AND strlen($tracker) != 0) $tracker = 'Y';
				else $tracker = 'N';

			// Determine image settings ($image_field has priority!)
			if(strlen($image_field) > 1) {
				$imagetype = 'field';
				$image = $image_field;
			} else if(strlen($image_dropdown) > 1) {
				$imagetype = 'dropdown';
				$image = WP_CONTENT_URL."/banners/".$image_dropdown;
			} else {
				$imagetype = $image = '';
			}

			// Save schedule for new ads or update the existing one
			if($type != 'empty') {
				$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_schedule` WHERE `id` IN (SELECT `schedule` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `schedule` != %d AND `schedule` > 0 AND `ad` = %d AND `group` = 0 AND `user` = 0);", $schedule_id, $id));
			}
			$wpdb->update($wpdb->prefix.'adrotate_schedule', array('starttime' => $start_date, 'stoptime' => $end_date, 'maxclicks' => $maxclicks, 'maximpressions' => $maxshown), array('id' => $schedule_id));

			// Save the ad to the DB
			$wpdb->update($wpdb->prefix.'adrotate', array('title' => $title, 'bannercode' => $bannercode, 'updated' => $thetime, 'author' => $author, 'imagetype' => $imagetype, 'image' => $image, 'tracker' => $tracker, 'type' => $active), array('id' => $id));

			// Fetch group records for the ad
			$groupmeta = $wpdb->get_results($wpdb->prepare("SELECT `group` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `user` = 0 AND `schedule` = 0;", $id));
			$group_array = array();
			foreach($groupmeta as $meta) {
				$group_array[] = $meta->group;
			}

			// Add new groups to this ad
			$insert = array_diff($groups, $group_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => $value, 'user' => 0, 'schedule' => 0));
			}
			unset($value);

			// Remove groups from this ad
			$delete = array_diff($group_array, $groups);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `group` = %d AND `user` = 0 AND `schedule` = 0;", $id, $value));
			}
			unset($value);

			// Verify ads
			adrotate_evaluate_ads();

			if($type == 'empty') {
				$action = 'new';
			} else {
				$action = 'update';
			}

			adrotate_return('adrotate', 200);
			exit;
		} else {
			adrotate_return('adrotate', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_group
 Purpose:   Save provided data for groups, update linkmeta where required
-------------------------------------------------------------*/
function adrotate_insert_group() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_group')) {
		$action = $id = $name = $modus = '';
		if(isset($_POST['adrotate_action'])) $action = sanitize_key($_POST['adrotate_action']);
		if(isset($_POST['adrotate_id'])) $id = sanitize_key($_POST['adrotate_id']);
		if(isset($_POST['adrotate_groupname'])) $name = sanitize_text_field($_POST['adrotate_groupname']);
		if(isset($_POST['adrotate_modus'])) $modus = sanitize_key(trim($_POST['adrotate_modus']));

		$rows = $columns = $adwidth = $adheight = $adspeed = '';
		if(isset($_POST['adrotate_gridrows'])) $rows = sanitize_key(trim($_POST['adrotate_gridrows']));
		if(isset($_POST['adrotate_gridcolumns'])) $columns = sanitize_key(trim($_POST['adrotate_gridcolumns']));
		if(isset($_POST['adrotate_adwidth'])) $adwidth = sanitize_key(trim($_POST['adrotate_adwidth']));
		if(isset($_POST['adrotate_adheight'])) $adheight = sanitize_key(trim($_POST['adrotate_adheight']));
		if(isset($_POST['adrotate_adspeed'])) $adspeed = sanitize_key(trim($_POST['adrotate_adspeed']));

		$ads = $admargin = $align = '';
		if(isset($_POST['adselect'])) $ads = $_POST['adselect'];
		if(isset($_POST['adrotate_admargin'])) $admargin = sanitize_key(trim($_POST['adrotate_admargin']));
		if(isset($_POST['adrotate_align'])) $align = sanitize_key(trim($_POST['adrotate_align']));

		$categories = $category_loc = $category_par = $pages = $page_loc = $page_par = '';
		if(isset($_POST['adrotate_categories'])) $categories = $_POST['adrotate_categories'];
		if(isset($_POST['adrotate_cat_location'])) $category_loc = sanitize_key($_POST['adrotate_cat_location']);
		if(isset($_POST['adrotate_cat_paragraph'])) $category_par = sanitize_key($_POST['adrotate_cat_paragraph']);
		if(isset($_POST['adrotate_pages'])) $pages = $_POST['adrotate_pages'];
		if(isset($_POST['adrotate_page_location'])) $page_loc = sanitize_key($_POST['adrotate_page_location']);
		if(isset($_POST['adrotate_page_paragraph'])) $page_par = sanitize_key($_POST['adrotate_page_paragraph']);

		$wrapper_before = $wrapper_after = '';
		if(isset($_POST['adrotate_wrapper_before'])) $wrapper_before = trim($_POST['adrotate_wrapper_before']);
		if(isset($_POST['adrotate_wrapper_after'])) $wrapper_after = trim($_POST['adrotate_wrapper_after']);

		if(current_user_can('adrotate_group_manage')) {
			if(strlen($name) < 1) $name = 'Group '.$id;
			$name = str_replace('"', '', $name);

			if($modus < 0 OR $modus > 2) $modus = 0;
			if($adspeed < 0 OR $adspeed > 99999) $adspeed = 6000;
			if($align < 0 OR $align > 3) $align = 0;

			// Sort out block shape
			if($rows < 1 OR $rows == '' OR !is_numeric($rows)) $rows = 2;
			if($columns < 1 OR $columns == '' OR !is_numeric($columns)) $columns = 2;
			if((is_numeric($adwidth) AND ($adwidth < 1 OR $adwidth > 9999)) OR $adwidth == '' OR (!is_numeric($adwidth) AND $adwidth != 'auto')) $adwidth = '728';
			if((is_numeric($adheight) AND ($adheight < 1 OR $adheight > 9999)) OR $adheight == '' OR (!is_numeric($adheight) AND $adheight != 'auto')) $adheight = '90';
			if($admargin < 0 OR $admargin > 99 OR $admargin == '' OR !is_numeric($admargin)) $admargin = 0;

			// Categories
			if(!is_array($categories)) $categories = array();
			$category = implode(',', $categories);
			if($category_par > 0) $category_loc = 4;
			if($category_loc != 4) $category_par = 0;

			// Pages
			if(!is_array($pages)) $pages = array();
			$page = implode(',', $pages);
			if($page_par > 0) $page_loc = 4;
			if($page_loc != 4) $page_par = 0;

			// Fetch records for the group
			$linkmeta = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `group` = %d AND `user` = 0;", $id));
			foreach($linkmeta as $meta) {
				$meta_array[] = $meta->ad;
			}

			if(empty($meta_array)) $meta_array = array();
			if(empty($ads)) $ads = array();

			// Add new ads to this group
			$insert = array_diff($ads,$meta_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $value, 'group' => $id, 'user' => 0));
			}
			unset($value);

			// Remove ads from this group
			$delete = array_diff($meta_array,$ads);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `group` = %d AND `user` = 0;", $value, $id));
			}
			unset($value);

			// Strip out 'dangerous' tags and crap?
			if(!current_user_can('unfiltered_html')) {
				$wrapper_before = wp_kses_post($wrapper_before);
				$wrapper_after = wp_kses_post($wrapper_after);
			}
			$wrapper_before = htmlspecialchars($wrapper_before, ENT_QUOTES);
			$wrapper_after = htmlspecialchars($wrapper_after, ENT_QUOTES);

			// Update the group itself
			$wpdb->update($wpdb->prefix.'adrotate_groups', array('name' => $name, 'modus' => $modus, 'fallback' => 0, 'network' => 0, 'cat' => $category, 'cat_loc' => $category_loc, 'cat_par' => $category_par, 'page' => $page, 'page_loc' => $page_loc, 'page_par' => $page_par, 'wrapper_before' => $wrapper_before, 'wrapper_after' => $wrapper_after, 'align' => $align, 'gridrows' => $rows, 'gridcolumns' => $columns, 'admargin' => $admargin, 'adwidth' => $adwidth, 'adheight' => $adheight, 'adspeed' => $adspeed), array('id' => $id));

			// Determine Dynamic Library requirement
			$dynamic_count = $wpdb->get_var("SELECT COUNT(*) as `total` FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `modus` = 1;");
			update_option('adrotate_dynamic_required', $dynamic_count);

			// Generate CSS for group
			if($align == 0) { // None
				$group_align = "";
			} else if($align == 1) { // Left
				$group_align = " float:left; clear:left;";
			} else if($align == 2) { // Right
				$group_align = " float:right; clear:right;";
			} else if($align == 3) { // Center
				$group_align = " margin: 0 auto;";
			}

			$output_css = "";
			if($modus == 0 AND ($admargin > 0 OR $align > 0)) { // Single ad group
				if($align < 3) {
					$output_css .= "\t.g".$adrotate_config['adblock_disguise']."-".$id." { margin:".$admargin."px; ".$group_align." }\n";
				} else {
					$output_css .= "\t.g".$adrotate_config['adblock_disguise']."-".$id." { ".$group_align." }\n";
				}
			}

			if($modus == 1) { // Dynamic group
				if($adwidth != 'auto') {
					$width = " width:100%; max-width:".$adwidth."px;";
				} else {
					$width = " width:auto;";
				}

				if($adheight != 'auto') {
					$height = " height:100%; max-height:".$adheight."px;";
				} else {
					$height = " height:auto;";
				}

				if($align < 3) {
					$output_css .= "\t.g".$adrotate_config['adblock_disguise']."-".$id." { margin:".$admargin."px; ".$width.$height.$group_align." }\n";
				} else {
					$output_css .= "\t.g".$adrotate_config['adblock_disguise']."-".$id." {".$width.$height.$group_align." }\n";
				}

				unset($width_sum, $width, $height_sum, $height);
			}

			if($modus == 2) { // Block group
				if($adwidth != 'auto') {
					$width_sum = $columns * ($adwidth + ($admargin * 2));
					$grid_width = "min-width:".$admargin."px; max-width:".$width_sum."px;";
				} else {
					$grid_width = "width:auto;";
				}

				$output_css .= "\t.g".$adrotate_config['adblock_disguise']."-".$id." { ".$grid_width.$group_align." }\n";
				$output_css .= "\t.b".$adrotate_config['adblock_disguise']."-".$id." { margin:".$admargin."px; }\n";
				unset($width_sum, $grid_width, $height_sum, $grid_height);
			}

			$group_css = get_option('adrotate_group_css', array());
			$group_css[$id] = $output_css;
			update_option('adrotate_group_css', $group_css);
			// end CSS

			adrotate_return('adrotate-groups', 201);
			exit;
		} else {
			adrotate_return('adrotate-groups', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_media
 Purpose:   Prepare input form on saving new or updated banners
-------------------------------------------------------------*/
function adrotate_insert_media() {
	global $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_media')) {
		if(current_user_can('adrotate_ad_manage')) {

			if($_FILES['adrotate_image']['size'] > 0 AND $_FILES['adrotate_image']['size'] <= 512000) {
				$file_path = WP_CONTENT_DIR."/".esc_attr($_POST['adrotate_image_location'])."/";

				$file = pathinfo(sanitize_file_name($_FILES['adrotate_image']['name']));
				$file['mimetype'] = mime_content_type($_FILES['adrotate_image']['tmp_name']);

				// Everyone can upload these
				$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png', 'svg', 'webp');
				$allowed_mimetypes = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/svg', 'image/webp');

				if(current_user_can('unfiltered_html')) {
					// Higher level and enabled users can also upload these.
					$allowed_extensions = array_merge($allowed_extensions, array('html', 'htm', 'js', 'zip'));
					$allowed_mimetypes = array_merge($allowed_mimetypes, array('text/html', 'text/htm', 'application/x-javascript', 'application/javascript', 'text/javascript', 'application/zip'));
				}

				if(in_array($file['extension'], $allowed_extensions) AND in_array($file['mimetype'], $allowed_mimetypes)) {
					if ($_FILES['adrotate_image']['error'] > 0) {
						if($_FILES['adrotate_image']['error'] == 1 OR $_FILES['adrotate_image']['error'] == 2) {
							$errorcode = 511;
						} else if($_FILES['adrotate_image']['error'] == 3) {
							$errorcode = 506;
						} else if($_FILES['adrotate_image']['error'] == 4) {
							$errorcode = 506;
						} else if($_FILES['adrotate_image']['error'] == 6 OR $_FILES['adrotate_image']['error'] == 7) {
							$errorcode = 506;
						} else {
							$errorcode = '';
						}

						adrotate_return('adrotate-media', $errorcode); // Other error
					} else {
						// Rename file if it already exists
						if(file_exists($file_path.$file['filename'].'.'.$file['extension'])) {
						    $file['filename'] = $file['filename'].'_'.rand(1000, 9999);
						}

						// Move file into place
						if(!move_uploaded_file($_FILES['adrotate_image']['tmp_name'], $file_path.$file['filename'].'.'.$file['extension'])) {
							adrotate_return('adrotate-media', 506); // Upload error
						}

						if(($file['mimetype'] == 'application/zip' OR $file['mimetype'] == 'application/zip-compressed' OR $file['mimetype'] == 'application/x-zip-compressed') AND $file['extension'] == 'zip') {
							require_once(ABSPATH .'/wp-admin/includes/file.php');

							$creds = request_filesystem_credentials(wp_nonce_url('admin.php?page=adrotate-media'), '', false, $file_path, null);
						    if(!WP_Filesystem($creds)) {
								$creds = request_filesystem_credentials(wp_nonce_url('admin.php?page=adrotate-media'), '', true, $file_path, null);
							}

							// Unzip file to a folder based on the filename without extension
							$unzipfile = unzip_file($file_path.$file['filename'].'.'.$file['extension'], $file_path.$file['filename']);
							if(is_wp_error($unzipfile)) {
								adrotate_return('adrotate-media', 512, array('error' => $unzipfile->get_error_message())); // Can not unzip file
							}

							// Delete unwanted files
							adrotate_clean_folder_contents($file_path.$file['filename']);

							// Delete the uploaded zip
							adrotate_unlink($file['basename']);
						}

						adrotate_return('adrotate-media', 202); // Success
					}
				} else {
					adrotate_return('adrotate-media', 510); // Filetype
				}
			} else {
				adrotate_return('adrotate-media', 511); // Size
			}
		} else {
			adrotate_return('adrotate-media', 500); // No access/permission
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_folder
 Purpose:   Create a folder
-------------------------------------------------------------*/
function adrotate_insert_folder() {
	global $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_media')) {
		if(current_user_can('adrotate_ad_manage')) {

			$folder = (isset($_POST['adrotate_folder'])) ? esc_attr(strip_tags(trim($_POST['adrotate_folder']))) : '';

			if(strlen($folder) > 0 and strlen($folder) <= 100) {
				$folder = sanitize_file_name($folder);
				$folder = WP_CONTENT_DIR."/".$adrotate_config['banner_folder']."/".$folder;

				if(is_dir($folder)) {
					$folder = $folder.'_'.rand(1000, 9999);
				}

				if(wp_mkdir_p($folder)) {
					adrotate_return('adrotate-media', 223); // Success
				} else {
					adrotate_return('adrotate-media', 516); // error
				}
			} else {
				adrotate_return('adrotate-media', 515); // name length
			}
		} else {
			adrotate_return('adrotate-media', 500); // No access/permission
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_request_action
 Purpose:   Prepare action for banner or group from database
-------------------------------------------------------------*/
function adrotate_request_action() {
	global $wpdb, $adrotate_config;

	$banner_ids = $group_ids = '';

	if(wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_active') OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_disable') OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_error') OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_groups')) {
		if(isset($_POST['bannercheck'])) $banner_ids = $_POST['bannercheck'];
		if(isset($_POST['disabledbannercheck'])) $banner_ids = $_POST['disabledbannercheck'];
		if(isset($_POST['errorbannercheck'])) $banner_ids = $_POST['errorbannercheck'];
		if(isset($_POST['groupcheck'])) $group_ids = $_POST['groupcheck'];
		if(isset($_POST['adrotate_id'])) $banner_ids = array($_POST['adrotate_id']);

		// Determine which kind of action to use
		if(isset($_POST['adrotate_action'])) {
			// Default action call
			$actions = strip_tags(trim($_POST['adrotate_action']));
		} else if(isset($_POST['adrotate_disabled_action'])) {
			// Disabled ads listing call
			$actions = strip_tags(trim($_POST['adrotate_disabled_action']));
		} else if(isset($_POST['adrotate_error_action'])) {
			// Erroneous ads listing call
			$actions = strip_tags(trim($_POST['adrotate_error_action']));
		}
		if(preg_match('/-/', $actions)) {
			list($action, $specific) = explode("-", $actions);
		} else {
		   	$action = $actions;
		}

		if($banner_ids != '') {
			$return = 'adrotate';
			foreach($banner_ids as $banner_id) {
				if($action == 'deactivate') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_active($banner_id, 'deactivate');
						$result_id = 210;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'activate') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_active($banner_id, 'activate');
						adrotate_evaluate_ads();
						$result_id = 211;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'delete') {
					if(current_user_can('adrotate_ad_delete')) {
						adrotate_delete($banner_id, 'banner');
						$result_id = 203;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'reset') {
					if(current_user_can('adrotate_ad_delete')) {
						adrotate_reset($banner_id);
						$result_id = 208;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'renew') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_renew($banner_id, $specific);
						adrotate_evaluate_ads();
						$result_id = 209;
					} else {
						adrotate_return($return, 500);
					}
				}
			}
		}

		if($group_ids != '') {
			$return = 'adrotate-groups';
			foreach($group_ids as $group_id) {
				if($action == 'group_delete') {
					if(current_user_can('adrotate_group_delete')) {
						adrotate_delete($group_id, 'group');
						$result_id = 204;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'group_delete_banners') {
					if(current_user_can('adrotate_group_delete')) {
						adrotate_delete($group_id, 'bannergroup');
						$result_id = 213;
					} else {
						adrotate_return($return, 500);
					}
				}
			}
		}

		adrotate_return($return, $result_id);
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_delete
 Purpose:   Remove banner or group from database
-------------------------------------------------------------*/
function adrotate_delete($id, $what) {
	global $wpdb;

	if($id > 0) {
		if($what == 'banner') {
			$schedule_id = $wpdb->get_var($wpdb->prepare("SELECT `schedule` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0 AND `schedule` != 0;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_schedule` WHERE `id` = %d;", $schedule_id));
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_stats` WHERE `ad` = %d;", $id));
		} else if ($what == 'group') {
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_groups` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `group` = %d;", $id));
			$wpdb->update($wpdb->prefix.'adrotate_groups', array('fallback' => 0), array('fallback' => $id));
		} else if ($what == 'bannergroup') {
			$linkmeta = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `group` = %d AND `user` = 0 AND `schedule` = 0;", $id));
			foreach($linkmeta as $meta) {
				adrotate_delete($meta->ad, 'banner');
			}
			unset($linkmeta);
			adrotate_delete($id, 'group');
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_active
 Purpose:   Activate or Deactivate a banner
-------------------------------------------------------------*/
function adrotate_active($id, $what) {
	global $wpdb;

	if($id > 0) {
		if($what == 'deactivate') {
			$wpdb->update($wpdb->prefix.'adrotate', array('type' => 'disabled'), array('id' => $id));
		}
		if ($what == 'activate') {
			// Determine status of ad
			$adstate = adrotate_evaluate_ad($id);
			$adtype = ($adstate == 'error' OR $adstate == 'expired') ? 'error' : 'active';

			$wpdb->update($wpdb->prefix.'adrotate', array('type' => $adtype), array('id' => $id));
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_reset
 Purpose:   Reset statistics for a banner
-------------------------------------------------------------*/
function adrotate_reset($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}adrotate_stats` WHERE `ad` = %d", $id));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_renew
 Purpose:   Renew the end date of a banner with a new schedule starting where the last ended
-------------------------------------------------------------*/
function adrotate_renew($id, $howlong = 2592000) {
	global $wpdb;

	if($id > 0) {
		$schedule_id = $wpdb->get_var($wpdb->prepare("SELECT `schedule` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0 ORDER BY `id` DESC LIMIT 1;", $id));
		if($schedule_id > 0) {
			$wpdb->query($wpdb->prepare("UPDATE `{$wpdb->prefix}adrotate_schedule` SET `stoptime` = `stoptime` + %d WHERE `id` = %d;", $howlong, $schedule_id));
		} else {
			$now = current_time('timestamp');
			$stoptime = $now + $howlong;
			$wpdb->insert($wpdb->prefix.'adrotate_schedule', array('name' => 'Schedule for ad '.$id, 'starttime' => $now, 'stoptime' => $stoptime, 'maxclicks' => 0, 'maximpressions' => 0));
			$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => 0, 'user' => 0, 'schedule' => $wpdb->insert_id));
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_options_submit
 Purpose:   Save options from dashboard
-------------------------------------------------------------*/
function adrotate_options_submit() {
	if(wp_verify_nonce($_POST['adrotate_nonce_settings'],'adrotate_settings')) {

		$settings_tab = sanitize_key($_POST['adrotate_settings_tab']);

		if($settings_tab == 'general') {
			$config = get_option('adrotate_config');

			$config['jquery'] = (isset($_POST['adrotate_jquery'])) ? 'Y' : 'N';
			$config['jsfooter'] = (isset($_POST['adrotate_jsfooter'])) ? 'Y' : 'N';

			// Turn options off/reset them. Available in AdRotate Pro only
			$config['textwidget_shortcodes'] = 'N';
			$config['banner_folder'] = 'banners';
			$config['notification_email'] = array();
			$config['advertiser_email'] = array();
			$config['enable_geo'] = 0;
			$config['geo_cookie_life'] = 86400;
			$config['geo_email'] = '';
			$config['geo_pass'] = '';
			$config['enable_advertisers'] = 'N';
			$config['enable_editing'] = 'N';
			$config['enable_geo_advertisers'] = 0;
			update_option('adrotate_config', $config);

			// Sort out crawlers
			$crawlers = explode(',', trim($_POST['adrotate_crawlers']));
			$new_crawlers = array();
			foreach($crawlers as $crawler) {
				$crawler = preg_replace('/[^a-zA-Z0-9\[\]\-_:; ]/i', '', trim($crawler));
				if(strlen($crawler) > 0) $new_crawlers[] = $crawler;
			}
			update_option('adrotate_crawlers', $new_crawlers);
		}

		if($settings_tab == 'notifications') {
			$notifications = get_option('adrotate_notifications');

			$notifications['notification_dash'] = (isset($_POST['adrotate_notification_dash'])) ? 'Y' : 'N';

			// Dashboard Notifications
			$notifications['notification_dash_expired'] = (isset($_POST['adrotate_notification_dash_expired'])) ? 'Y' : 'N';
			$notifications['notification_dash_soon'] = (isset($_POST['adrotate_notification_dash_soon'])) ? 'Y' : 'N';

			// Turn options off. Available in AdRotate Pro only
			$notifications['notification_email'] = 'N';
			$notifications['notification_email_publisher'] = array(get_option('admin_email'));
			$notifications['notification_mail_geo'] = 'N';
			$notifications['notification_mail_status'] = 'N';
			$notifications['notification_mail_queue'] = 'N';
			$notifications['notification_mail_approved'] = 'N';
			$notifications['notification_mail_rejected'] = 'N';

			update_option('adrotate_notifications', $notifications);
		}

		if($settings_tab == 'stats') {
			$config = get_option('adrotate_config');

			$stats = trim($_POST['adrotate_stats']);
			$config['stats'] = (is_numeric($stats) AND $stats >= 0 AND $stats <= 3) ? $stats : 1;
			$config['enable_loggedin_impressions'] = 'Y';
			$config['enable_loggedin_clicks'] = 'Y';
			$config['enable_clean_trackerdata'] = (isset($_POST['adrotate_enable_clean_trackerdata'])) ? 'Y' : 'N';

			if($config['enable_clean_trackerdata'] == 'Y' AND !wp_next_scheduled('adrotate_delete_transients')) {
				wp_schedule_event(current_time('timestamp'), 'twicedaily', 'adrotate_delete_transients');
			}
			if($config['enable_clean_trackerdata'] == 'N' AND wp_next_scheduled('adrotate_delete_transients')) {
				wp_clear_scheduled_hook('adrotate_delete_transients');
			}

			$impression_timer = trim($_POST['adrotate_impression_timer']);
			$config['impression_timer'] = (is_numeric($impression_timer) AND $impression_timer >= 10 AND $impression_timer <= 3600) ? $impression_timer : 60;
			$click_timer = trim($_POST['adrotate_click_timer']);
			$config['click_timer'] = (is_numeric($click_timer) AND $click_timer >= 60 AND $click_timer <= 86400) ? $click_timer : 86400;

			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'roles') {
			$config = get_option('adrotate_config');

			adrotate_set_capability($_POST['adrotate_ad_manage'], "adrotate_ad_manage");
			adrotate_set_capability($_POST['adrotate_ad_delete'], "adrotate_ad_delete");
			adrotate_set_capability($_POST['adrotate_group_manage'], "adrotate_group_manage");
			adrotate_set_capability($_POST['adrotate_group_delete'], "adrotate_group_delete");
			$config['ad_manage'] = $_POST['adrotate_ad_manage'];
			$config['ad_delete'] = $_POST['adrotate_ad_delete'];
			$config['group_manage'] = $_POST['adrotate_group_manage'];
			$config['group_delete'] = $_POST['adrotate_group_delete'];

			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'misc') {
			$config = get_option('adrotate_config');

			$config['widgetalign'] = (isset($_POST['adrotate_widgetalign'])) ? 'Y' : 'N';
			$config['widgetpadding'] = (isset($_POST['adrotate_widgetpadding'])) ? 'Y' : 'N';
			$config['hide_schedules'] = (isset($_POST['adrotate_hide_schedules'])) ? 'Y' : 'N';
			$config['w3caching'] = (isset($_POST['adrotate_w3caching'])) ? 'Y' : 'N';
			$config['borlabscache'] = (isset($_POST['adrotate_borlabscache'])) ? 'Y' : 'N';

			update_option('adrotate_config', $config);
		}

		// Return to dashboard
		adrotate_return('adrotate-settings', 400, array('tab' => $settings_tab));
	} else {
		adrotate_nonce_error();
		exit;
	}
}
?>
