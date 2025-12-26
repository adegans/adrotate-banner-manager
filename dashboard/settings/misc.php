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
<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings&tab=misc">
<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

<h1><?php _e('Miscellaneous', 'adrotate'); ?></h1>

<div class="ajdg-box-wrap">
	<div class="ajdg-box-three">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Settings', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p><label for="adrotate_widgetalign">
					<strong><?php _e("Widget alignment:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_widgetalign" tabindex="10">
						<option <?php echo ($adrotate_config['widgetalign'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['widgetalign'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Enable this if your widgets do not align with your themes sidebar. (Some themes ignore this!)", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_widgetpadding">
					<strong><?php _e("Widget padding:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_widgetpadding" tabindex="20">
						<option <?php echo ($adrotate_config['widgetpadding'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['widgetpadding'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Enable this to remove the padding (blank space) around ads in widgets. (Some themes prevent this!)", 'adrotate'); ?></small>
				</label></p>

				<p><label for="adrotate_hide_schedules">
					<strong><?php _e("Hide schedules:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_hide_schedules" tabindex="30" disabled>
						<option disabled value="N"><?php _e('No', 'adrotate'); ?></option>
						<option disabled value="N"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("When editing adverts, hide schedules that are not in use by that advert.", 'adrotate'); ?></small>
				</label></p>

				<?php if($adrotate_config['w3caching'] == 'Y' AND !defined('W3TC_DYNAMIC_SECURITY')) { ?>
				<p>
					<strong><?php _e("NOTICE:", 'adrotate'); ?></strong> <?php _e("You have enabled W3 Total Caching support but not defined the security hash.", 'adrotate'); ?>
				</p>
				<p>
					<?php _e("AdRotate has generated the following line for you to add to your wp-config.php around line 52 (below the WordPress nonces). If you do not know how to add this line, check out the following guide;", 'adrotate'); ?> <a href="https://support.ajdg.net/knowledgebase.php?article=46"><?php _e("Set up W3 Total Caching.", 'adrotate'); ?></a>
					<br /><pre>define('W3TC_DYNAMIC_SECURITY', 'adrotate');</pre>
				</p>
				<?php } ?>

				<p><label for="adrotate_w3caching">
					<strong><?php _e("W3 Total Caching compatibility:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_w3caching" tabindex="40">
						<option <?php echo ($adrotate_config['w3caching'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['w3caching'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Check this box if you use W3 Total Caching on your site.", 'adrotate'); ?> - <a href="https://support.ajdg.net/knowledgebase.php?article=46" target="_blank"><?php _e("Setup guide.", 'adrotate'); ?></a></small>
				</label></p>

				<p><label for="adrotate_borlabscache">
					<strong><?php _e("Borlabs Cache compatibility:", 'adrotate'); ?></strong> <span class="alignright"><select name="adrotate_borlabscache" tabindex="50">
						<option <?php echo ($adrotate_config['borlabscache'] == 'N') ? 'selected' : '';  ?> value="N"><?php _e('No', 'adrotate'); ?></option>
						<option <?php echo ($adrotate_config['borlabscache'] == 'Y') ? 'selected' : '';  ?> value="Y"><?php _e('Yes', 'adrotate'); ?></option>
					</select></span>
					<br /><small><?php _e("Check this box if you use Borlabs Caching on your site.", 'adrotate'); ?> - <a href="https://support.ajdg.net/knowledgebase.php?article=46" target="_blank"><?php _e("Setup guide.", 'adrotate'); ?></a></small>
				</label></p>

				<p>
					<em><?php _e("It may take a while for adverts to start rotating. The caching plugin needs to refresh/clear the cache. This can several days if not done manually.", 'adrotate'); ?> <?php _e("Caching support only works for [shortcodes] and the AdRotate Widget and Blocks. If you use a PHP Snippet you need to wrap your PHP in the exclusion code yourself.", 'adrotate'); ?></em>
				</p>

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