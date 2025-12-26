<div class="ajdg-box">
	<h2 class="ajdg-box-title"><?php _e("Become an advertising professional", 'adrotate'); ?></h2>
	<div class="ajdg-box-content ajdg-box-sale">

		<a href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><img src="<?php echo plugins_url("/images/offers/monetize-your-site.jpg", dirname(__FILE__)); ?>" alt="AdRotate Professional" width="100%"></a>
		<div class="title"><?php _e("AdRotate Professional", 'adrotate'); ?></div>
		<div class="sub_title"><?php _e("Starting at only €49.00", 'adrotate'); ?><br /><a href="https://ajdg.solutions/product-category/adrotate-pro/" target="_blank"><?php _e("Compare features & licenses", 'adrotate'); ?></a></div>
		<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-single/" target="_blank"><?php _e("Get a Single site License", 'adrotate'); ?></a></div>
		<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/adrotate-pro-multi/" target="_blank"><?php _e("Go big with the Multi License", 'adrotate'); ?></a></div>
		<hr>
		<div class="description">
			<p><?php _e("Unlock all the greyed out features with AdRotate Professional. Besides of the settings you can actually see, AdRotate Pro has much more to offer under the hood for even better advertising management. Better error detection, export your stats, but also features like Geo Targeting, and advanced Schedules. Expanded Post Injection and much more with AdRotate Pro!", 'adrotate'); ?></p>
			<p><?php _e("Oh, and you'll also get access to Ticket Support, AdRotate Geo and one year of updates.", 'adrotate'); ?></p>
		</div>

	</div>
</div>

<?php if(!is_plugin_active('gooseup/gooseup.php')) { ?>
<div class="ajdg-box">
	<h2 class="ajdg-box-title"><?php _e("GooseUp does updates", 'adrotate'); ?></h2>
	<div class="ajdg-box-content ajdg-box-sale">

		<a href="https://ajdg.solutions/product/gooseup/" target="_blank"><img src="<?php echo plugins_url("/images/offers/gooseup.jpg", dirname(__FILE__)); ?>" alt="GooseUp" width="100%"></a>
		<div class="title"><?php _e("GooseUp", 'adrotate'); ?></div>
		<div class="sub_title"><?php _e("Get your updates straight from the source", 'adrotate'); ?></div>
		<div class="cta"><a role="button" class="cta_button" href="https://ajdg.solutions/product/gooseup/" target="_blank"><?php _e("More info & download", 'adrotate'); ?></a></div>

	</div>
</div>
<?php } ?>

<div class="ajdg-box">
	<h2 class="ajdg-box-title"><?php _e("Blogs & updates", 'adrotate'); ?></h2>
	<div class="ajdg-box-content">

		<h3>AJdG Updates</h3>
		<?php echo adrotate_fetch_rss_feed('https://ajdg.solutions/feed/', 3); ?>
		<p>Stay up-to-date with plugins: <a href="https://ajdg.solutions/feed/" target="_blank" title="Subscribe to the AJdG Solutions blog!" class="button-primary"><i class="icn-rss"></i> RSS</a></p>

		<h3>Arnan's blog</h3>
		<?php echo adrotate_fetch_rss_feed('https://www.arnan.me/feed/page:feed.xml', 3); ?>
		<p>Subscribe to the blog of Arnan: <a href="https://www.arnan.me/feed/page:feed.xml" target="_blank" title="Subscribe to Arnan's blog!" class="button-primary"><i class="icn-rss"></i> RSS</a></p>

	</div>
</div>
