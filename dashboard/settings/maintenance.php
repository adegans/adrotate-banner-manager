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

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=misc">
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
					<strong><?php esc_html_e("Banners/assets Folder", 'adrotate-pro'); ?></strong><br />
					<?php
					echo WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder'].'/ -> ';
					echo (is_writeable(WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder']).'/') ? '<span style="color:#009900;">'.esc_html__("Exists and appears writable", 'adrotate-pro').'</span>' : '<span style="color:#CC2900;">'.esc_html__("Not writable or does not exist", 'adrotate-pro').'</span>';
					?>
				</p>

				<p>
					<strong><?php _e("Reports folder:", 'adrotate'); ?></strong><br />
					<?php
					echo WP_CONTENT_DIR.'/reports/'.' -> ';
					echo (is_writable(WP_CONTENT_DIR.'/reports/')) ? '<span style="color:#009900;">'.esc_html__("Exists and appears writable", 'adrotate-pro').'</span>' : '<span style="color:#CC2900;">'.esc_html__("Not writable or does not exist", 'adrotate-pro').'</span>';
					?>
				</p>

				<p>
					<strong><?php esc_html_e("Unfiltered HTML:", 'adrotate-pro'); ?></strong> 
					<?php
					if(defined('DISALLOW_UNFILTERED_HTML') AND !DISALLOW_UNFILTERED_HTML) {
						echo "<span style=\"color:#009900;\">".esc_html__("Excellent! Editors, Administrators and Superadmins can use unfiltered code.", 'adrotate-pro')."</span>";
					} else {
						echo "<span style=\"color:#CC2900;\">".esc_html__("Your website's current setup does not allow for unfiltered code to be used in adverts.", 'adrotate-pro')."</span><br />".esc_html__("This is required for javascript adverts to work. To enable this you need to set the DISALLOW_UNFILTERED_HTML definition to 'false' in your wp-config.php file.", 'adrotate-pro')." <a href=\"https://support.ajdg.net/knowledgebase.php?article=43\" target=\"_blank\">".esc_html__("How to enable unfiltered html", 'adrotate-pro')."</a>?";
					}
					?>
				</p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Background tasks', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php esc_html_e("Delete expired trackerdata:", 'adrotate-pro'); ?></strong> <?php if(!$tracker) '<span style="color:#CC2900;">'._e("Not scheduled!", 'adrotate-pro').'</span>'; else echo '<span style="color:#009900;">'.date_i18n(get_option('date_format')." H:i", $tracker).'</span>'; ?>
				</p>
				
				<p><em><?php _e("The listed date is the next time the task is supposed to run.", 'adrotate'); ?></em></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Maintenance', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<strong><?php _e('Check adverts:', 'adrotate'); ?></strong> <a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=check-all-ads'), 'maintenance', 'adrotate-nonce'); ?>" onclick="return confirm('<?php _e("You are about to check all adverts for errors.", 'adrotate'); ?>\n\n<?php _e("This might take a few seconds!", 'adrotate'); ?>\n\n<?php _e("OK to continue, CANCEL to stop.", 'adrotate'); ?>')"><?php _e("Check all adverts for configuration errors", 'adrotate'); ?></a><br />
					<small><?php _e("Apply all evaluation rules to all adverts to see if any error slipped in. This may take a few seconds.", 'adrotate'); ?></small>
				</p>

				<p>
					<strong><?php _e('Background tasks:', 'adrotate'); ?></strong> <a class="button" href="<?php echo wp_nonce_url(admin_url('admin.php?page=adrotate-settings&tab=maintenance&action=reset-tasks'), 'maintenance', 'adrotate-nonce'); ?>"><?php _e("Reset background tasks", 'adrotate'); ?></a><br />
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