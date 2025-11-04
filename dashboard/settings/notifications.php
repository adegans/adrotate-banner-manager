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

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=notifications">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Notifications', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Dashboard Banner', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><?php _e("These may show in a dashboard banner visible to all users who have access to AdRotate.", 'adrotate'); ?></p>

				<p><label for="adrotate_notification_dash">
					<strong><?php _e("Show a dashboard notification:", 'adrotate'); ?></strong> <select name="adrotate_notification_dash" tabindex="10">
						<option <?php echo ($adrotate_notifications['notification_dash'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_notifications['notification_dash'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("Notifications for adverts with configuration errors can not be turned off.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_dash_expired">
					<strong><?php _e("Expired adverts:", 'adrotate'); ?></strong> <select name="adrotate_notification_dash_expired" tabindex="20">
						<option <?php echo ($adrotate_notifications['notification_dash_expired'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_notifications['notification_dash_expired'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
					</select>
				</label></p>

				<p><label for="adrotate_notification_dash_soon">
					<strong><?php _e("Adverts expiring in less than 2 days:", 'adrotate'); ?></strong> <select name="adrotate_notification_dash_soon" tabindex="30">
						<option <?php echo ($adrotate_notifications['notification_dash_soon'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_notifications['notification_dash_soon'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
					</select>
				</label></p>

				<p><label for="adrotate_notification_dash_week">
					<strong><?php _e("Adverts expiring in less than 7 days:", 'adrotate'); ?></strong> <select name="adrotate_notification_dash_week" tabindex="40" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select>
				</label></p>

				<p><label for="adrotate_notification_schedules">
					<strong><?php _e("Schedules with warnings:", 'adrotate'); ?></strong> <select name="adrotate_notification_schedules" tabindex="50" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select>
				</label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Email Message', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_notification_email">
					<strong><?php _e("Send alert emails:", 'adrotate'); ?></strong> <select name="adrotate_notification_email" tabindex="60" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="N"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("Choose if you want to be notified via email for common issues with adverts.", 'adrotate'); ?></small>

				</label></p>

				<p><label for="adrotate_notification_email_publisher">
					<strong><?php _e("E-mail addresses to notify:", 'adrotate'); ?></strong><br />
					<textarea name="adrotate_notification_email_publisher" style="width:100%;" rows="1" tabindex="70" disabled><?php echo get_option('admin_email'); ?></textarea><br />
					<small><?php _e("Messages are sent once every 24 hours.  Maximum of 5 addresses. Comma separated. This field may not be empty!", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_mail_status">
					<strong><?php _e("Daily digest:", 'adrotate'); ?></strong> <select name="adrotate_notification_mail_status" tabindex="80" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("Daily digest of any advert status other than normal.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_mail_geo">
					<strong><?php _e("Geo Targeting:", 'adrotate'); ?></strong> <select name="adrotate_notification_mail_geo" tabindex="90" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("When you are running out of Geo Targeting Lookups.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_mail_queue">
					<strong><?php _e("Advertisers:", 'adrotate'); ?></strong> <select name="adrotate_notification_mail_queue" tabindex="100" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("Any advertiser saving an advert in your moderation queue.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_mail_approved">
					<strong><?php _e("Moderator approval:", 'adrotate'); ?></strong> <select name="adrotate_notification_mail_approved" tabindex="110" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("A moderator approved an advert from the moderation queue.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_notification_mail_rejected">
					<strong><?php _e("Moderator rejection:", 'adrotate'); ?></strong> <select name="adrotate_notification_mail_rejected" tabindex="120" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select><br />
					<small><?php _e("A moderator rejected an advert from the moderation queue.", 'adrotate'); ?></small>
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