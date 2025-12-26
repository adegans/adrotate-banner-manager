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

<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=general">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('General Settings', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Settings', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_duplicate_adverts_filter">
					<strong><?php _e("Duplicate adverts:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_duplicate_adverts_filter" tabindex="10" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Try and prevent adverts in groups that are in Default or Block mode from showing multiple times on the same page load.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_textwidget_shortcodes">
					<strong><?php _e("Shortcode in widgets:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_textwidget_shortcodes" tabindex="20" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Try and activate shortcodes in text widgets if your theme does not add support for it by itself. (This does not always work!)", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_live_preview">
					<strong><?php _e("Advert live preview:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_live_preview" tabindex="30" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Disable live previews for adverts if you have faulty adverts that overflow their designated area while creating/editing adverts.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_jquery">
					<strong><?php _e("Load jQuery:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_jquery" tabindex="40">
						<option <?php echo ($adrotate_config['jquery'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['jquery'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Load jQuery if your theme does not load it already. jQuery is required for dynamic groups, statistics and some other features.", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_jsfooter">
					<strong><?php _e("Load scripts in footer:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_jsfooter" tabindex="50">
						<option <?php echo ($adrotate_config['jsfooter'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['jsfooter'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Load all AdRotate Javascripts in the footer of your site.", 'adrotate'); ?></small>
				</label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Adblock disguise', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_adblock_disguise">
					<strong><?php _e('Random string:', 'adrotate'); ?></strong> <input tabindex="60" name="adrotate_adblock_disguise" type="text" class="search-input"  size="20" value="<?php echo $adrotate_config['adblock_disguise'];?>" autocomplete="off" disabled /> <?php _e("Default: (empty).", 'adrotate'); ?>
					<br /><small><?php _e("A random string of ~6 characters. For example:", 'adrotate'); ?> "<?php echo adrotate_rand(6); ?>".<br />
					<?php _e("This makes the standardized output of AdRotate harder to detect by adblockers when using shortcodes.", 'adrotate'); ?> <?php _e("To also apply this feature to widgets, use a text widget or block with a shortcode instead of the AdRotate widget.", 'adrotate'); ?> <?php _e("Avoid the use of obvious keywords or filenames in your adverts or this feature will have less effect!", 'adrotate'); ?></small>
				</label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Banner Folder', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><strong>Currently:</strong> <?php echo WP_CONTENT_DIR; ?>/<strong><?php echo $adrotate_config['banner_folder'];?></strong>/</p>
				<p><label for="adrotate_banner_folder"><strong><?php _e('Folder name:', 'adrotate'); ?></strong> <input tabindex="70" name="adrotate_banner_folder" type="text" class="search-input" size="20" value="<?php echo $adrotate_config['banner_folder'];?>" autocomplete="off" disabled /> <?php _e("Default: banners.", 'adrotate'); ?><br /><small><?php _e("To try and trick ad blockers you could set the folder to something crazy like:", 'adrotate'); ?> "<?php echo adrotate_rand(12); ?>".<br />
		<?php _e("This folder will not be automatically created if it doesn't exist. AdRotate will show errors when the folder is missing.", 'adrotate'); ?></small></label></p>


			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e("Reports Folder", 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><strong>Currently:</strong> <?php echo WP_CONTENT_DIR; ?>/<strong><?php echo $adrotate_config['report_folder']; ?></strong>/</p>
				<p><label for="adrotate_report_folder"><strong><?php _e("Folder name:", 'adrotate'); ?></strong> <input tabindex="70" name="adrotate_report_folder" type="text" class="search-input" size="20" value="<?php echo $adrotate_config['report_folder']; ?>" autocomplete="off" disabled /> <?php _e("Default: reports.", 'adrotate'); ?><br />
				<small><?php _e("This is where exported stats get stored so you can download it.", 'adrotate'); ?><br />
		<?php _e("This folder will not be automatically created if it doesn't exist. AdRotate will show errors when the folder is missing.", 'adrotate'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('ads.txt file', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><strong>Currently AdRotate looks for it here:</strong> <?php echo get_site_url(); ?>/<?php echo $adrotate_config['adstxt_file'];?>ads.txt</p>
				<p><label for="adrotate_adstxt_file"><strong><?php _e('Location:', 'adrotate'); ?></strong> <input tabindex="80" name="adrotate_adstxt_file" type="text" class="search-input" size="20" value="<?php echo $adrotate_config['adstxt_file'];?>" autocomplete="off" disabled /> <?php _e("Default: (empty).", 'adrotate'); ?><br /><small><?php _e("Commonly the ads.txt file is in the root of your site and this setting does not need to be changed.", 'adrotate'); ?><br /><?php _e("If you redirect the ads.txt you can enter the new location here. Otherwise this field should be left empty.", 'adrotate'); ?></small></label></p>

			</div>
		</div>

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Bot filter / User-Agent Filter', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_crawlers"><textarea name="adrotate_crawlers" style="width:100%;" rows="10" tabindex="90"><?php echo $crawlers; ?></textarea><br /><small><?php _e("Comma separated keywords. Only words with alphanumeric characters and [ - _ ] are accepted.", 'adrotate'); ?><br /><?php _e("Keep in mind that adding simplistic or generic keywords may give false positives. The word 'fire' also matches 'firefox' for example. However", 'adrotate'); ?><br />
		<?php _e("Additionally to the list specified here, empty User-Agents are blocked.", 'adrotate'); ?> (<?php _e("Learn more about", 'adrotate'); ?> <a href="http://en.wikipedia.org/wiki/User_agent" title="User Agents" target="_blank"><?php _e("user-agents", 'adrotate'); ?></a>.)</small></label></p>

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