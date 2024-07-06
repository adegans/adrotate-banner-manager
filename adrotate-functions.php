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
 Name:      adrotate_is_networked
 Purpose:   Determine if AdRotate is network activated
-------------------------------------------------------------*/
function adrotate_is_networked() {
	if(!function_exists('is_plugin_active_for_network')) require_once(ABSPATH.'/wp-admin/includes/plugin.php');

	if(is_plugin_active_for_network('adrotate/adrotate.php')) {
		return true;
	}
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_is_human
 Purpose:   Check if visitor is a bot
-------------------------------------------------------------*/
function adrotate_is_human() {
	global $adrotate_crawlers;

	if(is_array($adrotate_crawlers)) {
		$crawlers = $adrotate_crawlers;
	} else {
		$crawlers = array();
	}

	if(isset($_SERVER['HTTP_USER_AGENT'])) {
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$useragent = trim($useragent, ' \t\r\n\0\x0B');
	} else {
		$useragent = '';
	}

	$nocrawler = array(true);
	if(strlen($useragent) > 0) {
		foreach($crawlers as $key => $crawler) {
			if(preg_match('/'.$crawler.'/i', $useragent)) $nocrawler[] = false;
		}
	}
	$nocrawler = (!in_array(false, $nocrawler)) ? true : false; // If no bool false in array it's not a bot

	// Returns true if no bot.
	return $nocrawler;
}

/*-------------------------------------------------------------
 Name:      adrotate_filter_schedule
 Purpose:   Weed out ads that are over the limit of their schedule
-------------------------------------------------------------*/
function adrotate_filter_schedule($banner) {
	global $wpdb, $adrotate_config;

	$now = current_time('timestamp');

	// Get schedules for advert
	$schedules = $wpdb->get_results("SELECT `{$wpdb->prefix}adrotate_schedule`.`id`, `starttime`, `stoptime`, `maxclicks`, `maximpressions` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` AND `ad` = ".$banner['id']." ORDER BY `starttime` ASC LIMIT 1;");
	$schedule = $schedules[0];

	if($now < $schedule->starttime OR $now > $schedule->stoptime) {
		return true;
	} else {
		if($adrotate_config['stats'] == 1 AND $banner['tracker'] == 'Y') {
			$stat = adrotate_get_stats($banner['id'], $schedule->starttime, $schedule->stoptime);

			if($stat['clicks'] >= $schedule->maxclicks AND $schedule->maxclicks > 0) {
				return true;
			}

			if($stat['impressions'] >= $schedule->maximpressions AND $schedule->maximpressions > 0) {
				return true;
			}
		}
	}

	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_shuffle
 Purpose:   Randomize and slice an array but keep keys intact
-------------------------------------------------------------*/
function adrotate_shuffle($array) {
	if(!is_array($array)) return $array;

	$keys = array_keys($array);
	shuffle($keys);

	$shuffle = array();
	foreach($keys as $key) {
		$shuffle[$key] = $array[$key];
	}
	return $shuffle;
}

/*-------------------------------------------------------------
 Name:      adrotate_get_remote_ip
 Purpose:   Get the remote IP from the visitor
-------------------------------------------------------------*/
function adrotate_get_remote_ip(){
	if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$remote_ip = $_SERVER['REMOTE_ADDR'];
	} else {
		$remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	$buffer = explode(',', $remote_ip, 2);

	// Sanitize
    $buffer[0] = preg_replace('/[^0-9.]+/', '', $buffer[0]);

	return $buffer[0];
}

/*-------------------------------------------------------------
 Name:      adrotate_empty_trackerdata
 Purpose:   Removes old statistics
-------------------------------------------------------------*/
function adrotate_empty_trackerdata() {
	global $wpdb;

	$clicks = current_time('timestamp') - DAY_IN_SECONDS;
	$impressions = current_time('timestamp') - HOUR_IN_SECONDS;

	$wpdb->query("DELETE FROM `{$wpdb->prefix}adrotate_tracker` WHERE `timer` < {$impressions} AND `stat` = 'i';");
	$wpdb->query("DELETE FROM `{$wpdb->prefix}adrotate_tracker` WHERE `timer` < {$clicks} AND `stat` = 'c';");
	$wpdb->query("DELETE FROM `{$wpdb->prefix}adrotate_tracker` WHERE `ipaddress`  = 'unknown' OR `ipaddress`  = '';");
}

/*-------------------------------------------------------------
 Name:      adrotate_apply_jetpack_photon
 Purpose:   Use Jetpack Photon if possible
-------------------------------------------------------------*/
function adrotate_apply_jetpack_photon($image) {
	if(class_exists('Jetpack_Photon') AND Jetpack::is_module_active('photon') AND function_exists('jetpack_photon_url')) {
		return jetpack_photon_url($image);
	} else {
		return $image;
	}
}
?>