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

<div class="ajdg-box-wrap">
	<div class="ajdg-box-two">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Getting help with AdRotate Banner Manager', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<p>
					<img src="<?php echo plugins_url('/images/icon-support.png', dirname(__FILE__)); ?>" class="alignleft pro-image" /><?php _e("New to AdRotate Banner Manager? Is something not working the way you expect it to? When you need help with AdRotate or AdRotate Pro you can check the manuals and guides on my website. Also there is a lot of information on the support forum asked by other users. Chances are your question has already been asked and answered!", 'adrotate'); ?>
				</p>

				<p>
					<a href="https://support.ajdg.net/knowledgebase.php" target="_blank"><strong><?php _e("AdRotate Knowledge Base", 'adrotate'); ?></strong></a><br />
					<em><?php _e("Take a look at the AdRotate Manuals and Guides.", 'adrotate'); ?> <a href="https://support.ajdg.net/knowledgebase.php" target="_blank"><?php _e("Find answers", 'adrotate'); ?> &raquo;</a></em>
				</p>
				
				<p>
					<a href="https://ajdg.solutions/product/support-ticket/" target="_blank"><strong><?php _e("Buy a Support Ticket", 'adrotate'); ?></strong></a><br />
					<em><?php _e("Buy a priority support ticket for private and quick assistance.", 'adrotate'); ?> <a href="https://ajdg.solutions/product/support-ticket/" target="_blank"><?php _e("More information", 'adrotate'); ?> &raquo;</a></em>
				</p>
				
				<p>
					<a href="https://wordpress.org/support/plugin/adrotate/" target="_blank"><strong><?php esc_html_e("WordPress support forum", 'adrotate'); ?></strong></a><br />
					<em><?php esc_html_e("Browse questions on the WordPress website or ask your own.", 'adrotate'); ?> <a href="https://wordpress.org/support/plugin/adrotate/" target="_blank"><?php esc_html_e("Visit forum", 'adrotate'); ?> &raquo;</a></em>
				</p>
				
				<p>
					<a href="https://support.ajdg.net/index.php?a=add&category=4" target="_blank"><strong><?php _e("Report security issues", 'adrotate'); ?></strong></a><br />
					<em><?php _e("Found a security flaw?", 'adrotate'); ?> <a href="https://support.ajdg.net/index.php?a=add&category=4" target="_blank"><?php _e("Please report it", 'adrotate'); ?> &raquo;</a></em>
				</p>

				<h2><?php _e("Plugins and services", 'adrotate'); ?></h2>
				<table width="100%">
					<tr>
						<td width="50%">
							<div class="ajdg-box-content ajdg-box-sale">
								<a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/monetize-your-site.jpg", dirname(__FILE__)); ?>" alt="AdRotate Professional" width="228" height="120"></div></a>
								<div class="title"><?php _e("AdRotate Professional", 'adrotate'); ?></div>
								<div class="sub_title"><?php _e("More features!", 'adrotate'); ?></div>
								<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><?php _e("Buy now", 'adrotate'); ?></a></div>
								<hr>
								<div class="description"><?php _e("Turn up your advertising management capabilities up to 11 with AdRotate Pro! Benefit from extra features and increase your revenue.", 'adrotate'); ?></div>
							</div>
						</td>
						<td>
							<div class="ajdg-box-content ajdg-box-sale">
								<a href="https://ajdg.solutions/plugins/" target="_blank"><div class="header"><img src="<?php echo plugins_url("/images/offers/more-plugins.jpg", dirname(__FILE__)); ?>" alt="AJdG Solutions Plugins" width="228" height="120"></div></a>
								<div class="title"><?php _e("All my plugins", 'adrotate'); ?></div>
								<div class="sub_title"><?php _e("For WordPress and WooCommerce", 'adrotate'); ?></div>
								<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/plugins/" target="_blank"><?php _e("View all plugins", 'adrotate'); ?></a></div>
								<hr>
								<div class="description"><?php _e("Handy plugins that improve your WooCommerce online store; faster checkout, no checkout, payment stats and more!", 'adrotate'); ?></div>
							</div>
						</td>
					</tr>
				</table>

			</div>
		</div>

	</div>
	<div class="ajdg-box-two">

		<div class="ajdg-box">
			<h2 class="ajdg-box-title"><?php _e('Priority Support is the default in AdRotate Professional', 'adrotate'); ?></h2>
			<div class="ajdg-box-content">

				<form name="request" id="post" method="post" action="admin.php?page=adrotate">
					<p>
						<img src="<?php echo plugins_url('/images/icon-contact.png', dirname(__FILE__)); ?>" class="alignleft pro-image" />&raquo; <?php _e("AdRotate Pro support always gets priority!", 'adrotate'); ?><br />
						&raquo; <?php _e("Unlimited tickets, straight from your dashboard.", 'adrotate'); ?><br />
						&raquo; <?php _e("Using this form includes all the technical information for a quick solution.", 'adrotate'); ?><br />
						&raquo; <?php _e("Available in AdRotate Professional!", 'adrotate'); ?>
					</p>

					<p><label for="ajdg_support_username">
						<strong><?php _e("Your name:", 'adrotate'); ?></strong><br />
						<input tabindex="1" name="ajdg_support_username" type="text" class="search-input" style="width:100%;" value="<?php echo $current_user->display_name; ?>" autocomplete="off" disabled />
					</label></p>

					<p><label for="ajdg_support_email">
						<strong><?php _e("Your Email Address:", 'adrotate'); ?></strong><br />
						<input tabindex="1" name="ajdg_support_email" type="text" class="search-input" style="width:100%;" value="<?php echo $current_user->user_email; ?>" autocomplete="off" disabled />
					</label></p>

					<p><label for="ajdg_support_subject">
						<strong><?php _e("Subject:", 'adrotate'); ?></strong><br />
						<input tabindex="2" name="ajdg_support_subject" type="text" class="search-input" style="width:100%;" value="" autocomplete="off" disabled />
					</label></p>

					<p><label for="ajdg_support_message">
						<strong><?php _e("Problem description / Question:", 'adrotate'); ?></strong><br />
						<textarea tabindex="3" name="ajdg_support_message" style="width:100%; height:100px;" disabled></textarea>
					</label></p>

					<p><label for="ajdg_support_advert">
						<strong><?php _e("Advert I need help with:", 'adrotate'); ?></strong> <select tabindex="4" name="ajdg_support_advert" disabled>
					        <option value="0" disabled>-- <?php _e("Optionally select an advert", 'adrotate'); ?> --</option>
						</select> <span class="ajdg-tooltip">[?]<span class="ajdg-tooltiptext ajdg-tooltip-top"><?php _e("Selecting an advert will include the ad settings for Arnan to test on his website. Does not include group or schedule data.", 'adrotate'); ?></span></span>
					</label></p>
					
					<p><label for="ajdg_support_account">
						<input tabindex="5" name="ajdg_support_account" type="checkbox" disabled /> <?php _e("Please log in to my website and take a look.", 'adrotate'); ?> <span class="ajdg-tooltip">[?]<span class="ajdg-tooltiptext ajdg-tooltip-top"><?php _e("Checking this option will create a (temporary) account for Arnan to log in and take a look at your setup.", 'adrotate'); ?></span></span>
					</label></p>

					<p>
						<strong><?php _e("When you create a ticket, the following data will be submitted:", 'adrotate'); ?></strong><br/>
						<em><?php _e("Your name, Account email address, Your website url and some basic WordPress information will be included with the message.", 'adrotate'); ?></em>
					</p>

					<p class="submit">
						<input tabindex="6" type="submit" name="adrotate_support_submit" class="button-primary" value="<?php _e("Get Help", 'adrotate'); ?>" disabled />&nbsp;&nbsp;&nbsp;<em><?php _e("Premium Support is available in AdRotate Pro!", 'adrotate'); ?></em>
					</p>
				</form>

			</div>
		</div>

	</div>
</div>

</form>