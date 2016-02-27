jQuery(document).ready(function(){
	cookie = jQuery.cookie('fields_page');

	if (typeof cookie != 'undefined' && jQuery('.fileds-page[page="' + cookie + '"]').length){
		jQuery('.fileds-button[page="' + cookie + '"]').addClass('active');
		jQuery('.fileds-page[page="' + cookie + '"]').addClass('active');
	} else {
		jQuery('.fileds-button:first-child').addClass('active');
		jQuery('.fileds-page:first-child').addClass('active');
	}

	jQuery('.fileds-button').click(function(){
		if(!jQuery(this).hasClass('active')){
			jQuery.cookie('fields_page' , jQuery(this).attr('page') , {path: '/' , expires: 7});

			jQuery('.fileds-button.active').removeClass('active');
			jQuery('.fileds-page.active').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.fileds-page[page="' + jQuery(this).attr('page') + '"]').addClass('active');
		}
	});

	jQuery('.fields-group[type="radio"]').each(function(){
		fi = jQuery(this).children('.fields-input');
		fi.append('<div class="fields-input-radio xa"></div>');
		fir = fi.children('.fields-input-radio');

		fi.children('input').each(function(){
			cls = "";

			if(jQuery(this).is(':checked'))
				cls = "checked";
	
			fir.append('<div class="radio-item ' + cls + '" val="' + jQuery(this).attr('value') + '">' + jQuery(this).attr('label') + '</div>');
		});
	});

	jQuery(document).on('click' , '.radio-item' , function(){
		parent = jQuery(this).parent().parent();

		jQuery(this).parent().children('.radio-item.checked').removeClass('checked');
		jQuery(this).addClass('checked');
		parent.children('input').prop('checked' , false);
		parent.children('input[value="' + jQuery(this).attr('val') + '"]').prop('checked' , true);
	});
});