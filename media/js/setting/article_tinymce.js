jQuery(document).ready(function(){
	jQuery('.article_tinymce').click(function(){
		jQuery('input[name="article_tinymce"]').val('');
		read_article();
	});

	jQuery('input[name="article_tinymce"]').change(function(){
		val = jQuery(this).val();
		tinyMCE.execCommand('mceInsertContent' , false , '<a href="' + val + '">' + jQuery('input[name="article_name_tinymce"]').val() + '</a>');
	});

	function read_article(){
		article_item = jQuery('.article_tinymce');

		add_popup_page(article_item.attr('title') , jQuery('input[name="article_tinymce"]') , {0: {type: 'ajax' , value: article_item.attr('ajax')}} , {save: article_item.attr('save') , cancel: article_item.attr('cancel')});
	}

	jQuery(document).on('click' , '.selective' , function(){
		jQuery('input[name="article_name_tinymce"]').val(jQuery(this).text());
	});
});