jQuery(document).ready(function(){
	var item = 0;
	var adddetails_items = {};
	var edit = false;
	var edit_number = false;
	var times;

	if(jQuery('.adddetails-input').val() != ""){
		console.log(jQuery('.adddetails-input').val());
		vals = jQuery.parseJSON(jQuery('.adddetails-input').val());

		for(i in vals){
			adddetails_items[item] = vals[i];
			item++;
		}

		update_html();
	}

	jQuery('.adddetails-button').click(function(){
		adddetails_items[item] = "";
		item++;
		update_html();
	});

	jQuery(document).on('click' , '.adddetails-item .icon-edit' , function(){
		edit = true;
		edit_number = parseInt(jQuery(this).parent().attr('item'));

		jQuery(this).parent().addClass('edit');
	});

	jQuery(document).on('click' , '.adddetails-item .icon-delete' , function(){
		number = parseInt(jQuery(this).parent().attr('item'));
		number_inc = 0;
		adddetails_items_b = {};

		for(i in adddetails_items)
			if(i != number){
				adddetails_items_b[number_inc] = adddetails_items[i];
				number_inc++;
			}

		adddetails_items = adddetails_items_b;
		update_html();
	});

	jQuery(document).on('click' , '.adddetails-item .icon-move' , function(){
		if(jQuery(this).parent().hasClass('move'))
			jQuery(this).parent().removeClass('move');
		else {
			jQuery('.adddetails-item.move').removeClass('move');
			jQuery(this).parent().addClass('move');
		}
	});

	jQuery(document).on('keyup' , function(event){
		if(jQuery('.adddetails-item.move').hasClass('move')){
			number = parseInt(jQuery('.adddetails-item.move').attr('item'));
			temp = adddetails_items[number];
			count = 0;

			for (i in adddetails_items)
				if (adddetails_items.hasOwnProperty(i))
					count++;

			if(event.keyCode == 38 && number > 0){
				adddetails_items[number] = adddetails_items[number - 1];
				adddetails_items[number - 1] = temp;
				update_html();
				jQuery('.adddetails-item[item="' + (number - 1) + '"]').addClass('move');
			} else if(event.keyCode == 40 && (number + 1) < count){
				adddetails_items[number] = adddetails_items[number + 1];
				adddetails_items[number + 1] = temp;
				update_html();
				jQuery('.adddetails-item[item="' + (number + 1) + '"]').addClass('move');
			}
		}
	});

	jQuery(document).on('keyup' , '.adddetails-item .name input' , function(){
		adddetails_items[jQuery(this).parent().parent().attr('item')] = jQuery(this).val();
		jQuery('.adddetails-input').val(JSON.stringify(adddetails_items));
	});

	function update_html(){
		jQuery('.adddetails-items').empty();

		for(i in adddetails_items){
			jQuery('.adddetails-items').append("<div class=\"adddetails-item xa\" item=\"" + i + "\"></div>");
			jQuery('.adddetails-items').children().last().append("<div class=\"icon-move x1\"></div>");
			jQuery('.adddetails-items').children().last().append("<div class=\"name x75\"><input type=\"text\" value=\"" + adddetails_items[i] + "\">");
			jQuery('.adddetails-items').children().last().append("<div class=\"icon-delete x15\"></div>");
		}

		jQuery('.adddetails-input').val(JSON.stringify(adddetails_items));
	}
});