var popup_page = [];
var popup_values = [];
var number_page = -1;

// varable for ajax
var form;
var form_cookie_name;
var form_cookie_site;
var cookie;
var inter;

jQuery(document).ready(function(){
	jQuery('body').append('<div id="popup" class="xa"></div>');
	update_page();

	jQuery(document).on('click' , '.popup_page' , function(event){
		event.stopPropagation();
	});

	jQuery(document).on('click' , '#popup' , function(){
		popup_page = [];
		popup_values = [];
		number_page = -1;

		jQuery('#popup').empty();
		jQuery('#popup').removeClass('showing');
		jQuery('#wrapper').removeClass('blur');
	});

	//resized
	jQuery(window).resize(resize_popup);

	jQuery(document).on('click' , '.popup_button[type="cancel"]' , cancel);
	jQuery(document).on('click' , '.popup_button[type="save"]' , save);

	// listmenu
	jQuery(document).on('click' , '.listmenu .parent' , function(){
		jQuery(this).addClass('showing');

		resize_popup();
	});

	jQuery(document).on('click' , '.listmenu .vals' , function(){
		if(jQuery(this).hasClass('selected')){
			jQuery(this).removeClass('selected');
			jQuery('.active-page .listmenu').removeAttr('vals');
		} else {
			jQuery('.active-page .listmenu .vals.selected').removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery('.active-page .listmenu').attr('vals' , jQuery(this).attr('vals'));
		}

		check_value();
	});

	// html
	jQuery(document).on('click' , '.html .vals' , function(){
		if(jQuery(this).hasClass('selected')){
			jQuery(this).removeClass('selected');
			jQuery('.active-page .html').removeAttr('vals');
		} else {
			jQuery('.active-page .html .vals.selected').removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery('.active-page .html').attr('vals' , jQuery(this).attr('vals'));
		}

		check_value();
	});

	// textmenu
	jQuery(document).on('keyup' , 'input.textmenu' , function(){
		val = jQuery(this).val();
		vals = jQuery(this).attr('vals');

		if(typeof vals == 'undefined' || vals != val){
			jQuery(this).attr('vals' , jQuery(this).val());
			if(val == "")
				jQuery(this).removeAttr('vals');
			check_value();
		}
	});

	// ajax
	jQuery(document).on("click" , '.active-page .select2' , function(){
		jQuery('.select2-dropdown').addClass('zindex');
	});

	jQuery(document).on("click" , '.active-page .thead-button' , function(){
		form.children('input[name="form-sort"]').val(jQuery(this).attr('val'));
		cookie.form_sort = jQuery(this).attr('val');

		if(!jQuery(this).hasClass('active')){
			form.children('input[name="form-sort-order"]').val('ASC');
			cookie.form_sort_order = 'ASC';
		} else {
			if(cookie.form_sort_order == 'ASC'){
				form.children('input[name="form-sort-order"]').val('DESC');
				cookie.form_sort_order = 'DESC';
			} else {
				form.children('input[name="form-sort-order"]').val('ASC');
				cookie.form_sort_order = 'ASC';
			}
		}

		jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		ajax_show();
	});

	jQuery(document).on("click" , '.active-page button.pagination-item' , function(){
		val = jQuery(this).attr('page');
		form.children('input[name="form-page"]').val(val);

		cookie.form_page = val;
		jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		ajax_show();
	});

	jQuery(document).on("change" , '.active-page .toolbar-sort' , function(){
		val = jQuery(this).val().split('.');
		form.children('input[name="form-sort"]').val(val[0]);
		form.children('input[name="form-sort-order"]').val(val[1]);

		cookie.form_sort = val[0];
		cookie.form_sort_order = val[1];
		jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		ajax_show();
	});

	jQuery(document).on("change" , '.active-page .toolbar-item' , function(){
		val = jQuery(this).val();
		form.children('input[name="form-search-category"]').val(val);

		cookie.form_search_category = val;
		jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});
	});

	jQuery(document).on("keyup" , '.active-page .toolbar-search-input-parent input' , function(){
		val = jQuery(this).val();
		tval = jQuery('.active-page .toolbar-item').val();

		if(jQuery(this).attr('val') != val){
			form.children('input[name="form-search"]').val(val);
			form.children('input[name="form-search-category"]').val(tval);
			form.children('input[name="form-page"]').val("1");

			jQuery(this).attr('val' , val);

			cookie.form_search = val;
			cookie.form_search_category = tval;
			cookie.form_page = 1;

			jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

			clearTimeout(inter);
			inter = setTimeout(function(){
				ajax_show();
			} , 1000);
		}
	});

	jQuery(document).on("change" , '.active-page .toolbar-number' , function(){
		val = jQuery(this).val();
		form.children('input[name="form-number"]').val(val);
		form.children('input[name="form-page"]').val("1");

		cookie.form_number = val;
		cookie.form_page = 1;
		jQuery.cookie(form_cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		ajax_show();
	});

	jQuery(document).on("click" , '.active-page .toolbar-clean' , function(){
		form.children('input[name="form-search"]').val('');
		form.children('input[name="form-search-category"]').val('');
		form.children('input[name="form-sort"]').val('');
		form.children('input[name="form-sort-order"]').val('');
		form.children('input[name="form-number"]').val('');
		form.children('input[name="form-page"]').val('');

		jQuery.removeCookie(form_cookie_name , {path: '/'});

		ajax_show();
	});

	jQuery(document).on("click" , '.active-page .selective' , function(){
		if(jQuery(this).hasClass('selected')){
			jQuery(this).removeClass('selected');
			jQuery('.active-page .index').removeAttr('vals');
		} else {
			jQuery('.active-page .selective').removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery('.active-page .index').attr('vals' , jQuery(this).attr('vals'));
		}

		check_value();
	});
});

