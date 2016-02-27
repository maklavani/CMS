var value_forms_creator_files = false;
var item = 0;
var forms_creator_items = {};
var edit = false;
var edit_number = false;

jQuery(document).ready(function(){
	if(typeof jQuery('.forms-creator-input').val() != "undefined" && jQuery('.forms-creator-input').val() != "" && jQuery('.forms-creator-input').val() != "null"){
		vals = jQuery.parseJSON(jQuery('.forms-creator-input').val());

		for(i in vals){
			forms_creator_items[item] = {name: vals[i].name , type: vals[i].type , check: vals[i].check , items: vals[i].items}
			item++;
		}

		jQuery('.forms-creator-items .checks-input').select2();
	}

	jQuery('.forms-creator-button').click(function(){
		edit = false;
		edit_number = false;
		jQuery('.forms-creator-item').removeClass('edit');
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
			forms_creator_items[parseInt(jQuery('.forms-creator-item.edit').attr('item'))] = {name: val.name , type: val.type , check: forms_creator_items[parseInt(jQuery('.forms-creator-item.edit').attr('item'))].check , items: val.items}
		} else {
			forms_creator_items[item] = {name: val.name , type: val.type , check: "text" , items: val.items}
			item++;
		}

		update_html();
	});

	jQuery(document).on('click' , '.forms-creator-item .icon-edit' , function(){
		edit = true;
		edit_number = parseInt(jQuery(this).parent().attr('item'));

		jQuery(this).parent().addClass('edit');
		ajax_filed(jQuery(this).parent().children('.type').text());
	});

	jQuery(document).on('click' , '.forms-creator-item .icon-delete' , function(){
		number = parseInt(jQuery(this).parent().attr('item'));
		number_inc = 0;
		forms_creator_items_b = {};

		for(i in forms_creator_items)
			if(i != number){
				forms_creator_items_b[number_inc] = forms_creator_items[i];
				number_inc++;
			}

		forms_creator_items = forms_creator_items_b;
		update_html();
	});

	jQuery(document).on('click' , '.forms-creator-item .icon-move' , function(){
		if(jQuery(this).parent().hasClass('move'))
			jQuery(this).parent().removeClass('move');
		else {
			jQuery('.forms-creator-item.move').removeClass('move');
			jQuery(this).parent().addClass('move');
		}
	});

	jQuery(document).on('keyup' , function(event){
		if(jQuery('.forms-creator-item.move').hasClass('move')){
			number = parseInt(jQuery('.forms-creator-item.move').attr('item'));
			temp = forms_creator_items[number];
			count = 0;

			for (i in forms_creator_items)
				if (forms_creator_items.hasOwnProperty(i))
					count++;

			if(event.keyCode == 38 && number > 0){
				forms_creator_items[number] = forms_creator_items[number - 1];
				forms_creator_items[number - 1] = temp;
				update_html();
				jQuery('.forms-creator-item[item="' + (number - 1) + '"]').addClass('move');
			} else if(event.keyCode == 40 && (number + 1) < count){
				forms_creator_items[number] = forms_creator_items[number + 1];
				forms_creator_items[number + 1] = temp;
				update_html();
				jQuery('.forms-creator-item[item="' + (number + 1) + '"]').addClass('move');
			}
		}
	});

	jQuery(document).on('change' , '.forms-creator-items .checks-input' , function(event){
		forms_creator_items[parseInt(jQuery(this).parent().parent().attr('item'))].check = jQuery(this).val();
		jQuery('.forms-creator-input').val(JSON.stringify(forms_creator_items));
	});

	function ajax_filed(field_item){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.forms-creator').attr('ajax') ,
			success: function(result){
				popup_page = [];
				popup_values = [];
				number_page = -1;
				jQuery('#popup').empty();

				value_forms_creator_files = false;
				value_popup_files = jQuery(this);

				value_popup_files.value = false;
				add_popup_page(jQuery('.forms-creator').attr('select') , value_popup_files , {0: {type: 'html' , value: result}} , {save: jQuery('.forms-creator').attr('save')});
				jQuery('.active-page .popup_button[type="save"]').addClass('showing');

				if(typeof field_item != 'undefined')
					jQuery('.fields-items').val(field_item);

				if(edit)
					jQuery('.fields-name').val(forms_creator_items[edit_number].name);

				jQuery('.fields-items').select2();
				ajax_filed_item(jQuery('.fields-items').val());
			}
		});
	}

	function ajax_filed_item(field){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.forms-creator').attr('ajax') ,
			data: {
				field: field
			},
			success: function(result){
				jQuery('.field-properties').empty();
				jQuery('.field-properties').html(result);
				jQuery('.field-properties select').select2();

				if(edit){
					items = forms_creator_items[edit_number].items;
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
		jQuery('.forms-creator-items').empty();

		for(i in forms_creator_items){
			jQuery('.forms-creator-items').append("<div class=\"forms-creator-item xa\" item=\"" + i + "\"></div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"icon-move x1\"></div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"name x3\">" + forms_creator_items[i].name + "</div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"type x15\">" + forms_creator_items[i].type + "</div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"check x25\">" + check_select(forms_creator_items[i].check) + "</div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"icon-edit x1\"></div>");
			jQuery('.forms-creator-items').children().last().append("<div class=\"icon-delete x1\"></div>");
		}

		jQuery('.forms-creator-input').val(JSON.stringify(forms_creator_items));
		jQuery('.forms-creator-items .checks-input').select2();
	}

	function check_select(vals){
		checks = [
					"ansi" , "utf8" , "numeric" , "text" , "text_utf8" , "text_with_space" , 
					"text_with_space_utf8" , "username" , "url" , "search" , "status" , "source" , 
					"password" , "email" , "text_or_email" , "tf" , "tf_b" , "textarea" , 
					"tinymce" , "link" , "tel" , "tel_b" , "captcha" , "date"
				];

		output = "<select class=\"checks-input xa\">";

		for(i in checks){
			output += "<option value=\"" + checks[i] + "\"";
			if(checks[i] == vals)
				output += " selected";
			output += ">" + checks[i] + "</option>";
		}

		output += "</select>";

		return output; 
	}
});