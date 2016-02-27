var name , source , cookie , messages , scroll_item , selective , selective_types;
var value_popup_files = false;
var button_popup_files = false;
var editor = false;
var upload = false;
var upload_item = false;
var timeout = false;

jQuery(document).ready(function(){
	if(jQuery(".files").length){
		name = jQuery(".files").attr('name');
		source = jQuery(".files").attr('source');
		cookie = jQuery.cookie(name);
		messages = jQuery(jQuery(".files").attr('message'));
		scroll_item = jQuery(jQuery(".files").attr('scroll'));
		selective = jQuery(".files").attr('selective');
		selective_types = jQuery.parseJSON(jQuery(".files").attr('selective_types'));

		cookie = jQuery.parseJSON(cookie);

		if(cookie.view == 'list')
			list_image();
		else
			grid_image();

		clipboard();
		ajax_read('read' , source);
	}

	jQuery(document).on('click' , '.file-checked-all' , function(event){
		jQuery('.file-checked').each(function(){
			if(jQuery(this).is(':checked'))
			{
				jQuery(this).prop('checked' , false);
				jQuery(this).parent().removeClass('checked-file');
			}
			else
			{
				jQuery(this).prop('checked' , true);
				jQuery(this).parent().addClass('checked-file');
			}
		});

		event.stopPropagation();
	});

	jQuery(document).on('click' , '.checked-all' , function(){
		if(jQuery(this).children('.file-checked-all').is(':checked'))
			jQuery(this).children('.file-checked-all').prop('checked' , false);
		else
			jQuery(this).children('.file-checked-all').prop('checked' , true);

		jQuery('.file-checked').each(function(){
			if(jQuery(this).is(':checked'))
			{
				jQuery(this).prop('checked' , false);
				jQuery(this).parent().removeClass('checked-file');
			}
			else
			{
				jQuery(this).prop('checked' , true);
				jQuery(this).parent().addClass('checked-file');
			}
		});
	});

	jQuery(document).on('click' , '.file-checked' , function(event){
		if(selective != 'multiple' && jQuery.inArray("all" , selective_types) != 0 && jQuery('.file-checked:checked').length > 1){
			alert(jQuery('.files').attr('error-one-select'));
			jQuery(this).prop('checked' , false);
		} else if(	jQuery(this).parent().children('.file-checked:checked').length == 1 && jQuery.inArray("all" , selective_types) != 0 && (
					typeof jQuery(this).parent().attr('type') == 'undefined' || 
					jQuery.inArray(jQuery(this).parent().attr('type') , selective_types) == -1)){

			alert(jQuery('.files').attr('error-type-select') + selective_types.join());
			jQuery(this).prop('checked' , false);
			jQuery(this).parent().removeClass('checked-file');
		} else {
			jQuery(this).parent().toggleClass('checked-file');
		}

		if(jQuery('.active-page .html').length)
		{
			if(jQuery('.file-checked:checked').length > 0){
				vals = new Array();

				jQuery('.file-checked:checked').each(function(){
					vals.push(jQuery(this).attr('value'));
				});

				jQuery('.active-page .html').attr('vals' , vals.join());
			} else if(!jQuery('.file-checked:checked').length) {
				jQuery('.active-page .html').removeAttr('vals');
			}

			check_value();
		}

		event.stopPropagation();
	});

	jQuery(document).on('change' , '.file-checked-allg' , function(event){
		if(selective != 'multiple' && jQuery.inArray("all" , selective_types) != 0 && jQuery('.file-checked:checked').length > 1){
			alert(jQuery('.files').attr('error-one-select'));
			jQuery(this).prop('checked' , false);
		} else if(	jQuery(this).parent().children('.file-checked:checked').length == 1 && jQuery.inArray("all" , selective_types) != 0 && (
					typeof jQuery(this).parent().attr('type') == 'undefined' || 
					jQuery.inArray(jQuery(this).parent().attr('type') , selective_types) == -1)){

			alert(jQuery('.files').attr('error-type-select') + selective_types.join());
			jQuery(this).prop('checked' , false);
			jQuery(this).parent().removeClass('checked-file');
		} else {
			jQuery(this).parent().toggleClass('checked-file');
		}

		if(jQuery('.active-page .html').length)
		{
			if(jQuery('.file-checked:checked').length > 0){
				vals = new Array();

				jQuery('.file-checked:checked').each(function(){
					vals.push(jQuery(this).attr('value'));
				});

				jQuery('.active-page .html').attr('vals' , vals.join());
			} else if(!jQuery('.file-checked:checked').length) {
				jQuery('.active-page .html').removeAttr('vals');
			}

			check_value();
		}

		event.stopPropagation();
	});

	jQuery(window).resize(resized_upload);

	// functions toolbar
	jQuery(document).on('click' , '.files-toolbar-item' , function(){
		if(!jQuery(this).hasClass('disabled')){
			if(jQuery(this).attr('val') == 'grid'){
				grid_image();
				if(typeof resize_popup != 'undefined')
					resize_popup();
			} else if(jQuery(this).attr('val') == 'list'){
				list_image();
				if(typeof resize_popup != 'undefined')
					resize_popup();
			} else if(jQuery(this).attr('val') == 'exit'){
				ajax_read('read' , source);
			} else if(jQuery(this).attr('val') == 'save'){
				if(jQuery('.CodeMirror').length)
					ajax_read('save' , JSON.stringify(editor.getValue()) , jQuery('#code').attr('file'));
				else if(jQuery('.files > form').attr('id') == 'com_content_setting-form')
				{
					st_form = {'permission': jQuery('.files > form [name="field_input_permission"]').val() , 'deleted': jQuery('.files > form .fields-input-radio .checked').attr('val') , 'users': jQuery('.files > form [name="field_input_users[]"]').val() , 'src': jQuery('.files > form [name="field_input_src"]').val()};
					ajax_read('savesetting' , JSON.stringify(st_form) , jQuery('#code').attr('file'));
				}
			} else if(jQuery(this).attr('val') == 'eraser'){
				cookie.clipboard = "";
				cookie.clipboard_action = "";
				jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});

				clipboard();
			} else if(cookie.clipboard_action != "" && jQuery(this).attr('val') == 'paste') {
				ajax_read('paste' + cookie.clipboard_action , JSON.stringify(cookie.clipboard) , source);
			} else if(cookie.clipboard_action != "" && jQuery(this).attr('val') == 'archive') {
				popup_text('archive' , jQuery(this).attr('text') , 'archive');
			} else if(jQuery(this).attr('val') == 'new-folder') {
				popup_text('new-folder' , jQuery(this).attr('text') , 'new-folder');
			} else if(jQuery(this).attr('val') == 'new-file') {
				popup_text('new-file' , jQuery(this).attr('text') , 'new-file');
			} else if(jQuery(this).attr('val') == 'upload') {
				ajax_read('uploadform');
			} else if(len = jQuery('.file-checked:checked').length){
				if((jQuery(this).attr('val') == 'extract' || jQuery(this).attr('val') == 'rename' || jQuery(this).attr('val') == 'download' || jQuery(this).attr('val') == 'setting') && len != 1){
					alert(jQuery('.files').attr('error-more-select'));
				} else if(jQuery(this).attr('val') == 'extract' && len == 1 && jQuery('.file-checked:checked').parent().attr('type') != 'zip'){
					alert(jQuery('.files').attr('error-archive-select'));
				} else if(jQuery(this).attr('val') == 'rename'){
					popup_text('rename' , jQuery(this).attr('text') , jQuery('.file-checked:checked').parent().attr('names'));
				} else if(jQuery(this).attr('val') == 'download' && len == 1 && (jQuery('.file-checked:checked').parent().hasClass('folder') || jQuery('.file-checked:checked').parent().hasClass('back'))){
					alert(jQuery('.files').attr('error-file-select'));
				} else if(jQuery(this).attr('val') == 'setting'){
					ajax_read('setting' , jQuery('.file-checked:checked').attr('value'));
				} else {
					if(jQuery(this).attr('val') != 'delete' || (jQuery(this).attr('val') == 'delete' && confirm(jQuery('.files').attr('error-confirm-delete')))){
						var srcs = new Array();
						var index = 0;

						jQuery('.file-checked:checked').each(function(){
							srcs[index] = jQuery(this).attr('value');
							index++;
						});

						if(jQuery(this).attr('val') == 'copy'){
							cookie.clipboard = srcs;
							cookie.clipboard_action = 'copy';
							jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});

							clipboard();
						} else if(jQuery(this).attr('val') == 'cut') {
							cookie.clipboard = srcs;
							cookie.clipboard_action = 'cut';
							jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});

							clipboard();
						} else if(jQuery(this).attr('val') == 'extract') {
							ajax_read('extract' , JSON.stringify(srcs) , source);
						} else if(jQuery(this).attr('val') == 'download') {
							ajax_read('download' , JSON.stringify(srcs) , source);
						} else {
							ajax_read(jQuery(this).attr('val') , JSON.stringify(srcs));
						}
					}
				}
			} else {
				alert(jQuery('.files').attr('error-one-select'));
			}
		}
	});

	// folder list
	jQuery(document).on('click' , '.files-list .text' , function(event){
		if(!jQuery('.files').hasClass('blur')){
			jQuery('.files-list .current').removeClass('current');
			jQuery(this).parent().parent().addClass('current');
			folder = jQuery(this).parent().attr('folder');
			ajax_read('read' , folder);
			event.stopPropagation();
		}
	});

	jQuery(document).on('click' , '.files-list a > .icon' , function(event){
		if(!jQuery('.files').hasClass('blur')){
			jQuery(this).parent().parent().toggleClass('showing');
			event.stopPropagation();
		}
	});

	// toolbar
	jQuery(document).on('click' , '.file-item.folder' , function(event){
		if(!jQuery('.files').hasClass('blur')){
			folder = jQuery(this).children('.file-name').text();
			ajax_read('read' , source + folder + '_DIR_');
			event.stopPropagation();
		}
	});

	jQuery(document).on('click' , '.file-item.back' , function(event){
		if(!jQuery('.files').hasClass('blur')){
			sources = source.split('_DIR_');
			folder = "";

			for(i in sources)
				if(parseInt(i) + 2 < sources.length)
					folder += sources[i] + '_DIR_';

			ajax_read('read' , folder);
			event.stopPropagation();
		}
	});

	jQuery(document).on('click' , '.file-item' , function(event){
		if(!jQuery('.files').hasClass('blur') && !jQuery(this).hasClass('check-all') && !jQuery(this).hasClass('folder') && !jQuery(this).hasClass('back'))
			ajax_read('edit' , jQuery(this).children('.file-checked').attr('value') , source);
	});

	jQuery(document).on('click' , '.active-page .popup_button[type="save"]' , function(){
		if(!jQuery('.files').hasClass('blur')){
			if(button_popup_files == "rename")
				ajax_read('rename' , value_popup_files.value , jQuery('.file-checked:checked').attr('value'));
			else if(button_popup_files == "archive" && cookie.clipboard != "" && cookie.clipboard_action != "")
				ajax_read('archive' + cookie.clipboard_action , JSON.stringify(cookie.clipboard) , source , value_popup_files.value);
			else if(button_popup_files == "new-folder")
			{
				folder = value_popup_files.value.split(' ');
				folders = "";
				folder_passed = new Array();
				for(i in folder)
					if(folder[i] != "" && jQuery.inArray(folder[i] , folder_passed) == -1){
						if(folders != "")
							folders += " ";
						folders += folder[i];
						folder_passed.push(folder[i]);
					}

				ajax_read('new-folder' , folders , source);
			}
			else if(button_popup_files == "new-file")
				ajax_read('new-file' , value_popup_files.value , source);
		}
	});

	// Upload
	jQuery(document).on('mouseenter' , '#file-input' , function(){
		jQuery(this).parent().addClass('hover');
	});

	jQuery(document).on('mouseout' , '#file-input' , function(){
		jQuery(this).parent().removeClass('hover');
	});

	jQuery(document).on('change' , '#file-input' , function(){
		upload = jQuery('#file-input').get(0).files;
		upload_item = 0;
		update_upload_list();
	});
});

