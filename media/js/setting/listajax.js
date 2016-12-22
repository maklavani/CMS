jQuery(document).ready(function(){
	jQuery('.listajax').each(function(){
		list_ajax(jQuery(this));
	});

	jQuery('select').change(function(){
		attr = jQuery(this).attr('list_change');

		if(typeof attr != 'undefined' && attr != false){
			elm = jQuery(jQuery(this).attr('list_change'));
			list_empty(elm);
			list_ajax(elm);
		}
	});

	function list_ajax(elm){
		list = elm.attr('list');
		var elm_list = elm.parent().parent().parent().find(list);
		elm_list.attr('list_change' , ".listajax_field_" + elm.attr('index'));

		if(elm_list.length == 1 && elm_list.val() != "" && elm_list.val() != null){
			if((elm.val() == "" || elm.val() == null))
				ajax_read(elm , elm.attr('ajax') , elm_list , elm.attr('default'));
		}
	}

	function list_empty(elm){
		elm.empty();
		elm.val('');
		elm.select2();

		change = elm.attr('list_change');
		if(typeof change != 'undefined' && change != false && jQuery(change).length == 1)
			list_empty(jQuery(change));
	}

	function ajax_read(elm , ajax , list , defaults){
		jQuery.ajax({
			type: "GET",
			url: ajax ,
			data: {
				"list": list.val() ,
				"self": elm.attr("self")
			},
			success: function(result){
				// console.log(result);
				if(result)
					result = jQuery.parseJSON(result);

				if(typeof result.status != 'undefined' && typeof result.items != 'undefined' && result.status == true){
					for(i in result.items){
						elm.append('<option value="' + result.items[i] + '">' + i + '</option>');

						if(defaults != "" && defaults == result.items[i]){
							elm.children().last().attr('selected' , 'selected');
							elm.val(result.items[i]);
							elm.removeAttr('default');
						}
					}

					jQuery(".listajax_field_" + elm.attr('index')).select2();

					change = elm.attr('list_change');
					if(typeof change != 'undefined' && change != false && jQuery(change).length == 1){
						jQuery(change).empty();
						jQuery(change).val('');
						list_ajax(jQuery(change));
					}
				}
			}
		});
	}
});