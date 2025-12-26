<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2026 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */
?>

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=maintenance">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Maintenance', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Status', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php _e("Advert status:", 'adrotate'); ?></strong><br />
					<strong><?php _e("Normal", 'adrotate'); ?></strong> -> <?php echo $advert_status['normal']; ?><br />
					<strong><?php _e("Error", 'adrotate'); ?></strong> -> <?php echo $advert_status['error']; ?><br />
					<strong><?php _e("Expired", 'adrotate'); ?></strong> -> <?php echo $advert_status['expired']; ?><br />
					<strong><?php _e("Expires Soon", 'adrotate'); ?></strong> -> <?php echo $advert_status['expiressoon']; ?><br />
					<strong><?php _e("Unknown", 'adrotate'); ?></strong> -> <?php echo $advert_status['unknown']; ?>
				</p>

				<p>
					<strong><?php esc_html_e("Banners/assets Folder", 'adrotate'); ?></strong><br />
					<?php
					echo WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder'].'/ -> ';
					echo (is_writeable(WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder'].'/')) ? '<span style="color:#009900;">'.esc_html__("Exists and appears writable", 'adrotate').'</span>' : '<span style="color:#CC2900;">'.esc_html__("Not writable or does not exist", 'adrotate').'</span>';
					?>
				</p>

				<p>
					<strong><?php _e("Reports folder:", 'adrotate'); ?></strong><br />
					<?php
					echo WP_CONTENT_DIR.'/'.$adrotate_config['report_folder'].'/ -> ';
					echo (is_writable(WP_CONTENT_DIR.'/'.$adrotate_config['report_folder'].'/')) ? '<span style="color:#009900;">'.esc_html__("Exists and appears writable", 'adrotate').'</span>' : '<span style="color:#CC2900;">'.esc_html__("Not writable or does not exist", 'adrotate').'</span>';
					echo '<br />';
					echo WP_CONTENT_DIR.'/'.$adrotate_config['report_folder'].'/index.html -> ';
					echo (file_exists(WP_CONTENT_DIR.'/'.$adrotate_config['report_folder'].'/index.html')) ? '<span style="color:#009900;">'.esc_html__("Exists", 'adrotate').'</span>' : '<span style="color:#CC2900;">'.esc_html__("Not found (Required for security)", 'adrotate').'</span>';
					?>
				</p>

				<p>
					<strong><?php esc_html_e("Unfiltered HTML:", 'adrotate'); ?></strong> 
					<?php
					if(defined('DISALLOW_UNFILTERED_HTML') AND !DISALLOW_UNFILTERED_HTML) {
						echo "<span style=\"color:#009900;\">".esc_html__("Excellent! Editors, Administrators and Superadmins can use unfiltered code.", 'adrotate')."</span>";
					} else {
						echo "<span style=\"color:#CC2900;\">".esc_html__("Your website's current setup does not allow for unfiltered code to be used in adverts.", 'adrotate')."</span><br />".esc_html__("This is required for javascript adverts to work. To enable this you need to set the DISALLOW_UNFILTERED_HTML definition to 'false' in your wp-config.php file.", 'adrotate')." <a href=\"https://support.ajdg.net/knowledgebase.php?article=43\" target=\"_blank\">".esc_html__("How to enable unfiltered html", 'adrotate')."</a>?";
					}
					?>
				</p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Background tasks', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php esc_html_e("Delete expired trackerdata:", 'adrotate'); ?></strong> <?php if(!$tracker) '<span style="color:#CC2900;">'._e("Not scheduled!", 'adrotate').'</span>'; else echo '<span style="color:#009900;">'.date_i18n(get_option('date_format')." H:i", $tracker).'</span>'; ?>
				</p>
				
				<p><em><?php _e("The listed date is the next time the task is supposed to run.", 'adrotate'); ?></em></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Maintenance', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php _e('Check adverts:', 'adrotate'); ?></strong> <span class="alignright"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=check-all-ads'), 'maintenance', 'adrotate-nonce'); ?>" onclick="return confirm('<?php _e("You are about to check all adverts for errors.", 'adrotate'); ?>\n\n<?php _e("This might take a few seconds!", 'adrotate'); ?>\n\n<?php _e("OK to continue, CANCEL to stop.", 'adrotate'); ?>')"><?php _e("Check all adverts for configuration errors", 'adrotate'); ?></a></span><br />
					<small><?php _e("Apply all evaluation rules to all adverts to see if any error slipped in. This may take a few seconds.", 'adrotate'); ?></small>
				</p>

				<p>
					<strong><?php _e('Background tasks:', 'adrotate'); ?></strong> <span class="alignright"><a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=reset-tasks'), 'maintenance', 'adrotate-nonce'); ?>"><?php _e("Reset background tasks", 'adrotate'); ?></a></span><br />
					<small><?php _e("If automated tasks such as expiring adverts does not work reliably or one of the schedules listed on this page is missing, use this button to reset the tasks.", 'adrotate'); ?></small>
				</p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Internal Versions', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php _e("AdRotate version:", 'adrotate'); ?></strong><br />
					<?php
					echo __("Current:", 'adrotate')."<span style=\"color:#009900;\">".$adrotate_version['current']."</span>";
					if($adrotate_version['current'] != ADROTATE_VERSION) {
						echo "<span style=\"color:#CC2900;\">".__("Should be:", 'adrotate')." ".ADROTATE_VERSION."</span>";
					}
					echo " - ".__("Previous:", 'adrotate')." ".$adrotate_version['previous']; ?>
				</p>
		
				<p>
					<strong><?php _e("Database version:", 'adrotate'); ?></strong><br />
					<?php
					echo __("Current:", 'adrotate')."<span style=\"color:#009900;\">".$adrotate_db_version['current']."</span>";
					if($adrotate_db_version['current'] != ADROTATE_DB_VERSION) {
						echo "<span style=\"color:#CC2900;\">".__("Should be:", 'adrotate')." ".ADROTATE_DB_VERSION."</span>";
					}
					echo " - ".__("Previous:", 'adrotate')." ".$adrotate_db_version['previous']; ?>
				</p>

				<?php if($adrotate_db_version['current'] < ADROTATE_DB_VERSION OR $adrotate_version['current'] < ADROTATE_VERSION) { ?>
				<p>
					<strong><?php _e("Manual upgrade:", 'adrotate'); ?></strong> <a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=update-db'), 'maintenance', 'adrotate-nonce'); ?>"><?php _e("Run updater manually", 'adrotate'); ?></a>
				</p>
				<p>
					<em><?php _e("If the automatic update for some reason doesn't complete. Or you notice that the above versions are mismatched you can update the database and settings using this button.", 'adrotate'); ?></em>
				</p>
				<?php } ?>

			</div>
		</div>

	</div>
	<div class="ajdg-box-one">

		<?php include_once(dirname(__DIR__).'/sidebar.php'); ?>

	</div>
</div>

</form>