function init_upload(){
	if(jQuery(".files").length){
		name = jQuery(".files").attr('name');
		source = jQuery(".files").attr('source');
		cookie = jQuery.cookie(name);
		messages = jQuery(jQuery(".files").attr('message'));
		scroll_item = jQuery(jQuery(".files").attr('scroll'));
		selective = jQuery(".files").attr('selective');
		selective_types = jQuery.parseJSON(jQuery(".files").attr('selective_types'));

		cookie = jQuery.parseJSON(cookie);
		ajax_read('read' , source);
	}
}

function ajax_read(action , value , source_file , name_file)
{
	if(typeof value == 'undefined')
		value = false;

	if(typeof source_file == 'undefined')
		source_file = false;

	if(typeof name_file == 'undefined')
		name_file = false;

	jQuery.ajax({
		type: "POST",
		url: jQuery('.files').attr('ajax') ,
		data: {
			"upload": action ,
			"upload_value": value , 
			"upload_source": source_file , 
			"upload_name": name_file
		},
		beforeSend: function(){
			jQuery('.files').addClass('blur');
		},
		success: function(result){
			// console.log(result);
			jQuery('.files').removeClass('blur');

			if(result)
				result = jQuery.parseJSON(result);

			if(typeof result.status != 'undefined' && result.status == true)
			{
				if(action == 'read')
				{
					editor = false;

					jQuery('.files').empty();
					jQuery('.files').html(result.html);
					source = value;
					jQuery('.files').attr('source' , source);

					jQuery('.files-toolbar-item').removeClass('disabled');
					jQuery('.files-toolbar-item.edit').addClass('disabled');

					if(cookie.clipboard == ""){
						jQuery('.files-toolbar-item[val="paste"]').addClass('disabled');
						jQuery('.files-toolbar-item[val="archive"]').addClass('disabled');
					}

					if(selective != 'multiple')
						jQuery('.file-item.check-all').remove();

					jQuery('.file-item').each(function(){
						if(!jQuery(this).hasClass('folder') && !jQuery(this).hasClass('back') && !jQuery(this).hasClass('check-all'))
							if(jQuery.inArray('all' , selective_types) != 0 && jQuery.inArray(jQuery(this).attr('type') , selective_types) == -1)
								jQuery(this).remove();
					});

					if(jQuery('.active-page .html').length && typeof jQuery('.active-page .html').attr('vals') != 'undefined'){
						srcs = jQuery('.active-page .html').attr('vals').split(',');

						for(i in srcs){
							jQuery('.file-checked[value="' + srcs[i] + '"]').prop('checked' , true);
							jQuery('.file-checked[value="' + srcs[i] + '"]').parent().addClass('checked-file');
						}
					}

					if(typeof resize_popup != 'undefined')
						resize_popup();
				} else if(action == 'edit'){
					jQuery('.messages').remove();
					jQuery('.files').empty();
					jQuery('.files').html(result.html);

					editor = 
						CodeMirror.fromTextArea(document.getElementById("code") , {
							lineNumbers: true ,
							extraKeys: {
								'Tab': function(cm){
									cm.replaceSelection("\t" , "end");
								}
							} , 
							theme : jQuery('.files').attr('theme')
						});

					jQuery('.files-toolbar-item').removeClass('active');
					jQuery('.files-toolbar-item').addClass('disabled');
					jQuery('.files-toolbar-item.edit').removeClass('disabled');
					resized_upload();
				} else if(action == 'delete' || action == 'rename' || action == 'extract' || action == 'new-folder' || action == 'new-file'){
					ajax_read('read' , source);

					if(action == 'delete'){
						srcs = jQuery.parseJSON(value);

						for(i in srcs){
							src = srcs[i].replace(/\//g , '_DIR_');

							if(jQuery('.files-list [folder="' + src + '"]').parent().parent().children('li').length == 1){
								jQuery('.files-list [folder="' + src + '"]').parent().parent().parent().removeClass('parent');
								jQuery('.files-list [folder="' + src + '"]').parent().parent().parent().children('a').children('.icon').remove();
								jQuery('.files-list [folder="' + src + '"]').parent().parent().parent().children('a').prepend('<div class="space"></div>');
								jQuery('.files-list [folder="' + src + '"]').parent().parent().remove();
							}

							jQuery('.files-list [folder="' + src + '"]').parent().remove();
						}
					} else if(action == 'new-folder'){
						folders = value.split(' ');
						for(i in folders){
							new_source = source_file + folders[i] + '_DIR_';

							if(jQuery('.files-list [folder="' + new_source + '"]').length == 0){

								if(jQuery('.files-list [folder="' + source_file + '"]').parent().children('ul').length == 0){
									jQuery('.files-list [folder="' + source_file + '"]').parent().addClass('parent');
									jQuery('.files-list [folder="' + source_file + '"]').children('.space').remove();
									jQuery('.files-list [folder="' + source_file + '"]').prepend('<div class="icon icon-plus"></div>');
									jQuery('.files-list [folder="' + source_file + '"]').parent().append('<ul></ul>');
								}

								jQuery('.files-list [folder="' + source_file + '"]').parent().children('ul').append('<li><a folder="' + new_source + '"><div class="space"></div><div class="text">' + folders[i] + '</div></a></li>');
							}
						}
					}
				} else if(action == 'uploadform'){
					jQuery('.messages').remove();
					jQuery('.files').empty();
					jQuery('.files').html(result.html);

					jQuery('.files-toolbar-item').removeClass('active');
					jQuery('.files-toolbar-item').addClass('disabled');
					jQuery('.files-toolbar-item[val="exit"]').removeClass('disabled');
					if(typeof resize_popup != 'undefined')
						resize_popup();
				} else if(action == 'download' && typeof result.src != 'undefined'){
					window.open(result.src , '_blank');
				} else if(action == 'setting'){
					jQuery('.messages').remove();
					jQuery('.files').empty();
					jQuery('.files').html(result.html);

					jQuery('.files-toolbar-item').removeClass('active');
					jQuery('.files-toolbar-item').addClass('disabled');
					jQuery('.files-toolbar-item[val="save"]').removeClass('disabled');
					jQuery('.files-toolbar-item[val="exit"]').removeClass('disabled');

					jQuery(".list_field_1").select2();
					jQuery('.fields-group[type="radio"]').each(function(){fi = jQuery(this).children('.fields-input');fi.append('<div class="fields-input-radio xa"></div>');fir = fi.children('.fields-input-radio');fi.children('input').each(function(){cls = "";if(jQuery(this).is(':checked')) cls = "checked";fir.append('<div class="radio-item ' + cls + '" val="' + jQuery(this).attr('value') + '">' + jQuery(this).attr('label') + '</div>');});});
					jQuery(".ajax_field_3").select2({ tags: true,dir: jQuery('html').attr('dir'),ajax: {url: jQuery('#com_content_setting-form').attr('action') + '=users' ,type: "GET",dataType: "json" ,delay: 250 ,data: function(params){return {q: params.term};},processResults: function(data) {var res = [];for(var i  = 0 ; i < data.length; i++)res.push({id: data[i].name , text: data[i].name});return {results: res}},cache: true},minimumInputLength: 1});

					if(typeof resize_popup != 'undefined')
						resize_popup();
				} else if(action == 'savesetting'){
					ajax_read('setting' , jQuery('.files > form [name="field_input_src"]').val());
				}

				if (action == 'pastecopy' || 
					action == 'pastecut' || 
					action == 'archivecopy' || 
					action == 'archivecut'
					){
					cookie.clipboard = "";
					cookie.clipboard_action = "";
					jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});
					clipboard();
					ajax_read('read' , source);
				}

				if(cookie.view == 'list')
					list_image();
				else
					grid_image();
				clipboard();
			}

			if(typeof result.message != 'undefined'){
				if(!messages.children('.messages').length)
					messages.prepend('<div class="messages xa"></div>');
				messages.children('.messages').prepend(result.message);
				clearTimeout(timeout);
				timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
			}

			if(typeof resize_popup != 'undefined')
				resize_popup();
		}
	});
}

