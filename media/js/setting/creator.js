var value_creator_files = false;
var item = 0;
var creator_items = {};
var edit = false;
var edit_number = false;

jQuery(document).ready(function(){
	if(jQuery('.creator-input').val() != ""){
		vals = jQuery.parseJSON(jQuery('.creator-input').val());

		for(i in vals){
			creator_items[item] = {name: vals[i].name , type: vals[i].type , items: vals[i].items}
			item++;
		}
	}

	jQuery('.creator-button').click(function(){
		edit = false;
		edit_number = false;
		jQuery('.creator-item').removeClass('edit');
		ajax_filed();
	});

	jQuery(document).on('change' , '.fields-items' , function(){
		ajax_filed_item(jQuery(this).val());
	});

	jQuery(document).on('keyup' , '.fields-name' , function(){
		update_page();
	});

	jQuery(document).on('keyup' , '.field-item' , function(){
		update_page();
	});

	jQuery(document).on('change' , '.field-item' , function(){
		update_page();
	});

	jQuery(document).on('click', '.active-page .popup_button[type="save"]' , function(){
		val = jQuery.parseJSON(value_popup_files.value);

		if(edit){
			creator_items[parseInt(jQuery('.creator-item.edit').attr('item'))] = {name: val.name , type: val.type , items: val.items}
		} else {
			creator_items[item] = {name: val.name , type: val.type , items: val.items}
			item++;
		}

		update_html();
	});

	jQuery(document).on('click' , '.creator-item .icon-edit' , function(){
		edit = true;
		edit_number = parseInt(jQuery(this).parent().attr('item'));

		jQuery(this).parent().addClass('edit');
		ajax_filed(jQuery(this).parent().children('.type').text());
	});

	jQuery(document).on('click' , '.creator-item .icon-delete' , function(){
		number = parseInt(jQuery(this).parent().attr('item'));
		number_inc = 0;
		creator_items_b = {};

		for(i in creator_items)
			if(i != number){
				creator_items_b[number_inc] = creator_items[i];
				number_inc++;
			}

		creator_items = creator_items_b;
		update_html();
	});

	jQuery(document).on('click' , '.creator-item .icon-move' , function(){
		if(jQuery(this).parent().hasClass('move'))
			jQuery(this).parent().removeClass('move');
		else {
			jQuery('.creator-item.move').removeClass('move');
			jQuery(this).parent().addClass('move');
		}
	});

	jQuery(document).on('keyup' , function(event){
		if(jQuery('.creator-item.move').hasClass('move')){
			number = parseInt(jQuery('.creator-item.move').attr('item'));
			temp = creator_items[number];
			count = 0;

			for (i in creator_items)
				if (creator_items.hasOwnProperty(i))
					count++;

			if(event.keyCode == 38 && number > 0){
				creator_items[number] = creator_items[number - 1];
				creator_items[number - 1] = temp;
				update_html();
				jQuery('.creator-item[item="' + (number - 1) + '"]').addClass('move');
			} else if(event.keyCode == 40 && (number + 1) < count){
				creator_items[number] = creator_items[number + 1];
				creator_items[number + 1] = temp;
				update_html();
				jQuery('.creator-item[item="' + (number + 1) + '"]').addClass('move');
			}
		}
	});

	function ajax_filed(field_item){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.creator').attr('ajax') ,
			success: function(result){
				popup_page = [];
				popup_values = [];
				number_page = -1;
				jQuery('#popup').empty();

				value_creator_files = false;
				value_popup_files = jQuery(this);

				value_popup_files.value = false;
				add_popup_page(jQuery('.creator').attr('select') , value_popup_files , {0: {type: 'html' , value: result}} , {save: jQuery('.creator').attr('save')});
				jQuery('.active-page .popup_button[type="save"]').addClass('showing');

				if(typeof field_item != 'undefined')
					jQuery('.fields-items').val(field_item);

				if(edit)
					jQuery('.fields-name').val(creator_items[edit_number].name);

				jQuery('.fields-items').select2();
				ajax_filed_item(jQuery('.fields-items').val());
			}
		});
	}

	function ajax_filed_item(field){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.creator').attr('ajax') ,
			data: {
				field: field
			},
			success: function(result){
				jQuery('.field-properties').empty();
				jQuery('.field-properties').html(result);
				jQuery('.field-properties select').select2();

				if(edit){
					items = creator_items[edit_number].items;
					for(i in items)
						jQuery('.field-item[name="' + i + '"]').text(items[i]);
				}

				update_page();
				resize_popup();
			}
		});
	}

	function update_page(){
		val_item = {name: jQuery('.fields-name').val() , type: jQuery('.fields-items').val() , items: {}};

		jQuery('.field-item').each(function(){
			val_item.items[jQuery(this).attr('name')] = jQuery(this).val();
		});

		jQuery('.active-page .html').attr('vals' , JSON.stringify(val_item));
		check_value();
	}

	function update_html(){
		jQuery('.creator-items').empty();

		for(i in creator_items){
			jQuery('.creator-items').append("<div class=\"creator-item xa\" item=\"" + i + "\"></div>");
			jQuery('.creator-items').children().last().append("<div class=\"icon-move x1\"></div>");
			jQuery('.creator-items').children().last().append("<div class=\"name x4\">" + creator_items[i].name + "</div>");
			jQuery('.creator-items').children().last().append("<div class=\"type x2\">" + creator_items[i].type + "</div>");
			jQuery('.creator-items').children().last().append("<div class=\"icon-edit x15\"></div>");
			jQuery('.creator-items').children().last().append("<div class=\"icon-delete x15\"></div>");
		}

		jQuery('.creator-input').val(JSON.stringify(creator_items));
	}
});