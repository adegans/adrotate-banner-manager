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
 Name:      adrotate_ad
 Purpose:   Show requested ad
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad($banner_id, $opt = null) {
	global $wpdb, $adrotate_config;

	$output = '';

	if($banner_id) {
		$options = wp_parse_args($opt, array());
		$available = true;

		$banner = $wpdb->get_row($wpdb->prepare("SELECT `id`, `title`, `bannercode`, `tracker`, `image` FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d AND (`type` = 'active' OR `type` = '2days' OR `type` = '7days');", $banner_id), ARRAY_A);

		if($banner) {
			if(adrotate_filter_schedule($banner)) {
				$available = false;
			}
		} else {
			$available = false;
		}


		if($available) {
			$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner['image']);

			$output .= "<div class=\"a-single a-".$banner['id']."\">";
			$output .= adrotate_ad_output($banner['id'], 0, $banner['title'], $banner['bannercode'], $banner['tracker'], $image);
			$output .= "</div>";

			if($adrotate_config['stats'] == 1 AND $banner['tracker'] == 'Y') {
				adrotate_count_impression($banner['id'], 0, 0);
			}
		} else {
			$output .= adrotate_error('ad_expired', array($banner_id));
		}
		unset($banner);
	} else {
		$output .= adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group
 Purpose:   Fetch ads in specified group(s) and show a random ad
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_group($group_ids, $opt = null) {
	global $wpdb, $adrotate_config;

	$output = $group_select = '';
	if($group_ids) {
		$options = wp_parse_args($opt, array());

		$now = current_time('timestamp');

		$group_array = (preg_match('/,/is', $group_ids)) ? explode(',', $group_ids) : array($group_ids);
		$group_array = array_filter($group_array);

		foreach($group_array as $key => $value) {
			$group_select .= " `{$wpdb->prefix}adrotate_linkmeta`.`group` = ".$wpdb->prepare('%d', $value)." OR";
		}
		$group_select = rtrim($group_select, " OR");

		$group = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_array[0]));

		if($group) {
			// Get all ads in all selected groups
			$ads = $wpdb->get_results(
				"SELECT
					`{$wpdb->prefix}adrotate`.`id`,
					`{$wpdb->prefix}adrotate`.`title`,
					`{$wpdb->prefix}adrotate`.`bannercode`,
					`{$wpdb->prefix}adrotate`.`image`,
					`{$wpdb->prefix}adrotate`.`tracker`,
					`{$wpdb->prefix}adrotate_linkmeta`.`group`
				FROM
					`{$wpdb->prefix}adrotate`,
					`{$wpdb->prefix}adrotate_linkmeta`
				WHERE
					({$group_select})
					AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
					AND `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad`
					AND (`{$wpdb->prefix}adrotate`.`type` = 'active'
						OR `{$wpdb->prefix}adrotate`.`type` = '2days'
						OR `{$wpdb->prefix}adrotate`.`type` = '7days')
				GROUP BY `{$wpdb->prefix}adrotate`.`id`
				ORDER BY `{$wpdb->prefix}adrotate`.`id`;"
			, ARRAY_A);

			if($ads) {
				foreach($ads as $key => $ad) {
					if(adrotate_filter_schedule($ad)) {
						unset($ads[$key]);
					}
				}

				$array_count = count($ads);
				if($array_count > 0) {
					$before = $after = '';
					$before = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
					$after = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

					$output .= "<div class=\"g g-".$group->id."\">";

					// Kill dynamic mode for mobile users
					if($group->modus == 1 AND wp_is_mobile()) {
						$group->modus = 0;
					}

					if($group->modus == 1) { // Dynamic ads
						$i = 1;

						// Randomize and trim output
						$ads = adrotate_shuffle($ads);
						$ads = array_slice($ads, 0, 10, 1);

						foreach($ads as $key => $banner) {
							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner['image']);

							$output .= "<div class=\"g-dyn a-".$banner['id']." c-".$i."\">";
							$output .= $before.adrotate_ad_output($banner['id'], $group->id, $banner['title'], $banner['bannercode'], $banner['tracker'], $image).$after;
							$output .= "</div>";
							$i++;
						}
					} else if($group->modus == 2) { // Block of ads
						$block_count = $group->gridcolumns * $group->gridrows;
						if($array_count < $block_count) $block_count = $array_count;
						$columns = 1;

						for($i=1;$i<=$block_count;$i++) {
							$array_key = array_rand($ads, 1);

							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $ads[$array_key]['image']);

							$output .= "<div class=\"g-col b-".$group->id." a-".$ads[$array_key]['id']."\">";
							$output .= $before.adrotate_ad_output($ads[$array_key]['id'], $group->id, $ads[$array_key]['title'], $ads[$array_key]['bannercode'], $ads[$array_key]['tracker'], $image).$after;
							$output .= "</div>";

							if($columns == $group->gridcolumns AND $i != $block_count) {
								$output .= "</div><div class=\"g g-".$group->id."\">";
								$columns = 1;
							} else {
								$columns++;
							}

							if($adrotate_config['stats'] == 1 AND $ads[$array_key]['tracker'] == 'Y') {
								adrotate_count_impression($ads[$array_key]['id'], $group->id, 0);
							}

							unset($ads[$array_key]);
						}
					} else { // Default (single ad)
						$array_key = array_rand($ads, 1);

						$image = str_replace('%folder%', $adrotate_config['banner_folder'], $ads[$array_key]['image']);

						$output .= "<div class=\"g-single a-".$ads[$array_key]['id']."\">";
						$output .= $before.adrotate_ad_output($ads[$array_key]['id'], $group->id, $ads[$array_key]['title'], $ads[$array_key]['bannercode'], $ads[$array_key]['tracker'], $image).$after;
						$output .= "</div>";

						if($adrotate_config['stats'] == 1 AND $ads[$array_key]['tracker'] == 'Y') {
							adrotate_count_impression($ads[$array_key]['id'], $group->id, 0);
						}
					}

					$output .= "</div>";

					unset($selected);
				} else {
					$output .= adrotate_error('ad_expired');
				}
			} else {
				$output .= adrotate_error('ad_unqualified');
			}
		} else {
			$output .= adrotate_error('group_not_found', array($group_array[0]));
		}
	} else {
		$output .= adrotate_error('group_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group_post_inject
 Purpose:   Prepare group for post injection
 Since:		5.10
-------------------------------------------------------------*/
function adrotate_group_post_inject($group_id) {
	global $wpdb, $adrotate_config;

	// Grab settings to use from first group
	$group = $wpdb->get_row($wpdb->prepare("SELECT `id`, `wrapper_before`, `wrapper_after` FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_id));

	// Get all ads in group
	$ads = $wpdb->get_results(
		"SELECT
			`{$wpdb->prefix}adrotate`.`id`, `title`, `bannercode`, `image`, `tracker`
		FROM
			`{$wpdb->prefix}adrotate`,
			`{$wpdb->prefix}adrotate_linkmeta`
		WHERE
			`{$wpdb->prefix}adrotate_linkmeta`.`group` = {$group_id}
			AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
			AND `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad`
			AND (`{$wpdb->prefix}adrotate`.`type` = 'active'
				OR `{$wpdb->prefix}adrotate`.`type` = '2days'
				OR `{$wpdb->prefix}adrotate`.`type` = '7days')
		GROUP BY `{$wpdb->prefix}adrotate`.`id`
		ORDER BY `{$wpdb->prefix}adrotate`.`id`;");

	if($ads) {
		foreach($ads as $ad) {
			$selected[$ad->id] = $ad;
			$selected = adrotate_filter_schedule($selected, $ad);
		}

		$array_count = count($selected);
		if($array_count > 0) {
			$output = $before = $after = '';
			$banner_id = array_rand($selected, 1);
			$image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);
			$before = str_replace('%id%', $group_id, stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
			$after = str_replace('%id%', $group_id, stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

			$output .= "<div class=\"g g-".$group->id."\">";
			$output .= "<div class=\"g-single a-".$selected[$banner_id]->id."\">";
			$output .= $before.adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->title, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $image).$after;
			$output .= "</div>";

			if($adrotate_config['stats'] == 1 AND ($selected[$banner_id]->tracker == "Y")) {
				adrotate_count_impression($selected[$banner_id]->id, $group->id);
			}

			$output .= "</div>";

			unset($selected, $banner_id);

			return $output;
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_shortcode
 Purpose:   Prepare function requests for calls on shortcodes
 Since:		0.7
-------------------------------------------------------------*/
function adrotate_shortcode($atts, $content = null) {
	global $adrotate_config;

	$banner_id = (!empty($atts['banner'])) ? trim($atts['banner'], '\r\t ') : 0;
	$group_ids = (!empty($atts['group'])) ? trim($atts['group'], '\r\t ') : 0;
	if(!empty($atts['fallback'])) $fallback	= 0; // Not supported in free version
	if(!empty($atts['weight']))	$weight	= 0; // Not supported in free version
	if(!empty($atts['site'])) $site = 0; // Not supported in free version
	if(!empty($atts['wrapper'])) $wrapper = 0; // Not supported in free version

	$output = "";
	if($adrotate_config['w3caching'] == 'Y') {
		$output .= "<!-- mfunc ".W3TC_DYNAMIC_SECURITY." -->";
		if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
			$output .= "echo adrotate_ad(".$banner_id.");";
		}
		if($banner_id == 0 AND $group_ids > 0) { // Show group
			$output .= "echo adrotate_group(".$group_ids.");";
		}
		$output .= "<!-- /mfunc ".W3TC_DYNAMIC_SECURITY." -->";
	} else if($adrotate_config['borlabscache'] == 'Y' AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$output .= "<!--[borlabs cache start: ".$borlabsphrase."]--> ";
			if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
				$output .= "echo adrotate_ad(".$banner_id.");";
			}
			if($banner_id == 0 AND $group_ids > 0) { // Show group
				$output .= "echo adrotate_group(".$group_ids.");";
			}
			$output .= " <!--[borlabs cache end: ".$borlabsphrase."]-->";

			unset($borlabsphrase);
		}
	} else {
		if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
			$output .= adrotate_ad($banner_id);
		}

		if($banner_id == 0 AND $group_ids > 0) { // Show group
			$output .= adrotate_group($group_ids);
		}
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_posts_cache_wrapper
 Purpose:   Wrap post injection return with caching code?
 Since:		5.10
-------------------------------------------------------------*/
function adrotate_inject_posts_cache_wrapper($group_id) {
	global $adrotate_config;

	if($adrotate_config['w3caching'] == 'Y') {
		$advert_output = "<!-- mfunc ".W3TC_DYNAMIC_SECURITY." -->";
		$advert_output .= "echo adrotate_group(".$group_id.");";
		$advert_output .= "<!-- /mfunc ".W3TC_DYNAMIC_SECURITY." -->";
	} else if($adrotate_config['borlabscache'] == 'Y' AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$advert_output = "<!--[borlabs cache start: ".$borlabsphrase."]-->";
			$advert_output .= "echo adrotate_group(".$group_id.");";
			$advert_output .= "<!--[borlabs cache end: ".$borlabsphrase."]-->";

			unset($borlabsphrase);
		}
	} else {
		$advert_output = adrotate_group($group_id);
	}

	return $advert_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_posts
 Purpose:   Add an advert to a single page or post
-------------------------------------------------------------*/
function adrotate_inject_posts($post_content) {
	global $wpdb, $post;

	$categories_top = $categories_bottom = $categories_inside = array();
	if(is_page()) {
		// Inject ads into pages
		$groups = $wpdb->get_results("SELECT `id`, `page`, `page_loc`, `page_par` FROM `{$wpdb->prefix}adrotate_groups` WHERE `page_loc` > 0 AND `page_loc` < 5;");

		foreach($groups as $group) {
			$pages_more = explode(',', $group->page);

			if(count($pages_more) > 0) {
				if(in_array($post->ID, $pages_more)) {
					if($group->page_loc == 1 OR $group->page_loc == 3) {
						$categories_top[$group->id] = $group->page_par;
					}
					if($group->page_loc == 2 OR $group->page_loc == 3) {
						$categories_bottom[$group->id] = $group->page_par;
					}
					if($group->page_loc == 4) {
						$categories_inside[$group->id] = $group->page_par;
					}
					unset($pages_more, $group);
				}
			}
		}
	}

	if(is_single()) {
		// Inject ads into posts in specified category
		$groups = $wpdb->get_results("SELECT `id`, `cat`, `cat_loc`, `cat_par` FROM `{$wpdb->prefix}adrotate_groups` WHERE `cat_loc` > 0 AND `cat_loc` < 5;");
		$wp_categories = wp_get_post_categories($post->ID, array('taxonomy' => 'category', 'fields' => 'ids'));

		foreach($groups as $group) {
			$categories_more = array_intersect($wp_categories, explode(',', $group->cat));

			if(count($categories_more) > 0) {
				if(has_category($categories_more, $post->ID)) {
					if(($group->cat_loc == 1 OR $group->cat_loc == 3)) {
						$categories_top[$group->id] = $group->cat_par;
					}
					if($group->cat_loc == 2 OR $group->cat_loc == 3) {
						$categories_bottom[$group->id] = $group->cat_par;
					}
					if($group->cat_loc == 4) {
						$categories_inside[$group->id] = $group->cat_par;
					}
					unset($categories_more, $group);
				}
			}
		}
	}

	// Advert in front of content
	if(count($categories_top) > 0) {
		$post_content = adrotate_inject_posts_cache_wrapper(array_rand($categories_top)).$post_content;
	}

	// Advert behind the content
	if(count($categories_bottom) > 0) {
		$post_content = $post_content.adrotate_inject_posts_cache_wrapper(array_rand($categories_bottom));
	}

	// Adverts inside the content
	if(count($categories_inside) > 0) {
		// Setup
		$categories_inside = adrotate_shuffle($categories_inside);
	    $post_content_exploded = explode('</p>', $post_content);
		$post_content_count = ceil(count($post_content_exploded));
		$inserted = array();

		// Determine after which paragraphs ads should show
		foreach($categories_inside as $group_id => $group_paragraph) {
			if($group_paragraph == 99) {
				$group_paragraph = $post_content_count / 2; // Middle of content
			}

			$group_paragraph = intval($group_paragraph);

			// Create $inserted with paragraphs numbers and link the group to it. This list is leading from this point on.
			if(!array_key_exists($group_paragraph, $inserted)) {
				$inserted[$group_paragraph] = $group_id;
			}
			unset($group_id, $group_paragraph);
		}

		// Inject ads behind paragraphs based on $inserted created above, IF a group_id is set higher than 0
		foreach($post_content_exploded as $index => $paragraph) {
			$insert_here = $index + 1; // Deal with array offset
			if(array_key_exists($insert_here, $inserted)) {
				if($inserted[$insert_here] > 0) {
					$post_content_exploded[$index] .= adrotate_inject_posts_cache_wrapper($inserted[$insert_here]);
					$inserted[$insert_here] = 0;
				}
			}
			unset($index, $paragraph, $insert_here);
		}

		// Re-assemble post_content and clean up
	    $post_content = implode('', $post_content_exploded);
		unset($post_content_exploded, $post_content_count, $inserted);
	}

	unset($groups, $categories_top, $categories_bottom, $categories_inside);

	return $post_content;
}

/*-------------------------------------------------------------
 Name:      adrotate_preview
 Purpose:   Show preview of selected advert (Dashboard)
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_preview($banner_id) {
	global $wpdb;

	if($banner_id) {
		$now = current_time('timestamp');

		$edit_banner = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d;", $banner_id));
		if($edit_banner) {
			if(preg_match_all('/<(ins|script)(.*?)>|onclick\=|onload\=/i', stripslashes(htmlspecialchars_decode($edit_banner->bannercode, ENT_QUOTES)), $things)) {
				$output = "<div class=\"preview-wrapper row_blue\"><div class=\"preview-inner\">Adverts with JavaScript or a &lt;ins&gt; tag can not be previewed!</div></div>";
			} else {
				$image = str_replace('%folder%', '/banners/', $edit_banner->image);
				$output = adrotate_ad_output($edit_banner->id, 0, $edit_banner->title, $edit_banner->bannercode, $edit_banner->tracker, $image);
			}
		} else {
			$output = adrotate_error('ad_expired');
		}
	} else {
		$output = adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_output
 Purpose:   Prepare the output for viewing
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad_output($id, $group, $name, $bannercode, $tracker, $image) {
	global $blog_id, $adrotate_config;

	$banner_output = $bannercode;
	$banner_output = stripslashes(htmlspecialchars_decode($banner_output, ENT_QUOTES));

	if($adrotate_config['stats'] > 0 AND $tracker == 'Y') {
		if(empty($blog_id) or $blog_id == '') {
			$blog_id = 0;
		}

		if($adrotate_config['stats'] == 1) { // Internal tracker
			preg_match_all('/<a[^>](?:.*?)>/i', $banner_output, $matches, PREG_SET_ORDER);
			if(isset($matches[0])) {
				$banner_output = str_ireplace('<a ', '<a data-track="'.base64_encode($id.','.$group.','.$blog_id.','.$adrotate_config['impression_timer']).'" ', $banner_output);
				foreach($matches[0] as $value) {
					if(preg_match('/<a[^>]+class=\"(.+?)\"[^>]*>/i', $value, $regs)) {
					    $result = $regs[1].' gofollow';
						$banner_output = str_ireplace('class="'.$regs[1].'"', 'class="'.$result.'"', $banner_output);
					} else {
						$banner_output = str_ireplace('<a ', '<a class="gofollow" ', $banner_output);
					}
					unset($value, $regs, $result);
				}
			}
		}
	}

	$image = apply_filters('adrotate_apply_photon', $image);

	$banner_output = str_replace('%title%', $name, $banner_output);
	$banner_output = str_replace('%random%', rand(100000,999999), $banner_output);
	$banner_output = str_replace('%asset%', $image, $banner_output); // Replaces %image%
	$banner_output = str_replace('%image%', $image, $banner_output); // Depreciated, remove in AdRotate 5.0
	$banner_output = str_replace('%id%', $id, $banner_output);
	$banner_output = do_shortcode($banner_output);

	return $banner_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_header
 Purpose:   Add required CSS to wp_head (action)
-------------------------------------------------------------*/
function adrotate_header() {
	global $adrotate_config;

	if(!function_exists('get_plugins')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$plugins = get_plugins();
	$plugin_version = $plugins['adrotate/adrotate.php']['Version'];

	$output = "\n<!-- This site is using AdRotate v".$plugin_version." to display their advertisements - https://ajdg.solutions/ -->\n";

	// Create CSS for the header
	$generated_css = get_option('adrotate_group_css');

	$output .= "<!-- AdRotate CSS -->\n";
	$output .= "<style type=\"text/css\" media=\"screen\">\n";
	$output .= "\t.g { margin:0px; padding:0px; overflow:hidden; line-height:1; zoom:1; }\n";
	$output .= "\t.g img { height:auto; }\n";
	$output .= "\t.g-col { position:relative; float:left; }\n";
	$output .= "\t.g-col:first-child { margin-left: 0; }\n";
	$output .= "\t.g-col:last-child { margin-right: 0; }\n";
	if($generated_css) {
		foreach($generated_css as $group_id => $css) {
			if(strlen($css) > 0) {
				$output .= $css;
			}
		}
		unset($generated_css);
	}
	$output .= "\t@media only screen and (max-width: 480px) {\n";
	$output .= "\t\t.g-col, .g-dyn, .g-single { width:100%; margin-left:0; margin-right:0; }\n";
	$output .= "\t}\n";
	if($adrotate_config['widgetpadding'] == "Y") {
		$output .= ".adrotate_widgets, .ajdg_bnnrwidgets, .ajdg_grpwidgets { overflow:hidden; padding:0; }\n";
	}
	$output .= "</style>\n";
	$output .= "<!-- /AdRotate CSS -->\n\n";

	echo $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_scripts
 Purpose:   Add required scripts to wp_enqueue_scripts (action)
-------------------------------------------------------------*/
function adrotate_scripts() {
	global $adrotate_config;

	$in_footer = ($adrotate_config['jsfooter'] == 'Y') ? true : false;

	if($adrotate_config['jquery'] == 'Y') {
		wp_enqueue_script('jquery', false, false, null, $in_footer);
	}

	if(get_option('adrotate_dynamic_required') > 0) {
		wp_enqueue_script('adrotate-groups', plugins_url('/library/jquery.groups.js', __FILE__), false, null, $in_footer);
	}

	if($adrotate_config['stats'] == 1) {
		wp_enqueue_script('adrotate-clicker', plugins_url('/library/jquery.clicker.js', __FILE__), false, null, $in_footer);
		wp_localize_script('adrotate-clicker', 'click_object', array('ajax_url' => admin_url('admin-ajax.php')));
		wp_localize_script('adrotate-groups', 'impression_object', array('ajax_url' => admin_url('admin-ajax.php')));
	}

	if(!$in_footer) {
		add_action('wp_head', 'adrotate_custom_javascript');
	} else {
		add_action('wp_footer', 'adrotate_custom_javascript', 100);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_javascript
 Purpose:   Add required JavaScript to adrotate_scripts()
 Since:		3.10.5
-------------------------------------------------------------*/
function adrotate_custom_javascript() {
	global $wpdb, $adrotate_config;

	$groups = $wpdb->get_results("SELECT `id`, `adspeed` FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `modus` = 1 ORDER BY `id` ASC;");
	if($groups) {
		$output = "<!-- AdRotate JS -->\n";
		$output .= "<script type=\"text/javascript\">\n";
		$output .= "jQuery(document).ready(function(){\n";
		$output .= "if(jQuery.fn.gslider) {\n";
		foreach($groups as $group) {
			$output .= "\tjQuery('.g-".$group->id."').gslider({ groupid: ".$group->id.", speed: ".$group->adspeed." });\n";
		}
		$output .= "}\n";
		$output .= "});\n";
		$output .= "</script>\n";
		$output .= "<!-- /AdRotate JS -->\n\n";
		unset($groups);
		echo $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_nonce_error
 Purpose:   Display a formatted error if Nonce fails
 Since:		3.7.4.2
-------------------------------------------------------------*/
function adrotate_nonce_error() {
	echo "	<h2 style=\"text-align: center;\">".__("Oh no! Something went wrong!", 'adrotate')."</h2>";
	echo "	<p style=\"text-align: center;\">".__("WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.", 'adrotate')."</p>";
	echo "	<p style=\"text-align: center;\">".__("If you have received the url you want to visit via email, you are being tricked!", 'adrotate')."</p>";
	echo "	<p style=\"text-align: center;\">".__("Contact support if the issue persists:", 'adrotate')." <a href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/\" title=\"AdRotate Support\" target=\"_blank\">AJdG Solutions Support</a>.</p>";
}

/*-------------------------------------------------------------
 Name:      adrotate_error
 Purpose:   Show errors for problems in using AdRotate, should they occur
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_error($action, $arg = null) {
	switch($action) {
		// Ads
		case 'ad_expired' :
			$result = "<!-- ".__("Error, Advert is not available at this time due to schedule/geolocation restrictions!", 'adrotate')." -->";
			return $result;
		break;

		case 'ad_unqualified' :
			$result = "<!-- ".__("Either there are no banners, they are disabled or none qualified for this location!", 'adrotate')." -->";
			return $result;
		break;

		case 'ad_no_id' :
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("Error, no Advert ID set! Check your syntax!", 'adrotate')."</span>";
			return $result;
		break;

		// Groups
		case 'group_no_id' :
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("Error, no group ID set! Check your syntax!", 'adrotate')."</span>";
			return $result;
		break;

		case 'group_not_found' :
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("Error, group does not exist! Check your syntax!", 'adrotate')." (ID: ".$arg[0].")</span>";
			return $result;
		break;

		// Database
		case 'db_error' :
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("There was an error locating the database tables for AdRotate. Please deactivate and re-activate AdRotate from the plugin page!!", 'adrotate')."<br />".__("If this does not solve the issue please seek support on the", 'adrotate')." <a href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/\">support forums</a></span>";
			return $result;
		break;

		// Possible XSS or malformed URL
		case 'error_loading_item' :
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("There was an error loading the page. Please try again by reloading the page via the menu on the left.", 'adrotate')."<br />".__("If the issue persists please seek help on the", 'adrotate')." <a href=\"https://ajdg.solutions/forums/forum/adrotate-for-wordpress/\">support forums</a></span>";
			return $result;
		break;

		// Misc
		default:
			$result = "<span style=\"font-weight: bold; color: #f00;\">".__("An unknown error occured.", 'adrotate')." (ID: ".$arg[0].")</span>";
			return $result;
		break;
	}
}
?>