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

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=roles">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Access Roles', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Permissions', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_ad_manage">
					<strong><?php _e("Manage/Add/Edit adverts:", 'adrotate'); ?></strong> <select name="adrotate_ad_manage" tabindex="10">
						<?php adrotate_dropdown_roles($adrotate_config['ad_manage']); ?>
					</select><br />
					<small><?php _e("Who can add and edit adverts?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_ad_delete">
					<strong><?php _e("Delete/Reset adverts:", 'adrotate'); ?></strong> <select name="adrotate_ad_delete" tabindex="20">
						<?php adrotate_dropdown_roles($adrotate_config['ad_delete']); ?>
					</select><br />
					<small><?php _e("Who can delete adverts and reset advert stats?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_group_manage">
					<strong><?php _e("Manage/Add/Edit groups:", 'adrotate'); ?></strong> <select name="adrotate_group_manage" tabindex="30">
						<?php adrotate_dropdown_roles($adrotate_config['group_manage']); ?>
					</select><br />
					<small><?php _e("Who can add and edit groups?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_group_delete">
					<strong><?php _e("Delete groups:", 'adrotate'); ?></strong> <select name="adrotate_group_delete" tabindex="40">
						<?php adrotate_dropdown_roles($adrotate_config['group_delete']); ?>
					</select><br />
					<small><?php _e("Who can add and edit groups?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_schedule_manage">
					<strong><?php _e("Manage/Add/Edit schedules:", 'adrotate'); ?></strong> <select name="adrotate_schedule_manage" disabled>
						<?php adrotate_dropdown_roles($adrotate_config['schedule_manage']); ?>
					</select><br />
					<small><?php _e("Who can add and edit schedules?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_schedule_delete">
					<strong><?php _e("Delete schedules:", 'adrotate'); ?></strong> <select name="adrotate_schedule_delete" disabled>
						<?php adrotate_dropdown_roles($adrotate_config['schedule_delete']); ?>
					</select><br />
					<small><?php _e("Who can delete schedules?", 'adrotate'); ?></small>
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