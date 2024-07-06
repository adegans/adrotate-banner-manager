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
 Name:      adrotate_get_stats
 Purpose:   Quick check for the latest number of clicks and impressions
-------------------------------------------------------------*/
function adrotate_get_stats($ad, $when = 0, $until = 0) {
	global $wpdb;

	$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `{$wpdb->prefix}adrotate_stats` WHERE `ad` = {$ad} AND `thetime` >= {$when} AND `thetime` <= {$until} GROUP BY `ad` ORDER BY `ad` ASC;", ARRAY_A);

	if(empty($stats['clicks'])) $stats['clicks'] = '0';
	if(empty($stats['impressions'])) $stats['impressions'] = '0';

	return $stats;
}

/*-------------------------------------------------------------
 Name:      adrotate_date_start
 Purpose:   Get and return the localized UNIX time for the current hour, day and start of the week
-------------------------------------------------------------*/
function adrotate_date_start($what) {
	$now = current_time('timestamp');
	$string = gmdate('Y-m-d H:i:s', time());
	$timezone = get_option('timezone_string');
	preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);

	switch($what) {
		case 'hour' :
			$string_time = gmmktime($matches[4], 0, 0, $matches[2], $matches[3], $matches[1]);
			$result = gmdate('U', $string_time + (get_option('gmt_offset') * 3600));
		break;
		case 'day' :
			$result = gmdate('U', gmmktime(0, 0, 0, gmdate('n'), gmdate('j')));

			if($timezone) {
				$result = strtotime('00:00:00') + (get_option('gmt_offset') * 3600);
			}
		break;
		case 'week' :
			$result = gmdate('U', gmmktime(0, 0, 0));

			if($timezone) {
				$result = strtotime('Last Monday', $now) + (get_option('gmt_offset') * 3600);
			}
		break;
	}

	return $result;
}

/*-------------------------------------------------------------
 Name:      adrotate_count_impression
 Purpose:   Count Impressions where needed
-------------------------------------------------------------*/
function adrotate_count_impression($ad, $group = 0, $blog_id = 0) {
	global $wpdb, $adrotate_config;

	$now = current_time('timestamp');
	$today = adrotate_date_start('day');
	$remote_ip = adrotate_get_remote_ip();

	if($blog_id > 0 AND adrotate_is_networked()) {
		$current_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
	}

	$impression_timer = $now - $adrotate_config['impression_timer'];

	if($remote_ip != 'unknown' AND !empty($remote_ip)) {
		$saved_timer = $wpdb->get_var($wpdb->prepare("SELECT `timer` FROM `{$wpdb->prefix}adrotate_tracker` WHERE `ipaddress` = '%s' AND `stat` = 'i' AND `bannerid` = %d ORDER BY `timer` DESC LIMIT 1;", $remote_ip, $ad));
		if($saved_timer < $impression_timer AND adrotate_is_human()) {
			$stats = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM `{$wpdb->prefix}adrotate_stats` WHERE `ad` = %d AND `group` = %d AND `thetime` = {$today};", $ad, $group));
			if($stats > 0) {
				$wpdb->query("UPDATE `{$wpdb->prefix}adrotate_stats` SET `impressions` = `impressions` + 1 WHERE `id` = {$stats};");
			} else {
				$wpdb->insert($wpdb->prefix.'adrotate_stats', array('ad' => $ad, 'group' => $group, 'thetime' => $today, 'clicks' => 0, 'impressions' => 1));
			}

			$wpdb->insert($wpdb->prefix."adrotate_tracker", array('ipaddress' => $remote_ip, 'timer' => $now, 'bannerid' => $ad, 'stat' => 'i'));
		}
	}

	if($blog_id > 0 AND adrotate_is_networked()) {
		switch_to_blog($current_blog);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_impression_callback
 Purpose:   Register a impression for dynamic groups
-------------------------------------------------------------*/
function adrotate_impression_callback() {
	if(!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
	if(!defined('DONOTCACHEDB')) define('DONOTCACHEDB', true);
	if(!defined('DONOTCACHEOBJECT')) define('DONOTCACHEOBJECT', true);

	$meta = $_POST['track'];
	$meta = base64_decode($meta);

	$meta = esc_attr($meta);
	// Don't use $impression_timer - It's for impressions used in javascript
	list($ad, $group, $blog_id, $impression_timer) = explode(",", $meta, 4);
	if(is_numeric($ad) AND is_numeric($group) AND is_numeric($blog_id)) {
		adrotate_count_impression($ad, $group, $blog_id);
	}

	die();
}

/*-------------------------------------------------------------
 Name:      adrotate_click_callback
 Purpose:   Register clicks for clicktracking
-------------------------------------------------------------*/
function adrotate_click_callback() {
	if(!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
	if(!defined('DONOTCACHEDB')) define('DONOTCACHEDB', true);
	if(!defined('DONOTCACHEOBJECT')) define('DONOTCACHEOBJECT', true);

	global $wpdb, $adrotate_config;

	$meta = $_POST['track'];
	$meta = base64_decode($meta);

	$meta = esc_attr($meta);
	// Don't use $impression_timer - It's for impressions used in javascript
	list($ad, $group, $blog_id, $impression_timer) = explode(",", $meta, 4);

	if(is_numeric($ad) AND is_numeric($group) AND is_numeric($blog_id)) {
		if($blog_id > 0 AND adrotate_is_networked()) {
			$current_blog = $wpdb->blogid;
			switch_to_blog($blog_id);
		}

		$remote_ip = adrotate_get_remote_ip();

		if(adrotate_is_human() AND $remote_ip != "unknown" AND !empty($remote_ip)) {
			$now = current_time('timestamp');
			$today = adrotate_date_start('day');
			$click_timer = $now - $adrotate_config['click_timer'];

			$saved_timer = $wpdb->get_var($wpdb->prepare("SELECT `timer` FROM `{$wpdb->prefix}adrotate_tracker` WHERE `ipaddress` = '%s' AND `stat` = 'c' AND `bannerid` = %d ORDER BY `timer` DESC LIMIT 1;", $remote_ip, $ad));
			if($saved_timer < $click_timer) {
				$stats = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM `{$wpdb->prefix}adrotate_stats` WHERE `ad` = %d AND `group` = %d AND `thetime` = {$today};", $ad, $group));
				if($stats > 0) {
					$wpdb->query("UPDATE `{$wpdb->prefix}adrotate_stats` SET `clicks` = `clicks` + 1 WHERE `id` = {$stats};");
				} else {
					$wpdb->insert($wpdb->prefix.'adrotate_stats', array('ad' => $ad, 'group' => $group, 'thetime' => $today, 'clicks' => 1, 'impressions' => 1));
				}

				$wpdb->insert($wpdb->prefix.'adrotate_tracker', array('ipaddress' => $remote_ip, 'timer' => $now, 'bannerid' => $ad, 'stat' => 'c'));
			}

			// Advertising budget
			$wpdb->query("UPDATE `{$wpdb->prefix}adrotate` SET `budget` = `budget` - `crate` WHERE `id` = {$ad} AND `crate` > 0;");
		}

		if($blog_id > 0 AND adrotate_is_networked()) {
			switch_to_blog($current_blog);
		}

		unset($remote_ip, $track, $meta, $ad, $group, $remote, $banner);
	}

	die();
}
?>