jQuery(document).ready(function(){
	jQuery('.field-type .field-button').click(function(){
		ajax_type(jQuery(this).parent());
	});

	jQuery('.field-link .field-button').click(function(){
		ajax_link(jQuery(this).parent());
	});

	jQuery('.field-input-type').change(function(){
		var elm = jQuery(this);
		var parent = jQuery('.field-type[elm="' + elm.attr("name") + '"]');
		var parent_link = parent.next(".field-link");
		val = elm.val();
		parent.find('.field-text').text(val);

		if(val != ""){
			parent_link.find('.field-text').empty();
			jQuery('input[name="' + parent_link.attr("elm") + '"]').val('');
			parent_link.addClass('showing');
		}
	});

	jQuery('.field-input-link').change(function(){
		var elm = jQuery(this);
		var parent = jQuery('.field-link[elm="' + elm.attr("name") + '"]');
		parent.find('.field-text').text(elm.val());
	});

	jQuery('.field-type .field-clean').click(function(){
		var elm = jQuery(this);
		var parent = elm.parent();

		jQuery('input[name="' + parent.attr("elm") + '"]').val('');
		jQuery('input[name="' + parent.next('.field-link').attr("elm") + '"]').val('');
		parent.find('.field-text').text('');
		parent.next('.field-link').find('.field-text').text('');
		parent.next('.field-link').removeClass('showing');
	});

	jQuery('.field-link .field-clean').click(function(){
		var elm = jQuery(this);
		var parent = elm.parent();
		jQuery('input[name="' + parent.attr("elm") + '"]').val('');
		parent.find('.field-text').text('');
	});

	function ajax_type(parent){
		jQuery.ajax({
			type: "GET",
			url: parent.attr('address') ,
			data: { 
				"type" : "1"
			},
			success: function(result){
				result = jQuery.parseJSON(result);
				add_popup_page(result.name , jQuery('input[name="' + parent.attr("elm") + '"]') , result.value , result.buttons);
			}
		});
	}

	function ajax_link(parent){
		jQuery.ajax({
			type: "GET",
			url: parent.attr('address') ,
			data: { 
				"link" : jQuery('input[name="' + parent.prev(".field-parent").attr("elm") + '"]').val()
			},
			success: function(result){
				if(result){
					result = jQuery.parseJSON(result);
					add_popup_page(result.name , jQuery('input[name="' + parent.attr("elm") + '"]') , result.value , result.buttons);
				}
			}
		});
	}
});