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

				<p><label for="adrotate_global_report">
					<strong><?php _e("Statistics page:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_global_report" disabled>
						<?php wp_dropdown_roles($adrotate_config['global_report']); ?>
					</select></span><br />
					<small><?php _e("Who can access the Statistics dashboard page?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_ad_manage">
					<strong><?php _e("Manage/Add/Edit adverts:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_ad_manage" tabindex="10">
						<?php wp_dropdown_roles($adrotate_config['ad_manage']); ?>
					</select></span><br />
					<small><?php _e("Who can add and edit adverts?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_ad_delete">
					<strong><?php _e("Delete/Reset adverts:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_ad_delete" tabindex="20">
						<?php wp_dropdown_roles($adrotate_config['ad_delete']); ?>
					</select></span><br />
					<small><?php _e("Who can delete adverts and reset advert stats?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_group_manage">
					<strong><?php _e("Manage/Add/Edit groups:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_group_manage" tabindex="30">
						<?php wp_dropdown_roles($adrotate_config['group_manage']); ?>
					</select></span><br />
					<small><?php _e("Who can add and edit groups?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_group_delete">
					<strong><?php _e("Delete groups:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_group_delete" tabindex="40">
						<?php wp_dropdown_roles($adrotate_config['group_delete']); ?>
					</select></span><br />
					<small><?php _e("Who can add and edit groups?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_schedule_manage">
					<strong><?php _e("Manage/Add/Edit schedules:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_schedule_manage" disabled>
						<?php wp_dropdown_roles($adrotate_config['schedule_manage']); ?>
					</select></span><br />
					<small><?php _e("Who can add and edit schedules?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_schedule_delete">
					<strong><?php _e("Delete schedules:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_schedule_delete" disabled>
						<?php wp_dropdown_roles($adrotate_config['schedule_delete']); ?>
					</select></span><br />
					<small><?php _e("Who can delete schedules?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_advertiser_manage">
					<strong><?php _e("Manage advertisers:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_advertiser_manage" disabled>
						<?php wp_dropdown_roles($adrotate_config['advertiser_manage']); ?>
					</select></span><br />
					<small><?php _e("Who can see and manage advertisers?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_moderate">
					<strong><?php _e("Moderate new adverts:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_moderate" disabled>
						<?php wp_dropdown_roles($adrotate_config['moderate']); ?>
					</select></span><br />
					<small><?php _e("Who can approve adverts submitted by advertisers?", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_moderate_approve">
					<strong><?php _e("Moderation Queue:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_moderate_approve" disabled>
						<?php wp_dropdown_roles($adrotate_config['moderate_approve']); ?>
					</select></span><br />
					<small><?php _e("Who can approve or reject adverts submitted by advertisers?", 'adrotate'); ?></small>
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

		<?php include_once(dirname(__DIR__).'/sidebar.php'); ?>

	</div>
</div>

</form>