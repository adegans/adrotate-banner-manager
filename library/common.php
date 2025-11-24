<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
---------------------------------------------------------------------------------------
*  This file includes common functions used by several plugins
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 AJdG Solutions common functions used by several plugins
---------------------------------------------------------------
 Changelog:
---------------------------------------------------------------
1.0.1 - November 21, 2025
	* updated - Arnan.me RSS url
1.0 - November 7, 2025
	* added - RSS feed reader
-------------------------------------------------------------*/

/*-------------------------------------------------------------
 Name:      ajdg_fetch_rss_feed
 Purpose:   Load one of more RSS feeds to show in the AdRotate dashboard. Cache it for a day.
 Note: 		Cache is shared between plugins!
-------------------------------------------------------------*/
if(!function_exists('ajdg_fetch_rss_feed')) {
	function ajdg_fetch_rss_feed($show_items = 10) {
		// Is there a cached feed? Use it!
		$ajdg_feeds = get_transient('ajdg_news_feeds');
		if($ajdg_feeds) {
			return $ajdg_feeds;
		}
	
		// Download feed(s)
		$rss = fetch_feed(array(
			'http://ajdg.solutions/feed/', 
			'https://www.arnan.me/feed/rss'
		));
	
		// Check for errors
		if(!is_numeric($show_items) OR $show_items < 1 OR $show_items > 20) {
			$show_items = 10;
		}
	
		if(is_wp_error($rss)) {
			if(is_admin() OR current_user_can('manage_options')) {
				$output = '<p><strong>' . __('RSS Error:') . '</strong> ' . esc_html($rss->get_error_message()) . '</p>';
				return $output;
			}
	
			return;
		}
	
		if(!$rss->get_item_quantity()) {
			$output = '<ul><li>' . __('An error has occurred, which probably means the feed is down. Try again later.') . '</li></ul>';
	
			$rss->__destruct();
			unset($rss);
	
			return $output;
		}
	
		// Prepare output
		$feed_output = '<ul>';
		foreach($rss->get_items(0, $show_items) as $item) {
			$link = $item->get_link();
	
			while(!empty($link) AND stristr($link, 'http') !== $link) {
				$link = substr($link, 1);
			}
	
			$link = esc_url(strip_tags($link));
			$title = esc_html(trim(strip_tags($item->get_title())));
			$date = $item->get_date('U');
	
			if(empty($title)) $title = __('Untitled');
			if($date) $date = ' <span class="rss-date">'.date_i18n(get_option('date_format'), $date).'</span>';
	
			$feed_output .= (empty($link)) ? "<li>$title<br /><em>{$date}</em></li>" : "<li><a class='rsswidget' href='$link'>$title</a><br /><em>{$date}</em></li>";
		}
		$feed_output .= '</ul>';
	
		// Cache output
		set_transient('ajdg_news_feeds', $feed_output, 86400);
	
		// Cleanup
		$rss->__destruct();
		unset( $rss );
	
		// Done!
		return $feed_output;
	}
}