function grid_image(){
	jQuery(".files").addClass('grid');
	jQuery(".files").removeClass('list');

	jQuery('.files-toolbar-item.icon-grid').addClass('active');
	jQuery('.files-toolbar-item.icon-list').removeClass('active');

	jQuery('.files.grid .file-image.image').each(function(){
		jQuery(this).css('background-image' , 'url(' +  jQuery(this).attr('source-image') + ')');
	});

	cookie.view = 'grid';
	jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});
}

function list_image(){
	jQuery(".files").removeClass('grid');
	jQuery(".files").addClass('list');

	jQuery('.files-toolbar-item.icon-list').addClass('active');
	jQuery('.files-toolbar-item.icon-grid').removeClass('active');

	jQuery('.files.list .file-image.image').each(function(){
		jQuery(this).removeAttr('style');
	});

	cookie.view = 'list';
	jQuery.cookie(name , JSON.stringify(cookie) , {path: '/' , expires: 7});
}

function clipboard(){
	jQuery('.files-toolbar-item.icon-copy').removeClass('active');
	jQuery('.files-toolbar-item.icon-cut').removeClass('active');
	jQuery('.file-item').removeClass('clipboard');

	if(cookie.clipboard == "" || editor || upload){
		jQuery('.files-toolbar-item.icon-paste').addClass('disabled');
		jQuery('.files-toolbar-item.icon-paste').removeClass('active');

		jQuery('.files-toolbar-item.icon-archive').addClass('disabled');
		jQuery('.files-toolbar-item.icon-archive').removeClass('active');

	} else {
		for(i in cookie.clipboard)
			jQuery('.file-checked').each(function(){
				value = jQuery(this).attr('value');

				if(value == cookie.clipboard[i] || value.indexOf(cookie.clipboard[i]) > -1)
					jQuery(this).parent().addClass('clipboard');
			});

		jQuery('.files-toolbar-item.icon-paste').removeClass('disabled');
		jQuery('.files-toolbar-item.icon-paste').addClass('active');

		jQuery('.files-toolbar-item.icon-archive').removeClass('disabled');
		jQuery('.files-toolbar-item.icon-archive').addClass('active');

		jQuery('.files-toolbar-item.icon-' + cookie.clipboard_action).addClass('active');
	}
}

