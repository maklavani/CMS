jQuery(document).ready(function(){
	var form = jQuery('form');
	var inter;

	if(form.children('input[name="form-name"]').length){
		var cookie_name = form.children('input[name="form-name"]').val();
		var cookie_site = jQuery.cookie(cookie_name);

		if(cookie_site){
			var cookie = jQuery.parseJSON(cookie_site);

			if(typeof cookie.form_sort != 'undefined' && typeof cookie.form_sort_order != 'undefined'){
				if(cookie.form_sort_order == 'ASC')
					jQuery('.thead-button[val="' + cookie.form_sort + '"]').append('<span class="icon-arrow-down"></span>');
				else if(cookie.form_sort_order == 'DESC')
					jQuery('.thead-button[val="' + cookie.form_sort + '"]').append('<span class="icon-arrow-up"></span>');

				jQuery('.thead-button[val="' + cookie.form_sort + '"]').addClass('active');
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

				if(size > 0 && typeof cookie.found != 'undefined')
				{
					form.parent().append('<div class="form-result xa"></div>');
					jQuery('.form-result').append('<div class=\"found-all\"><span>' + size + '</span>' + form.attr('result') + '</div>');
				}
			}
		}
	}

	if(jQuery('.toolbar-search-input-parent input').length){
		val = jQuery('.toolbar-search-input-parent input').val();
		jQuery('.toolbar-search-input-parent input').val('');
		jQuery('.toolbar-search-input-parent input')[0].focus();
		jQuery('.toolbar-search-input-parent input').val(val);
	}

	jQuery('.thead-button').click(function(){
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

		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		form.submit();
	});

	jQuery('button.pagination-item').click(function(){
		val = jQuery(this).attr('page');
		form.children('input[name="form-page"]').val(val);

		cookie.form_page = val;
		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		form.submit();
	});

	jQuery('.toolbar-sort').change(function(){
		val = jQuery(this).val().split('.');
		form.children('input[name="form-sort"]').val(val[0]);
		form.children('input[name="form-sort-order"]').val(val[1]);

		cookie.form_sort = val[0];
		cookie.form_sort_order = val[1];
		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		form.submit();
	});

	jQuery('.toolbar-item').change(function(){
		val = jQuery(this).val();
		form.children('input[name="form-search-category"]').val(val);

		cookie.form_search_category = val;
		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});
	});

	jQuery('.toolbar-search-input-parent input').keyup(function(){
		val = jQuery(this).val();
		tval = jQuery('.toolbar-item').val();
		form.children('input[name="form-search"]').val(val);
		form.children('input[name="form-search-category"]').val(tval);
		form.children('input[name="form-page"]').val("1");

		cookie.form_search = val;
		cookie.form_search_category = tval;
		cookie.form_page = 1;
		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});


		if(jQuery(this).attr('val') != val){
			jQuery(this).attr('val' , val);
			clearTimeout(inter);
			inter = setTimeout(function(){
				form.submit();
			} , 1000);
		}
	});

	jQuery('.toolbar-number').change(function(){
		val = jQuery(this).val();
		form.children('input[name="form-number"]').val(val);
		form.children('input[name="form-page"]').val("1");

		cookie.form_number = val;
		cookie.form_page = 1;
		jQuery.cookie(cookie_name , JSON.stringify(cookie) , {path: '/' , expires: 7});

		form.submit();
	});

	jQuery('.toolbar-clean').click(function(){
		form.children('input[name="form-search"]').val('');
		form.children('input[name="form-search-category"]').val('');
		form.children('input[name="form-sort"]').val('');
		form.children('input[name="form-sort-order"]').val('');
		form.children('input[name="form-number"]').val('');
		form.children('input[name="form-page"]').val('');

		jQuery.removeCookie(cookie_name , {path: '/'});

		form.submit();
	});

	jQuery('.checkbox-all').click(function(){
		jQuery('.checkbox').each(function(){
			if(jQuery(this).is(':checked'))
				jQuery(this).prop('checked' , false);
			else
				jQuery(this).prop('checked' , true);
		});
	});

	jQuery(document).on('change' , '.checkbox' , function(){
		checked = jQuery(this).is(':checked');

		cls = jQuery(this).parent().parent().children('td').slice(1 , 2).attr('class');
		level = parseInt(cls.replace('level level-' , ''));
		
		jQuery(this).parent().parent().nextAll().each(function(){
			clsb = jQuery(this).children('td').slice(1 , 2).attr('class');
			levelb = parseInt(clsb.replace('level level-' , ''));

			if(levelb > level){
				if(checked)
					jQuery(this).children('td').slice(0 , 1).children('input').prop('checked' , true);
				else
					jQuery(this).children('td').slice(0 , 1).children('input').prop('checked' , false);
			}
			else
				return false;
		});
	});

	jQuery('.toolbar-button').click(function(){
		if(jQuery(this).attr('form-button') == 'edit' || jQuery(this).attr('form-button') == 'delete' || jQuery(this).attr('form-button') == 'block' || jQuery(this).attr('form-button') == 'unblock'){
			if(len = jQuery('.checkbox:checked').length){
				if(jQuery(this).attr('form-button') == 'edit' && len != 1){
					alert(form.attr('error-more-select'));
				} else {
					if(jQuery(this).attr('form-button') != 'delete' || (jQuery(this).attr('form-button') == 'delete' && confirm(form.attr('error-confirm-delete')))){
						var ids = "";

						jQuery('.checkbox:checked').each(function(){
							if(ids != "")
								ids += ",";
							ids += jQuery(this).attr('tid');
						});

						form.children('input[name="form-button"]').val(jQuery(this).attr('form-button'));
						form.children('input[name="form-values"]').val(ids);
						form.submit();
					}
				}
			} else {
				alert(form.attr('error-one-select'));
			}
		} else {
			var ids = "";

			jQuery('.checkbox:checked').each(function(){
				if(ids != "")
					ids += ",";
				ids += jQuery(this).attr('tid');
			});

			form.children('input[name="form-button"]').val(jQuery(this).attr('form-button'));
			form.children('input[name="form-values"]').val(ids);
			form.submit();
		}
	});

	jQuery('.status').click(function(){
		if(jQuery(this).hasClass('icon-block'))
			form.children('input[name="form-button"]').val('unblock');
		else
			form.children('input[name="form-button"]').val('block');

		form.children('input[name="form-values"]').val(jQuery(this).attr('tid'));

		form.submit();
	});

	jQuery('.button').click(function(){
		form.children('input[name="form-values"]').val(jQuery(this).attr('tid'));
		form.children('input[name="form-button"]').val(jQuery(this).attr('type'));

		form.submit();
	});
});