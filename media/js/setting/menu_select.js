jQuery(document).ready(function(){
	var sel = [];

	if(jQuery('[name="field_input_menus"]').val() != ""){
		sel = jQuery.parseJSON(jQuery('[name="field_input_menus"]').val());
		for(i in sel)
			jQuery('.select-item[item="' + sel[i] + '"]').children('.select_checkbox').prop('checked' , true);
	}

	jQuery('.select_checkbox').click(function(){
		jQuery(this).parent().children('ul').find('.select_checkbox').each(function(){
			if(jQuery(this).is(':checked'))
				jQuery(this).prop('checked' , false);
			else
				jQuery(this).prop('checked' , true);
		});

		sel = [];
		set_selected();
	});

	function set_selected(){
		jQuery('.select_checkbox:checked').each(function(){
			if(typeof jQuery(this).parent().attr('item') != 'undefined')
				sel.push(jQuery(this).parent().attr('item'));
		});

		jQuery('[name="field_input_menus"]').val(JSON.stringify(sel));
	}
});