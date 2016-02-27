jQuery(document).ready(function(){
	jQuery('.position_button').each(function(){
		if(jQuery(this).parent().hasClass('level-0'))
			jQuery(this).append('<div class="position_button_select"><div class="position_button_select_in" act="in">' + jQuery('.menu_position').attr('in_text')  + '</div></div>');
		else
			jQuery(this).append('<div class="position_button_select"><div class="position_button_select_in" act="after">' + jQuery('.menu_position').attr('after_text') + '</div><div class="position_button_select_in" act="in">' + jQuery('.menu_position').attr('in_text')  + '</div></div>');
	});

	parent = jQuery('input[name="field_input_parent"]').val();
	index = jQuery('input[name="field_input_index"]').val();

	if(jQuery('.menu_position .item-' + parent + ' > ul').length){
		if(index == 1)
			jQuery('.menu_position .item-' + parent + ' > ul').prepend('<div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div>');
		else
			jQuery('.menu_position .item-' + parent + ' > ul').children().slice(index - 2 , index - 1).after('<div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div>');
	}
	else
		jQuery('.menu_position .item-' + parent).append('<ul><div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div></ul>')

	jQuery(document).on('click' , '.position_button_select_in[act="after"]' , function(event){
		if(jQuery('.self_button').parent().children().length == 1)
		{
			jQuery('.self_button').parent().parent().removeClass('parent');
			jQuery('.self_button').parent().remove();
		}
		else
			jQuery('.self_button').remove();

		jQuery(this).parent().parent().parent().after('<div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div>');

		jQuery(this).parent().parent().parent().parent().children('li').each(function(){
			jQuery(this).attr('index' , jQuery(this).index() + 1);
		});

		jQuery('input[name="field_input_parent"]').val(jQuery(this).parent().parent().parent().attr('parent'));
		jQuery('input[name="field_input_index"]').val(parseInt(jQuery(this).parent().parent().parent().attr('index')) + 1);

		event.stopPropagation();
	});

	jQuery(document).on('click' , '.position_button_select_in[act="in"]' , function(event){
		if(jQuery('.self_button').parent().children().length == 1)
			jQuery('.self_button').parent().remove();
		else
			jQuery('.self_button').remove();

		if(jQuery(this).parent().parent().parent().children('ul').length)
			jQuery(this).parent().parent().parent().children('ul').prepend('<div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div>');
		else
			jQuery(this).parent().parent().parent().append('<ul><div class="self_button">' + jQuery('.menu_position').attr('self_text') + '</div></ul>');

		jQuery(this).parent().parent().parent().addClass('parent showing');

		jQuery('input[name="field_input_parent"]').val(jQuery(this).parent().parent().parent().attr('item'));
		jQuery('input[name="field_input_index"]').val(1);

		event.stopPropagation();
	});

	jQuery(document).on('click' , '.menu_position .parent > .position_button' , function(){
		jQuery(this).parent().toggleClass('showing');
	});
});