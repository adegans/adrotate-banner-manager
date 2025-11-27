<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_portable_hash
 Purpose:   Export/import adverts via a portable hash
-------------------------------------------------------------*/
function adrotate_portable_hash($action, $data, $what = 'advert') {
	$source = get_option('siteurl');

	if(in_array("aes-128-cbc", openssl_get_cipher_methods())) {

		$allowed_columns = array('title', 'bannercode', 'thetime', 'author', 'imagetype', 'image', 'tracker', 'show_everyone', 'desktop', 'mobile', 'tablet', 'os_ios', 'os_android', 'weight', 'autodelete', 'autodisable', 'budget', 'crate', 'irate', 'state_req', 'cities', 'states', 'countries');

		$advert = array();

		if($action == 'export') {
			$portable_hash = array('meta' => array('type' => $what, 'source' => $source, 'exported' => current_time('timestamp')), 'data' => array());

			// Basic formatting and checks
			foreach($data as $column => $value) {
				if(in_array($column, $allowed_columns)) {
					if(empty($value) AND ($column == 'cities' OR $column == 'states' OR $column == 'countries')) $value = "a:0:{}";

					if(!empty($value)) {
						$advert[$column] = trim($value);
					}
				}
			}

			$portable_hash['data'] = $advert;
			$portable_hash = serialize($portable_hash);

			return openssl_encrypt($portable_hash, 'aes-128-cbc', '983jdnn3idjk02id', false, 'oi1u23kj123hj7jd');
	    }

		if($action == 'import') {
			$data = openssl_decrypt($data, 'aes-128-cbc', '983jdnn3idjk02id', false, 'oi1u23kj123hj7jd');
			$data = unserialize($data);

			if(is_array($data)) {
				if(array_key_exists('meta', $data) AND array_key_exists('data', $data)) {
					if($data['meta']['type'] == 'advert' AND $source != $data['meta']['source']) {
						// Basic formatting and checks
						foreach($data['data'] as $column => $value) {
							if(in_array($column, $allowed_columns)) {
								if(empty($value) AND ($column == 'cities' OR $column == 'states' OR $column == 'countries')) $value = "a:0:{}";

								if(!empty($value)) {
									$advert[$column] = trim($value);
								}
							}
						}

						// Add a modified time 
						$advert['updated'] = current_time("timestamp");
						
						return $advert;
					} else if($data['meta']['type'] == 'group') {
						// maybe
					} else if($data['meta']['type'] == 'schedule') {
						// maybe					
					} else {
						adrotate_return('adrotate', 514);
					}
				}
			}
			adrotate_return('adrotate', 513);
	    }
	
	}
}
?>