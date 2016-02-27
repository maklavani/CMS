jQuery(document).ready(function(){
	var values = {};
	var items = 0;
	var twice = false;

	if(jQuery('.details-parent').val() != ""){
		values_b = jQuery.parseJSON(jQuery('.details-parent').val());

		for(i in values_b){
			clone = jQuery('.details-append').children('tr').clone(true);

			clone.find('[name]').each(function(){
				jQuery(this).attr('name' , jQuery(this).attr('name') + '_' + items);

				// Detected Price
				if(jQuery(this).hasClass('price'))
					jQuery(this).parent().children('.price-field').attr('price' , jQuery(this).attr('name'));


				if(typeof values_b[i][jQuery(this).attr('name')] != 'undefined')
				{
					jQuery(this).val(values_b[i][jQuery(this).attr('name')]);
					jQuery(this).attr('value' , values_b[i][jQuery(this).attr('name')]);
				}
			});

			items++;
			jQuery('.details-tbody').append(clone).trigger('change');
		}
	}

	jQuery('.details-new').click(function(){
		clone = jQuery('.details-append').children('tr').clone(true);

		clone.find('[name]').each(function(){
			jQuery(this).attr('name' , jQuery(this).attr('name') + '_' + items);
		});

		items++;
		jQuery('.details-tbody').append(clone).trigger('change');
		twice = false;
	});

	jQuery(document).on('click' , '.details-item-delete' , function(){
		jQuery(this).parent().parent().remove();
		jQuery('.details-tbody').trigger('change');
	});

	jQuery('.details-tbody').change(function(){
		// set Default
		values = {};

		// Detected Select2
		jQuery(this).find('.select2-container').remove();
		jQuery(this).find('.select2-hidden-accessible').removeClass('select2-hidden-accessible');
		jQuery(this).find('select').each(function(){
			jQuery(this).removeAttr('class');
			jQuery(this).attr('id' , jQuery(this).attr('name'));
			jQuery('#' + jQuery(this).attr('name')).select2();
		});

		// Detected Price
		jQuery(this).find('.price').each(function(){
			jQuery(this).parent().children('.price-field').attr('price' , jQuery(this).attr('name'));
		});

		jQuery(this).children('tr').each(function(){
			var tr_arr = {};

			jQuery(this).find('[name]').each(function(){
				tr_arr[jQuery(this).attr('name')] = jQuery(this).val();
			});

			values[jQuery(this).index()] = tr_arr;
		});

		// Set Value
		jQuery('.details-parent').val(JSON.stringify(values));
		jQuery('.details-parent').attr('value' , JSON.stringify(values));

		if(!twice){
			twice = true;
			jQuery('.details-tbody').trigger('change');
		}
	});
});