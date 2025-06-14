<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

function adrotate_widget() {
	register_widget('adrotate_widgets');
}

/*-------------------------------------------------------------
 Name:      adrotate_widget

 Purpose:   Unlimited widgets for the sidebar
 Since:		0.8
-------------------------------------------------------------*/
class adrotate_widgets extends WP_Widget {

	/*-------------------------------------------------------------
	 Purpose:   Construct the widget
	-------------------------------------------------------------*/
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'adrotate_widgets',
			'description' => 'Show a group of adverts or a single advert in any widget area.',
		);
		parent::__construct('adrotate_widgets', 'AdRotate', $widget_ops);
	}

	/*-------------------------------------------------------------
	 Purpose:   Display the widget
	-------------------------------------------------------------*/
	public function widget($args, $instance) {
		global $adrotate_config, $blog_id;

		extract($args);
		if(empty($instance['adid'])) $instance['adid'] = 0;
		if(empty($instance['siteid'])) $instance['siteid'] = $blog_id;
		if(empty($instance['title'])) $instance['title'] = '';
		if(empty($instance['type'])) $instance['type'] = 'single';

        $title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
		if($title) {
			echo $before_title . $title . $after_title;
		}
		
		if($adrotate_config['widgetalign'] == 'Y') echo '<ul><li>';

		if($adrotate_config['w3caching'] == 'Y') {
			echo "<!-- mfunc ".W3TC_DYNAMIC_SECURITY." -->";
			if($instance['type'] == "single") {
				echo "echo adrotate_ad(".$instance['adid'].", true);";
			}
	
			if($instance['type'] == "group") {
				echo "echo adrotate_group(".$instance['adid'].");";
			}
			echo "<!-- /mfunc ".W3TC_DYNAMIC_SECURITY." -->";
		} else if($adrotate_config['borlabscache'] == 'Y' AND function_exists('BorlabsCacheHelper') AND BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();
	
			echo "<!--[borlabs cache start: ".$borlabsphrase."]-->";
			if($instance['type'] == 'single') {
				echo "echo adrotate_ad(".$instance['adid'].", true);";
			}
	
			if($instance['type'] == 'group') {
				echo "echo adrotate_group(".$instance['adid'].");";
			}
			echo "<!--[borlabs cache end: ".$borlabsphrase."]-->";

			unset($borlabsphrase);
		} else {
			if($instance['type'] == 'single') {
				echo adrotate_ad($instance['adid'], true);
			}
	
			if($instance['type'] == 'group') {
				echo adrotate_group($instance['adid']);
			}
		}

		if($adrotate_config['widgetalign'] == 'Y') echo '</li></ul>';
		
		echo $after_widget;
	}

	/*-------------------------------------------------------------
	 Purpose:   Save the widget options per instance
	-------------------------------------------------------------*/
	public function update($new_instance, $old_instance) {
		$new_instance['title'] = sanitize_title($new_instance['title']);
		$new_instance['type'] = sanitize_text_field($new_instance['type']);
		$new_instance['siteid'] = sanitize_key($new_instance['siteid']);
		
		//Try and preserve pre-fix widget IDs
		if(isset($new_instance['id']) AND $new_instance['adid'] < 1) {
			$new_instance['adid'] = $new_instance['id'];
		} else {
			$new_instance['adid'] = sanitize_key($new_instance['adid']);
		}

		$instance = wp_parse_args($new_instance, $old_instance);

		return $instance;
	}

	/*-------------------------------------------------------------
	 Purpose:   Display the widget options for admins
	-------------------------------------------------------------*/
	public function form($instance) {
		global $blog_id;

		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $description = $adid = '';
		$type = 'single';
		extract($instance);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title (optional):', 'adrotate' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			<br />
			<small><?php _e( 'HTML will be stripped out.', 'adrotate' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Type:', 'adrotate' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" class="postform">
			    <option value="single" <?php if($type == "single") { echo 'selected'; } ?>><?php _e( 'Advert - Use Advert ID', 'adrotate' ); ?></option>
		        <option value="group" <?php if($type == "group") { echo 'selected'; } ?>><?php _e( 'Group - Use group ID', 'adrotate' ); ?></option>
			</select>
			<br />
			<small><?php _e( 'Choose what you want to use this widget for', 'adrotate' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('adid'); ?>"><?php _e( 'ID:', 'adrotate' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('adid'); ?>" name="<?php echo $this->get_field_name('adid'); ?>" type="text" value="<?php echo $adid; ?>" />
			<br />
			<small><?php _e( 'Fill in the ID of the type you want to display!', 'adrotate' ); ?></small>
		</p>
		<input id="<?php echo $this->get_field_id('siteid'); ?>" name="<?php echo $this->get_field_name('siteid'); ?>" type="hidden" value="<?php echo $blog_id; ?>" />
<?php
	}
}
?>