function popup_text(button , name , value)
{
	value_popup_files = false;
	value_popup_files = jQuery(this);
	button_popup_files = button;

	value_popup_files.value = value;
	add_popup_page(name , value_popup_files , {0: {type: 'textmenu' , value: {text: jQuery('.files').attr('names') , class: "popup-left"}}} , {save: jQuery('.files').attr('save')});
	jQuery('.active-page .popup_button[type="save"]').addClass('showing');
}

function resized_upload()
{
	jQuery('.CodeMirror').removeAttr('style');

	wid = parseInt(jQuery(window).width());
	hei = parseInt(jQuery(window).height());

	if(wid > 991)
	{
		size = 120 + parseInt(jQuery('.toolbar').height()) + parseInt(jQuery('.files-toolbar').height());
		if(jQuery('.messages').length)
			size += parseInt(jQuery('.messages').height());
		jQuery('.CodeMirror').height(hei - size);
	}
}

function update_upload_list()
{
	jQuery('#upload-list').empty();

	for (i = 0;i < upload.length;i++){
		jQuery('#upload-list').append("<div class='upload-list-item xa'></div>");
		item = jQuery('#upload-list').children().last();
		item.append("<div class='upload-list-details x6 s7 m6 l7'></div>");
		item.children('.upload-list-details').append("<div class='upload-list-name x95 ex025 aex025'>" + upload[i].name + "</div>");
		item.children('.upload-list-details').append("<div class='upload-list-progress x95 ex025 aex025'><div class='upload-list-progress-per-shadow'></div><div class='upload-list-progress-per'></div></div>");
		item.append("<div class='upload-list-size x25 s2 m25 l2'>" + get_size(upload[i].size) + "</div>");
		item.append("<div class='upload-list-close x15 s1 m15 l1 icon-close'></div>");
	}

	if(typeof resize_popup != 'undefined')
		resize_popup();

	upload_start();
}