function add_popup_page(name , element , forms , buttons){
	jQuery('.active-page').removeClass('active-page');

	number_page++;
	page = {name: name , element: element , forms: forms , buttons: buttons};
	popup_page[number_page] = page;
	popup_values[number_page] = [];
	update_page();
}

function update_page(){
	if(popup_page.length !== 0){
		values = [];
		jQuery('#popup').addClass('showing');
		jQuery('#wrapper').addClass('blur');

		jQuery('#popup').append('<div class="popup_page active-page xa s9 m7 l6 es05 em15 el2 aes05 aem15 ael2 zindex-' + (3000 + number_page) + '"><div class="popup_page_head xa"></div><div class="popup_page_in xa"></div><div class="popup_page_button xa"></div></div>');
		page = jQuery('#popup').children().last();

		page.children('.popup_page_head').text(popup_page[number_page].name);

		for(j in popup_page[number_page].buttons){
			cls = "";
			if(j == 'cancel')
				cls = " showing";
			page.children('.popup_page_button').append('<div class="popup_button' + cls + '" type="' + j + '">' + popup_page[number_page].buttons[j] + '</div>');
		}

		for(j in popup_page[number_page].forms){
			filed = popup_page[number_page].forms[j];
			elm = popup_page[number_page].element;
			vals = "";

			if(elm && typeof elm.value != 'undefined')
				vals = elm.value;
			else if(elm && typeof elm.val() != 'undefined' && elm.val() != "")
				vals = elm.val();
			else if(elm && typeof elm.attr('vals') != 'undefined' && elm.attr('vals') != "")
				vals = elm.attr('vals');

			if(field_out = add_form_field(filed.type , filed.value , j , vals)){
				values[j] = "";
				page.children('.popup_page_in').append(field_out);

				if(filed.type == 'ajax')
					ajax_show();
			}
		}

		popup_values[number_page] = values;	
		check_default_vals();
	}

	resize_popup();
}

