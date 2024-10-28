/*
use the Media Uploader from WordPress
Version: 1.1
Copyright: (c) 2020-2024 Arnan de Gans
*/
jQuery(document).ready(function(){
	var custom_uploader;
	jQuery('#adrotate_image_button').click(function(e) {
		e.preventDefault();
		if(custom_uploader) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Banner',
			button: {text: 'Choose Banner'},
			multiple: false,
			library: { type: 'image/jpg, image/jpeg, image/gif, image/png, image/svg, image/webp, text/html, text/htm' }
		});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
    		// Check if selected mediafile is of wrong file type
			allowedFiles = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/svg', 'image/webp', 'text/html', 'text/htm'];
    		if(!allowedFiles.includes(attachment.mime)) {
        		// Create error element
        		const err_el = document.createElement('span');
        		err_el.classList.add('adrotate_inline_error');
        		err_el.innerHTML = 'File (' + attachment.mime + ') is not supported for ads.';
        		// Check if error message does not exist yet and add it if it doesn't
        		if(!document.querySelector('.adrotate-wrong-filetype')) {
					jQuery('#adrotate_image_button').after(err_el);
					jQuery('#adrotate_image').val('');
				}
        		return;
    		}
			jQuery('.adrotate-wrong-filetype').remove();
			jQuery('#adrotate_image').val(attachment.url);
		});
		custom_uploader.open();
	});
});