function upload_start(){
	if(upload_item < upload.length){
		jQuery.ajax({
			type: 'GET' , 
			url: jQuery('.files').attr('ajax') , 
			data: {
				uploadper_size: upload[upload_item].size , 
				uploadper_name: upload[upload_item].name , 
				source: source
			},
			success: function(result){
				if(result)
					result = jQuery.parseJSON(result);

				if(typeof result.message != 'undefined'){
					if(!messages.children('.messages').length)
						messages.prepend('<div class="messages xa"></div>');
					messages.children('.messages').prepend(result.message);
					clearTimeout(timeout);
					timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
				}

				if(typeof result.status != 'undefined' && result.status == true){
					upload_finished(upload_item);
				} else {
					item = jQuery('.upload-list-item').slice(upload_item , upload_item + 1);
					scroll_to(item);
					item.removeClass('enabled');
					item.addClass('broken');
					upload_item++;
					upload_start();
				}
			}
		});
	} else {
		scroll_item.animate({scrollTop: 0} , 1500);

		if(!messages.children('.messages').length)
			messages.prepend('<div class="messages xa"></div>');
		messages.children('.messages').prepend("<div class=\"message xa\"><span class=\"icon-success\"></span>" + jQuery('#upload-form').attr('done') + "</div><div class=\"icon-close\"></div>");
		clearTimeout(timeout);
		timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
	}
}