function resize_popup(){
	win_hei = parseInt(jQuery(window).height());

	jQuery('.popup_page').each(function(){
		jQuery(this).removeAttr('style');

		hei = parseInt(jQuery(this).height());
		if(win_hei > hei)
			jQuery(this).css('margin-top' , parseInt((win_hei - hei) / 2));
		});
}

function cancel(){
	popup_page.pop();
	popup_values.pop();
	number_page--;

	if(number_page == -1){
		jQuery('#popup').empty();
		jQuery('#popup').removeClass('showing');
		jQuery('#wrapper').removeClass('blur');
	} else {
		jQuery('#popup .popup_page').last().remove();
		jQuery('#popup .popup_page').last().addClass('active-page');
	}
}

function save(){
	page = popup_page[number_page];
	values = popup_values[number_page];

	if(values.length == 1)
		for (i in values)
			if(typeof page.element.value != 'undefined')
				page.element.value = values[i];
			else
				page.element.val(values[i]).trigger('change');
	else
		if(typeof page.element.value != 'undefined')
			page.element.value = JSON.stringify(values);
		else
			page.element.val(JSON.stringify(values)).trigger('change');

	cancel();
}

function check_value(){
	values = popup_values[number_page];
	insert = true;

	for (i in values){
		vals = jQuery('.active-page .index[index="' + i + '"]').attr('vals');

		if(typeof vals != 'undefined')
			values[i] = vals;
		else
			insert = false;
	}

	if(insert)
		jQuery('.active-page .popup_button[type="save"]').addClass('showing');
	else
		jQuery('.active-page .popup_button[type="save"].showing').removeClass('showing');
}

function add_form_field(type , values , index , vals){
	if(type == "listmenu")
		return '<ul types="listmenu" class="listmenu index" index="' + index + '" vals="' + vals + '">' + listmenu_type(values) + '</ul>';
	else if(type == "textmenu")
		return '<div class="x3">' + values.text + '</div><input types="textmenu" type="text" class="textmenu x7 index ' + (typeof values.class != 'undefined' ? values.class : "") + '" index="' + index + '" placeholder="' + values.text + '" vals="' + (typeof values.default != 'undefined' ? values.default : vals) + '" value="' + (typeof values.default != 'undefined' ? values.default : "") + '" autofocus>';
	else if(type == "ajax")
		return '<div types="ajax" class="xa index ajax" index="' + index + '" ajax="' + values + '" vals="' + vals + '"></div>';
	else if(type == "html")
		return '<div types="html" class="xa index html" index="' + index + '" vals="' + vals + '">' + values + '</div>';

	return false;
}

function check_default_vals(){
	jQuery('.active-page .index').each(function(){
		vals = jQuery(this).attr('vals');
		type = jQuery(this).attr('types');

		if(vals != ''){
			if(type == 'listmenu')
			{
				jQuery(this).find('[vals="' + vals + '"]').addClass('selected');
				p = jQuery(this).children().children().children().children('[vals="' + vals + '"]');
				if(p.length)
					p.parent().parent().parent().addClass('showing');
				check_value();
			} else if(type == 'textmenu'){
				jQuery(this).val(vals);
				check_value();
			} else if(type == 'ajax'){
				jQuery(this).find('[vals="' + vals + '"]').addClass('selected');
				check_value();
			} else if(type == 'html'){
				jQuery(this).find('[vals="' + vals + '"]').addClass('selected');
				check_value();
			}
		}
	});
}

function listmenu_type(values){
	var output = "";
	for (i in values){
		var item = values[i];
		if(typeof item.children != 'undefined'){
			child = listmenu_type(item.children);
			if(child != "")
				output += '<li class="parent"><a>' + item.text + '</a><ul>' + child + '</ul></li>';
			else
				output += '<li><a>' + item.text + '</a></li>';
		} else {
			output += '<li><a class="vals" vals="' + item.vals + '">' + item.text + '</a></li>';
		}
	}
	return output;
}

