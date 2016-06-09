jQuery(document).ready(function(){
	var values = [];
	var twice = [];
	var items = [];
	var number = 0;

	jQuery('.details-parent').each(function(){
		values[number] = {};
		twice[number] = false;
		items[number] = 0;
		jQuery(this).attr("item" , number);

		if(jQuery(this).val() != ""){
			var values_b = jQuery.parseJSON(jQuery(this).val());

			for(i in values_b){
				clone = jQuery(this).next(".details").find('.details-append').children('tr').clone(true);

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

				items[number]++;
				jQuery(this).attr("items" , items);
				jQuery(this).next(".details").find('.details-tbody').append(clone).trigger('change');
			}
		}

		number++;
	});


	jQuery('.details-new').click(function(){
		var item_number = Number(jQuery(this).parent().prev(".details-parent").attr("item"));
		clone = jQuery(this).next(".details-tables").find('.details-append').children('tr').clone(true);

		clone.find('[name]').each(function(){
			jQuery(this).attr('name' , jQuery(this).attr('name') + '_' + items[item_number]);
		});

		items[item_number]++;
		jQuery(this).prev().find('.details-tbody').append(clone).trigger('change');
		twice[item_number] = false;
	});

	jQuery(document).on('click' , '.details-item-delete' , function(){
		var item = jQuery(this).parent().parent();
		item.remove();
		item.parent().trigger('change');
	});

	jQuery('.details-tbody').change(function(){
		// set Default
		var item_number = Number(jQuery(this).parent().parent().prev(".details-parent").attr("item"));
		values[item_number] = {};

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

			values[item_number][jQuery(this).index()] = tr_arr;
		});

		// Set Value
		jQuery(this).parent().parent().parent().find('.details-parent').val(JSON.stringify(values[item_number]));
		jQuery(this).parent().parent().parent().find('.details-parent').attr('value' , JSON.stringify(values[item_number]));

		if(!twice[item_number]){
			twice[item_number] = true;
			jQuery(this).trigger('change');
		}
	});
});