function upload_finished(item_num){
	var file_data = upload[item_num];
	var form_data = new FormData();
	form_data.append('file' , file_data);

	jQuery.ajax({
		type: 'POST',
		url: jQuery('#upload-form').attr('action') , 
		data: form_data , 
		contentType: false , 
		processData: false ,
		xhr: function(){
			var xhr = jQuery.ajaxSettings.xhr();

			if(xhr.upload) 
				xhr.upload.addEventListener('progress' , progress_bar , false);

			return xhr;
		},
		beforeSend: function(xhr){
			var item = jQuery('.upload-list-item').slice(item_num , item_num + 1);
			item.addClass('enabled');

			scroll_to(item);

			item.find('.upload-list-close').click(function(){
				item.removeClass('enabled');
				item.addClass('broken');
				xhr.abort();
				upload_item++;
				upload_start();
			});
		},
		success: function(result){
			item = jQuery('.upload-list-item').slice(item_num , item_num + 1);
			scroll_to(item);

			if(result)
				result = jQuery.parseJSON(result);

			if(typeof result.message != 'undefined'){
				if(!messages.children('.messages').length)
					messages.prepend('<div class="messages xa"></div>');
				messages.children('.messages').prepend(result.message);
				clearTimeout(timeout);
				timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
			}

			if(typeof result.status != 'undefined' && result.status == true){
				item.removeClass('enabled');
				item.addClass('success');
			} else {
				item.removeClass('enabled');
				item.addClass('broken');
			}

			upload_item++;
			upload_start();
		}
	});
}

function scroll_to(item){
	tops = parseInt(item.position().top);
	o_tops = 290 + parseInt(jQuery('.toolbar').height());
	s_tops = parseInt(scroll_item.scrollTop());
	w_hei = parseInt(jQuery(window).height()) - 150;

	if(jQuery('.messages').length)
		o_tops += parseInt(jQuery('.messages').height());

	if(tops + o_tops > w_hei + s_tops)
		scroll_item.animate({scrollTop: (tops + o_tops - w_hei)} , 100);
}

function progress_bar(e){
	wid = Math.round(e.loaded * 10000 / e.total) / 100 + '%';
	item = jQuery('.upload-list-item').slice(upload_item , upload_item + 1);
	item.find('.upload-list-progress-per').width(wid);
}

function get_size(file_size)
{
	if(file_size > 1073741824)
		file_size_new = Math.round(file_size * 100 / 1073741824) / 100 + " GB";
	else if(file_size > 1048576)
		file_size_new = Math.round(file_size * 100 / 1048576) / 100 + " MB";
	else if(file_size > 1024)
		file_size_new = Math.round(file_size * 100 / 1024) / 100 + " KB";
	else
		file_size_new = file_size + " B";

	return file_size_new;
}