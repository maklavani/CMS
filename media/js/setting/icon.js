jQuery(document).ready(function(){
	jQuery('.field-icon .field-button').click(function(){
		buttons = jQuery.parseJSON(jQuery(this).parent().attr('buttons'));
		icons = [];
		icon_div = '';
		val = [	'icon-star-empty' , 'icon-feed' , 
				'icon-advertisement' , 'icon-aparat' , 'icon-vimeo' , 'icon-youtube' , 'icon-pinterest' , 'icon-twitter' , 'icon-instagram' , 'icon-google-plus' , 
				'icon-facebook' , 'icon-speaker' , 'icon-laptop' , 'icon-desktop' , 'icon-tablet' , 'icon-mobile' , 'icon-product' , 'icon-digarsoo' , 
				'icon-mail' , 'icon-telephone' , 'icon-book' , 'icon-cog' , 'icon-money' , 'icon-move' , 'icon-line' , 'icon-eye' , 
				'icon-cogs' , 'icon-stop' , 'icon-pause' , 'icon-play' , 'icon-upload' , 'icon-back' , 'icon-file-font' , 'icon-refresh' , 
				'icon-resize' , 'icon-crop' , 'icon-file-photoshop' , 'icon-file-illustrator' , 'icon-new-file' , 'icon-file-code' , 'icon-file' , 'icon-file-text' , 
				'icon-file-picture' , 'icon-file-music' , 'icon-file-play' , 'icon-file-video' , 'icon-file-zip' , 'icon-file-pdf' , 'icon-file-openoffice' , 'icon-file-word' , 
				'icon-file-excel' , 'icon-download' , 'icon-folder' , 'icon-folder-open' , 'icon-new-folder' , 'icon-remove-folder' , 'icon-archive' , 'icon-extract' , 
				'icon-paste' , 'icon-cut' , 'icon-copy' , 'icon-rename' , 'icon-grid' , 'icon-heart' , 'icon-star' , 'icon-search' , 
				'icon-eraser' , 'icon-edit' , 'icon-delete' , 'icon-save' , 'icon-extension' , 'icon-content' , 'icon-user' , 'icon-users' , 
				'icon-setting' , 'icon-home' , 'icon-lock' , 'icon-dislike' , 'icon-like' , 'icon-success' , 'icon-error' , 'icon-warning' , 
				'icon-signout' , 'icon-signin' , 'icon-plus' , 'icon-minus' , 'icon-correct' , 'icon-close' , 'icon-list' , 'icon-menu' , 
				'icon-left' , 'icon-down' , 'icon-right' , 'icon-up' , 'icon-arrow-left' , 'icon-arrow-down' , 'icon-arrow-right' , 'icon-arrow-up'
			];

		for(i in val)
			icon_div += '<div class="s2 s16 m12 l1 icon-div vals ' + val[i] + '" vals="' + val[i] + '"></div>';



		item = {type: 'html' , value: icon_div};
		icons = [item];
		add_popup_page(jQuery(this).parent().attr('name') , jQuery('input[name="field_input_icon"]') , icons , buttons);
	});

	jQuery('input[name="field_input_icon"]').change(function(){
		val = jQuery('input[name="field_input_icon"]').val();
		jQuery('.field-icon .field-text').html('<div class="' + val + '"></div>');
	});

	jQuery('.field-icon .field-clean').click(function(){
		jQuery('input[name="field_input_icon"]').val('');
		jQuery('.field-icon .field-text').html('');
	});
});