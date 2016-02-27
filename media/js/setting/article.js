jQuery(document).ready(function(){
	jQuery('.field-article .field-button').click(function(){
		ajax_article(jQuery(this).parent().attr('address') , jQuery(this).parent().prev());
	});

	jQuery('.article_input').change(function(){
		jQuery(this).next().children('.field-text').text(jQuery(this).val());
	});

	jQuery('.field-article .field-clean').click(function(){
		jQuery(this).parent().prev().val('');
		jQuery(this).parent().children('.field-text').text('');
	});

	function ajax_article(address , elm){
		jQuery.ajax({
			type: "GET",
			url: address ,
			data: { 
				"link" : 'content_article'
			},
			success: function(result){
				if(result){
					result = jQuery.parseJSON(result);
					add_popup_page(result.name , elm , result.value , result.buttons);
				}
			}
		});
	}
});