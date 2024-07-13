<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */
?>

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=maintenance">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<h2><?php _e("Maintenance", 'adrotate'); ?></h2>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e("Check adverts", 'adrotate'); ?></th>
		<td>
			<a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=check-all-ads'), 'maintenance', 'adrotate-nonce'); ?>" onclick="return confirm('<?php _e("You are about to check all adverts for errors.", 'adrotate'); ?>\n\n<?php _e("This might take a few seconds!", 'adrotate'); ?>\n\n<?php _e("OK to continue, CANCEL to stop.", 'adrotate'); ?>')"><?php _e("Check all adverts for configuration errors", 'adrotate'); ?></a>
			<br /><br />
			<span class="description"><?php _e("Apply all evaluation rules to all adverts to see if any error slipped in. This may take a few seconds.", 'adrotate'); ?></span>
		</td>
	</tr>
</table>

<h3><?php _e("Status and Versions", 'adrotate'); ?></h3>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e("Current status of adverts", 'adrotate'); ?></th>
		<td colspan="3"><?php _e("Normal", 'adrotate'); ?>: <?php echo $advert_status['normal']; ?>, <?php _e("Error", 'adrotate'); ?>: <?php echo $advert_status['error']; ?>, <?php _e("Expired", 'adrotate'); ?>: <?php echo $advert_status['expired']; ?>, <?php _e("Expires Soon", 'adrotate'); ?>: <?php echo $advert_status['expiressoon']; ?>, <?php _e("Unknown", 'adrotate'); ?>: <?php echo $advert_status['unknown']; ?>.</td>
	</tr>
	<tr>
		<th width="15%"><?php _e("Banners/assets Folder", 'adrotate'); ?></th>
		<td colspan="3">
			<?php
			echo WP_CONTENT_DIR."/".$adrotate_config['banner_folder']."/ -> ";
			echo (is_writeable(WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder']).'/') ? "<span style=\"color:#009900;\">".__("Exists and appears writable", 'adrotate')."</span>" : "<span style=\"color:#CC2900;\">".__("Not writable or does not exist", 'adrotate')."</span>";
			?>
		</td>
	</tr>
	<tr>
		<th width="15%"><?php _e("Reports Folder", 'adrotate'); ?></th>
		<td colspan="3">
			<?php
			echo WP_CONTENT_DIR."/reports/ -> ";
			echo (is_writable(WP_CONTENT_DIR.'/reports/')) ? "<span style=\"color:#009900;\">".__("Exists and appears writable", 'adrotate')."</span>" : "<span style=\"color:#CC2900;\">".__("Not writable or does not exist", 'adrotate')."</span>";
			?>
		</td>
	</tr>
	<tr>
		<th width="15%"><?php _e("Unfiltered HTML", 'adrotate'); ?></th>
		<td colspan="3">
			<?php
			if(defined('DISALLOW_UNFILTERED_HTML') && !DISALLOW_UNFILTERED_HTML) { 
				echo "<span style=\"color:#009900;\">".__("Allowed", 'adrotate')."</span>";
			} else {
				echo "<span style=\"color:#CC2900;\">".__("Your website's current setup does not allow for unfiltered code to be used in adverts.", 'adrotate')."</span><br />".__("This is required for javascript adverts to work. To enable this you need to set the DISALLOW_UNFILTERED_HTML definition to 'true' in your wp-config.php file.", 'adrotate')." <a href=\"https://ipstack.com/product\" target=\"_blank\">".__("More info", 'adrotate')."</a>";
			}
			?>
		</td>
	</tr>
	<tr>
		<th width="15%"><?php _e("Clean Trackerdata", 'adrotate'); ?></th>
		<td><?php echo (!$tracker) ? "<span style=\"color:#CC2900;\">"._e("Not scheduled!", 'adrotate')."</span>" : "<span style=\"color:#009900;\">".date_i18n(get_option('date_format')." H:i", $tracker)."</span>"; ?></td>
		<th width="15%">&nbsp;</th>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<th valign="top"><?php _e("Background tasks", 'adrotate'); ?></th>
		<td colspan="3">
			<a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=reset-tasks'), 'maintenance', 'adrotate-nonce'); ?>"><?php _e("Reset background tasks", 'adrotate'); ?></a>

			<br /><br />
			<span class="description"><em><?php _e("If automated tasks such as expiring adverts does not work reliable or one of the above schedules is missing use this button to reset the tasks.", 'adrotate'); ?></em></span>
		</td>
	</tr>
</table>

<h2><?php _e("Internal Versions", 'adrotate'); ?></h2>
<span class="description"><?php _e("Unless you experience database issues or a warning shows below, these numbers are not really relevant for troubleshooting. Support may ask for them to verify your database status.", 'adrotate'); ?></span>
<table class="form-table">			
	<tr>
		<th width="15%" valign="top"><?php _e("AdRotate version", 'adrotate'); ?></th>
		<td>
			<?php 
			echo __("Current:", 'adrotate')."<span style=\"color:#009900;\">".$adrotate_version['current']."</span>";
			if($adrotate_version['current'] != ADROTATE_VERSION) { 
				echo "<span style=\"color:#CC2900;\">".__("Should be:", 'adrotate')." ".ADROTATE_VERSION."</span>"; 
			} 
			?>
			<br /><?php echo __("Previous:", 'adrotate')." ".$adrotate_version['previous']; ?>
		</td>
		<th width="15%" valign="top"><?php _e("Database version", 'adrotate'); ?></th>
		<td>
			<?php 
			echo __("Current:", 'adrotate')."<span style=\"color:#009900;\">".$adrotate_db_version['current']."</span>";
			if($adrotate_db_version['current'] != ADROTATE_DB_VERSION) { 
				echo "<span style=\"color:#CC2900;\">".__("Should be:", 'adrotate')." ".ADROTATE_DB_VERSION."</span>";
			} 
			?>
			<br /><?php echo __("Previous:", 'adrotate')." ".$adrotate_db_version['previous']; ?>
		</td>
	</tr>
	<?php if($adrotate_db_version['current'] < ADROTATE_DB_VERSION OR $adrotate_version['current'] < ADROTATE_VERSION) { ?>
	<tr>
		<th valign="top"><?php _e("Manual upgrade", 'adrotate'); ?></th>
		<td colspan="3">
			<a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=update-db'), 'maintenance', 'adrotate-nonce'); ?>"><?php _e("Run updater manually", 'adrotate'); ?></a>

			<br /><br />
			<span class="description"><em><?php _e("If the automatic update for some reason doesn't complete. Or you notice that the above versions are mismatched you can update the database and settings using this button.", 'adrotate'); ?></em></span>
		</td>
	</tr>
	<?php } ?>
</table>

</form>
