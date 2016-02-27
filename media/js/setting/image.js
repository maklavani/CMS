jQuery(document).ready(function(){
	jQuery('.field-image .field-button').click(function(){
		read_image(jQuery(this).parent().attr('image_name'));
	});

	jQuery('input[image="image"]').change(function(){
		val = jQuery(this).val();
		jQuery('.field-image[image_name="' + jQuery(this).attr('image_name') + '"] .field-text').text(val);
	});

	jQuery('.field-image .field-clean').click(function(){
		jQuery('input[image_name="' + jQuery(this).parent().attr('image_name') + '"]').val('');
		jQuery(this).parent().children('.field-text').text('');
	});

	function read_image(image){
		image_item = jQuery('.field-image[image_name="' + image + '"]');
		upload = jQuery.parseJSON(image_item.attr('upload'));
		html = upload.toolbar + upload.view;
		add_popup_page(image_item.attr('image') , jQuery('input[image_name="' + image + '"]') , {0: {type: 'html' , value: html}} , {save: image_item.attr('save') , cancel: image_item.attr('cancel')});
		init_upload();
	}
});