// functions of ajax
function ajax_show(){
	jQuery('.active-page .index.ajax').each(function(){
		th = jQuery(this);

		if(jQuery('.active-page form').length)
			data = jQuery('.active-page form').serialize();
		else
			data = '';
		
		jQuery.ajax({
			type: "GET",
			url: th.attr('ajax') ,
			data: data ,
			beforesend: function(){
				th.empty();
			},
			success: function(result){
				th.html(result);

				th.find(".toolbar-item").select2();
				th.find(".toolbar-sort").select2();
				th.find(".toolbar-number").select2();

				init_form();
				check_default_vals();
				resize_popup();
			}
		});
	});
}

function init_form(){
	form = jQuery('.active-page form');

	if(form.length){
		if(form.children('input[name="form-name"]').length){
			form_cookie_name = form.children('input[name="form-name"]').val();
			form_cookie_site = jQuery.cookie(form_cookie_name);

			if(form_cookie_site){
				cookie = jQuery.parseJSON(form_cookie_site);

				if(typeof cookie.form_sort != 'undefined' && typeof cookie.form_sort_order != 'undefined'){
					if(cookie.form_sort_order == 'ASC')
						jQuery('.active-page .thead-button[val="' + cookie.form_sort + '"]').append('<span class="icon-arrow-down"></span>');
					else if(cookie.form_sort_order == 'DESC')
						jQuery('.active-page .thead-button[val="' + cookie.form_sort + '"]').append('<span class="icon-arrow-up"></span>');

					jQuery('.active-page .thead-button[val="' + cookie.form_sort + '"]').addClass('active');
				}

				if(typeof cookie.form_number != 'undefined' && typeof cookie.form_page != 'undefined' && typeof cookie.found != 'undefined'){
					size = parseInt(cookie.found);
					page = parseInt(cookie.form_page);

					if(cookie.number != 'all'){
						limits = parseInt(cookie.form_number);

						if(page && limits > 0 && limits <= 100 && !(limits == 1 && page == 1)){
							if(size > limits){
								form.parent().append('<div class="pagination xa"></div>');
								pagination = jQuery('.pagination');

								size_field = size / limits;
								size_field = (parseInt(size_field) < size_field) ? parseInt(size_field) + 1 : size_field;
								
								pagination.append("<button class=\"pagination-item item-prev\" page=\"" + ((page > 1) ? page - 1 : size_field) + "\"><span class=\"icon-" + ((jQuery('html').attr('dir') == "ltr") ? "arrow-left" : "arrow-right") + "\"></span></button>");

								k = 0;
								for (i = 0;i < size_field;i++) {
									if(i + 1 == page){
										pagination.append("<div class=\"pagination-item item-current\">" + (i + 1) + "</div>");
										k = 0;
									} else if(!i || i == (size_field - 1) || (i + 4) > page && (i - 2) < page) {
										pagination.append("<button class=\"pagination-item\" page=\"" + (i + 1) + "\">" + (i + 1) + "</button>");
										k = 0;
									} else if(!k) {
										pagination.append("<div class=\"pagination-item item-false\">...</div>");
										k = 1;
									}
								}

								pagination.append("<button class=\"pagination-item item-next\" page=\"" + ((page < size_field) ? page + 1 : 1) + "\"><span class=\"icon-" + ((jQuery('html').attr('dir') == "ltr") ? "arrow-right" : "arrow-left") + "\"></span></button>");
							}
						}
					}
				}
			}
		}
	}

	if(jQuery('.active-page .toolbar-search-input-parent input').length){
		val = jQuery('.active-page .toolbar-search-input-parent input').val();
		jQuery('.active-page .toolbar-search-input-parent input').val('');
		jQuery('.active-page .toolbar-search-input-parent input')[0].focus();
		jQuery('.active-page .toolbar-search-input-parent input').val(val);
	}
}