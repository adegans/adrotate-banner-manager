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

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=geo">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Geo Targeting', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Geo Targeting service', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_enable_geo">
					<strong><?php _e("Geo service to use:", 'adrotate'); ?></strong> <select name="adrotate_enable_geo" tabindex="10">
						<option value="0"><?php _e("No Geo Targeting", 'adrotate'); ?></option>
						<option value="0" disabled>AdRotate Geo</option>
						<option value="0" disabled>ipstack</option>
						<option value="0" disabled>MaxMind City</option>
						<option value="0" disabled>MaxMind Country</option>
						<option value="0" disabled>CloudFlare</option>
					</select>
				</label></p>
				
				<p>
					<strong>AdRotate Geo</strong> - <?php _e("30000 free lookups every day, uses GeoLite2 databases from MaxMind!", 'adrotate'); ?><br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em>ipv4/ipv6, Countries, Cities, DMA codes, States and State ISO (3166-2) codes.</em><br />
					<strong><?php _e("Scalability:", 'adrotate'); ?></strong> <em><?php _e("Suitable for small to medium sized websites.", 'adrotate'); ?></em>
				</p>

				<p>
					<strong>ipstack</strong> (<a href="https://ipstack.com/product" target="_blank>"><?php _e("More info", 'adrotate'); ?></a>) - <?php _e("10000 free lookups per month, requires account.", 'adrotate'); ?><br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em>ipv4, Countries, Cities, DMA codes, States and State ISO (3166-2) codes.</em><br />
					<strong><?php _e("Scalability:", 'adrotate'); ?></strong> <em><?php _e("Suitable for small to medium sized websites with a free account - Paid options available.", 'adrotate'); ?></em>
				</p>

				<p>
					<strong>MaxMind GeoIP2</strong> (<a href="https://www.maxmind.com/en/geoip2-precision-city-service" target="_blank>"><?php _e("More info", 'adrotate'); ?></a>)- <?php _e("The most accurate geo targeting available.", 'adrotate'); ?><br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em>ipv4/ipv6, Countries, States, State ISO (3166-2) codes, Cities and DMA codes.</em><br />
					<strong><?php _e("Scalability:", 'adrotate'); ?></strong> <em><?php _e("Suitable for any size website as long as you have lookups.", 'adrotate'); ?></em>
				</p>
				
				<p>
					<strong>CloudFlare IP Geolocation</strong> - <?php _e("Basic geolocation included in every CloudFlare account.", 'adrotate'); ?><br />
					<strong><?php _e("Supports:", 'adrotate'); ?></strong> <em>ipv4/ipv6, Countries.</em><br />
					<strong><?php _e("Scalability:", 'adrotate'); ?></strong> <em><?php _e("Suitable for any size website.", 'adrotate'); ?></em>
				</p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('MaxMind City/Country and ipstack', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_geo_email">
					<strong><?php _e('User ID:', 'adrotate'); ?></strong> <input tabindex="20" name="adrotate_geo_email" type="text" class="search-input" style="width:100%;" value="" autocomplete="off" disabled />
					<br /><small><?php _e("Only used for MaxMind accounts.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_geo_pass">
					<strong><?php _e('License Key/API Key:', 'adrotate'); ?></strong> <input tabindex="30" name="adrotate_geo_pass" type="text" class="search-input" style="width:100%;" value="" autocomplete="off" disabled />
					<br /><small><?php _e("Used for Maxmind and ipstack accounts.", 'adrotate'); ?></small>
				</label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<p class="submit">
			  	<input type="submit" name="adrotate_save_options" class="button-primary" value="<?php _e("Save settings", 'adrotate'); ?>" disabled /> <em><?php _e("Some settings are only available in AdRotate Pro!", 'adrotate'); ?></em>
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