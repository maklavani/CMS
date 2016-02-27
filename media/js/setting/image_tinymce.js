jQuery(document).ready(function(){
	jQuery('.image_tinymce').click(function(){
		jQuery('input[name="image_tinymce"]').val('');
		read_image();
	});

	jQuery('input[name="image_tinymce"]').change(function(){
		val = jQuery(this).val().split(',');

		for(i in val){
			src = val[i].split('/');

			if(src[0] == 'uploads')
				src[0] = 'download';

			src = src.join('/');
			tinyMCE.execCommand('mceInsertContent' , false , '<img src="' + src + '">');
		}
	});

	function read_image(image){
		image_item = jQuery('.image_tinymce');
		upload = jQuery.parseJSON(image_item.attr('upload'));
		html = upload.toolbar + upload.view;
		add_popup_page(image_item.attr('image') , jQuery('input[name="image_tinymce"]') , {0: {type: 'html' , value: html}} , {save: image_item.attr('save') , cancel: image_item.attr('cancel')});
		init_upload();
	}
});