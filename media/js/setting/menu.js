jQuery(document).ready(function(){
	jQuery('.field-type .field-button').click(function(){
		ajax_type(jQuery(this).parent().attr('address'));
	});

	jQuery('.field-link .field-button').click(function(){
		ajax_link(jQuery(this).parent().attr('address'));
	});

	jQuery('input[name="field_input_type"]').change(function(){
		val = jQuery('input[name="field_input_type"]').val();
		jQuery('.field-type .field-text').text(val);

		if(val != ""){
			jQuery('.field-link .field-text').empty();
			jQuery('input[name="field_input_link"]').val('');
			jQuery('.field-link .field-text').parent().addClass('showing');
		}
	});

	jQuery('input[name="field_input_link"]').change(function(){
		val = jQuery('input[name="field_input_link"]').val();
		jQuery('.field-link .field-text').text(val);
	});

	jQuery('.field-type .field-clean').click(function(){
		jQuery('input[name="field_input_type"]').val('');
		jQuery('input[name="field_input_link"]').val('');
		jQuery('.field-type .field-text').text('');
		jQuery('.field-link .field-text').text('');
		jQuery('.field-link .field-text').parent().removeClass('showing');
	});

	jQuery('.field-link .field-clean').click(function(){
		jQuery('input[name="field_input_link"]').val('');
		jQuery('.field-link .field-text').text('');
	});

	function ajax_type(address){
		jQuery.ajax({
			type: "GET",
			url: address ,
			data: { 
				"type" : "1"
			},
			success: function(result){
				result = jQuery.parseJSON(result);
				add_popup_page(result.name , jQuery('input[name="field_input_type"]') , result.value , result.buttons);
			}
		});
	}

	function ajax_link(address){
		jQuery.ajax({
			type: "GET",
			url: address ,
			data: { 
				"link" : jQuery('input[name="field_input_type"]').val()
			},
			success: function(result){
				if(result){
					result = jQuery.parseJSON(result);
					add_popup_page(result.name , jQuery('input[name="field_input_link"]') , result.value , result.buttons);
				}
			}
		});
	}
});