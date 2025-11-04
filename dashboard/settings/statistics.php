<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */
?>

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=stats">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Statistics', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('How to track stats', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_stats">
					<strong><?php _e("Stats tracker to use:", 'adrotate'); ?></strong> <select name="adrotate_stats" tabindex="10">
						<option value="0" <?php echo ($adrotate_config['stats'] == 0) ? 'selected' : '';  ?>><?php _e("Do not track stats", 'adrotate'); ?></option>
						<option value="1" <?php echo ($adrotate_config['stats'] == 1) ? 'selected' : '';  ?>>AdRotate Counter (<?php _e("Default", 'adrotate'); ?>)</option>
						<option value="0" disabled>Matomo</option>
						<option value="0" disabled>Google Analytics 4</option>
						<option value="0" disabled>Google Tag Manager (<?php _e("Advanced", 'adrotate'); ?>)</option>
					</select>
				</label></p>

				<p>
					<strong>AdRotate Statistics</strong> - <?php _e("Tracks impressions and clicks locally", 'adrotate'); ?> - <a href="https://support.ajdg.net/knowledgebase.php?article=29&mtm_campaign=adrotate&mtm_keyword=settings_stats" target="_blank"><?php _e("Setup guide", 'adrotate'); ?></a>.<br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em><?php _e("Clicks and Impressions, Click and impression limits, impression spread for schedules. Javascript/HTML5 adverts will only track impressions.", 'adrotate'); ?></em>
				</p>

				<p>
					<strong>Matomo</strong> - <?php _e("Requires my Matomo Tracker plugin installed or the Matomo tracking code in your sites footer. See the manual for details.", 'adrotate'); ?> - <a href="https://support.ajdg.net/knowledgebase.php?article=31&mtm_campaign=adrotate&mtm_keyword=settings_stats" target="_blank"><?php _e("Setup guide", 'adrotate'); ?></a>.<br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em><?php _e("Clicks and Impressions via events. Javascript/HTML5 adverts will only track impressions.", 'adrotate'); ?></em>
				</p>

				<p>	
					<strong>Google Analytics</strong> - <?php _e("Requires the Google Global Tag from GA4 installed behind your sites head tag and a Google Analytics Account!", 'adrotate'); ?>  - <a href="https://support.ajdg.net/knowledgebase.php?article=33&mtm_campaign=adrotate&mtm_keyword=settings_stats" target="_blank"><?php _e("Setup guide", 'adrotate'); ?></a>.<br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em><?php _e("Clicks and Impressions via custom events you need to set up. Javascript/HTML5 adverts will only track impressions.", 'adrotate'); ?></em>
				</p>

				<p>
					<strong>Google Tag Manager</strong> - <?php _e("Requires Google Tag Manager installed in your sites head tag and a Google Analytics Account!", 'adrotate'); ?>  - <a href="https://support.ajdg.net/knowledgebase.php?article=32&mtm_campaign=adrotatepro&mtm_keyword=settings_stats" target="_blank"><?php _e("Setup guide", 'adrotate'); ?></a>.<br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em><?php _e("Clicks and Impressions via custom events. Javascript/HTML5 adverts will only track impressions.", 'adrotate'); ?></em>
				</p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('AdRotate Counter', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_enable_admin_stats">
					<strong><?php _e("Admin stats:", 'adrotate'); ?></strong> <select name="adrotate_enable_admin_stats" tabindex="20" disabled>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
					</select>
					<br /><small><?php _e("Track statistics from admin users.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_enable_loggedin_impressions">
					<strong><?php _e("Logged in impressions:", 'adrotate'); ?></strong> <select name="adrotate_enable_loggedin_impressions" tabindex="30" disabled>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
					</select>
					<br /><small><?php _e("Track impressions from logged in users.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_enable_loggedin_clicks">
					<strong><?php _e("Logged in clicks:", 'adrotate'); ?></strong> <select name="adrotate_enable_loggedin_clicks" tabindex="40" disabled>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
					</select>
					<br /><small><?php _e("Track clicks from logged in users.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_impression_timer">
					<strong><?php _e('Impression timer:', 'adrotate'); ?></strong> <input tabindex="50" name="adrotate_impression_timer" type="text" class="search-input"  size="8" value="<?php echo $adrotate_config['impression_timer']; ?>" autocomplete="off" /> <?php _e("Default: 60.", 'adrotate'); ?>
					<br /><small><?php _e("This number may not be empty, be lower than 10 or exceed 3600 (1 hour).", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_click_timer">
					<strong><?php _e('Click timer:', 'adrotate'); ?></strong> <input tabindex="60" name="adrotate_click_timer" type="text" class="search-input"  size="8" value="<?php echo $adrotate_config['click_timer']; ?>" autocomplete="off" /> <?php _e("Default: 86400.", 'adrotate'); ?>
					<br /><small><?php _e("This number may not be empty, be lower than 60 or exceed 86400 (24 hours).", 'adrotate'); ?></small>
				</label></p>
		
			</div>
		</div>

		<div class="ajdg-box">
			<p class="submit">
			  	<input type="submit" name="adrotate_save_options" class="button-primary" value="<?php _e("Save settings", 'adrotate'); ?>" tabindex="1000" /> <em><?php _e("Some settings are only available in AdRotate Pro!", 'adrotate'); ?></em>
			</p>
		</div>

	</div>
	<div class="ajdg-box-one">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Become an advertising professional', 'adrotate'); ?></h2>
			<div class="ajdg-box-content ajdg-box-sale">

				<a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><img src="<?php echo plugins_url("../images/offers/monetize-your-site.jpg", dirname(__FILE__)); ?>" alt="AdRotate Professional" width="100%"></a>
				<div class="title"><a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><?php _e("AdRotate Professional", 'adrotate'); ?></a></div>
				<div class="sub_title"><?php _e("Starting at only €49.00", 'adrotate'); ?><br /><a href="https://ajdg.solutions/product-category/adrotate-pro/" target="_blank">Compare Licenses</a></div>
				<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><?php _e("Get a Single site License", 'adrotate'); ?></a></div>
				<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-multi/" target="_blank"><?php _e("Go big with the Multi License", 'adrotate'); ?></a></div>
				<hr>
				<div class="description"><?php _e("AdRotate Professional has a lot more to offer for even better advertising management and premium support. Enjoy features like Geo Targeting, better Schedules offering more options, expanded Post Injection and much more.", 'adrotate'); ?></div>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('I make other plugins too', 'adrotate'); ?></h2>
			<div class="ajdg-box-content ajdg-box-sale">
	
				<a href="https://ajdg.solutions/plugins/" target="_blank"><img src="<?php echo plugins_url("../images/offers/more-plugins.jpg", dirname(__FILE__)); ?>" alt="AJdG Solutions Plugins" width="100%"></a>
				<div class="sub_title"><?php _e("For WordPress, WooCommerce and ClassicPress", 'adrotate'); ?></div>
				<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/plugins/" target="_blank"><?php _e("View all plugins", 'adrotate'); ?></a></div>
				<hr>
				<div class="description"><?php _e("Handy plugins that improve your WooCommerce online store; faster checkout, no checkout, improve security, automate things, payment stats and more!", 'adrotate'); ?></div>

			</div>
		</div>

	</div>
</div>

</form>