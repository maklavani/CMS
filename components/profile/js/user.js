var files , upload , messages , timeout , item = false , leftx = false , topx = false , itemb = false;

jQuery(document).ready(function(){
	messages = jQuery('.component').parent();

	jQuery(document).on('change' , '.file-input' , function(){
		files = jQuery(this).parent();
		upload = jQuery(this).get(0).files;
		upload_start(jQuery(this).attr('name'));
	});

	jQuery(window).resize(resized_resize_pic);

	jQuery(document).on('mousedown' , '.crop-item' , function(event){
		itemb = jQuery(this);
		event.stopPropagation();
	});

	jQuery(document).on('mousedown' , '.crop' , function(event){
		item = jQuery(this);
		leftx = event.pageX - jQuery(this).offset().left;
		topx = event.pageY - jQuery(this).offset().top;
	});

	jQuery(document).on('mouseup' , function(){
		item = false;
		itemb = false;
	});

	jQuery(document).on('mousemove' , '.resize-square' , function(event){
		if(item){
			tops = event.pageY - jQuery('.resize-square').offset().top;
			rights = jQuery('.resize-square').offset().left + jQuery('.resize-square').width() -  event.pageX;
			bottoms = jQuery('.resize-square').offset().top + jQuery('.resize-square').height() -  event.pageY;
			lefts = event.pageX - jQuery('.resize-square').offset().left;

			if(tops >= 0 && rights >= 0 && bottoms >= 0 && lefts >= 0){
				jQuery('.crop').css('left' , lefts - leftx);
				jQuery('.crop').css('top' , tops - topx);

				if(parseInt(jQuery('.crop').css('left')) < 0)
					jQuery('.crop').css('left' , 0);

				if(parseInt(jQuery('.crop').css('left')) + jQuery('.crop').width() > jQuery('.resize-square').width())
					jQuery('.crop').css('left' , jQuery('.resize-square').width() - jQuery('.crop').width());

				if(parseInt(jQuery('.crop').css('top')) < 0)
					jQuery('.crop').css('top' , 0);

				if(parseInt(jQuery('.crop').css('top')) + jQuery('.crop').height() > jQuery('.resize-square').height())
					jQuery('.crop').css('top' , jQuery('.resize-square').height() - jQuery('.crop').height());
			}
		} else if(itemb){
			tops = event.pageY - jQuery('.resize-square').offset().top;
			rights = jQuery('.resize-square').offset().left + jQuery('.resize-square').width() -  event.pageX;
			bottoms = jQuery('.resize-square').offset().top + jQuery('.resize-square').height() -  event.pageY;
			lefts = event.pageX - jQuery('.resize-square').offset().left;

			if(tops >= 0 && rights >= 0 && bottoms >= 0 && lefts >= 0){
				wid = parseInt(jQuery('.crop').width());
				hei = parseInt(jQuery('.crop').height());
				leftc = parseInt(jQuery('.crop').css('left'));
				topc = parseInt(jQuery('.crop').css('top'));

				so = itemb.attr('so');

				if(			so == 'rt' && 
							event.pageX > jQuery('.crop-item.lt').offset().left + 40 && 
							event.pageY < jQuery('.crop-item.rb').offset().top - 40){

					jQuery('.crop').width(parseInt(event.pageX - jQuery('.crop').offset().left));
					jQuery('.crop').height(hei - tops + topc);
					jQuery('.crop').css('top' , tops);
				} else if(	so == 'rb' && 
							event.pageX > jQuery('.crop-item.lt').offset().left + 40 && 
							event.pageY > jQuery('.crop-item.rt').offset().top + 40){

					jQuery('.crop').width(parseInt(event.pageX - jQuery('.crop').offset().left));
					jQuery('.crop').height(tops - topc);
				} else if(	so == 'lt' && 
							event.pageX < jQuery('.crop-item.rt').offset().left - 40 && 
							event.pageY < jQuery('.crop-item.lb').offset().top - 40){

					jQuery('.crop').width(wid + jQuery('.crop').offset().left - event.pageX);
					jQuery('.crop').css('left' , lefts);
					jQuery('.crop').height(hei - tops + topc);
					jQuery('.crop').css('top' , tops);
				} else if(	so == 'lb' && 
							event.pageX < jQuery('.crop-item.rt').offset().left - 40 && 
							event.pageY > jQuery('.crop-item.lt').offset().top + 40){

					jQuery('.crop').width(wid + jQuery('.crop').offset().left - event.pageX);
					jQuery('.crop').css('left' , lefts);
					jQuery('.crop').height(tops - topc);
				}
			}
		}
	});

	jQuery(document).on('click' , '.save-resize' , upload_done);
	jQuery(document).on('click' , '.cancel-resize' , function(){
		jQuery('#wrapper').removeClass('blur');
		jQuery('.popup-image-resize').remove();
	});
});

function upload_start(name){
	jQuery.ajax({
		type: 'GET' , 
		url: files.attr('action') , 
		data: {
			name: name , 
			uploadper_size: upload[0].size , 
			uploadper_name: upload[0].name ,
			section: "profile"
		},
		success: function(result){
			console.log(result);
			if(result)
				result = jQuery.parseJSON(result);

			if(typeof result.message != 'undefined'){
				if(!messages.children('.messages').length)
					messages.prepend('<div class="messages xa"></div>');
				messages.children('.messages').prepend(result.message);
				clearTimeout(timeout);
				timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
			}

			if(typeof result.status != 'undefined' && result.status == true)
				upload_file(0 , name);
		}
	});
}

function upload_file(item_num , name){
	var file_data = upload[item_num];
	var form_data = new FormData();
	form_data.append('file' , file_data);

	jQuery.ajax({
		type: 'POST',
		url: files.attr('action') , 
		data: form_data , 
		contentType: false , 
		processData: false ,
		xhr: function(){
			var xhr = jQuery.ajaxSettings.xhr();

			if(xhr.upload) 
				xhr.upload.addEventListener('progress' , progress_bar , false);

			return xhr;
		},
		success: function(result){
			console.log(result);
			jQuery('.progress-in').width(0);

			if(result)
				result = jQuery.parseJSON(result);

			if(typeof result.message != 'undefined'){
				if(!messages.children('.messages').length)
					messages.prepend('<div class="messages xa"></div>');
				messages.children('.messages').prepend(result.message);
				clearTimeout(timeout);
				timeout = setTimeout(function(){jQuery('.messages').remove();} , 10000);
			}

			if(typeof result.status != 'undefined' && result.status == true)
				resize_pic(result.url);
		}
	});
}

function upload_done(){
	jQuery.ajax({
		type: 'GET' , 
		url: files.attr('action') , 
		data: {
			width: parseInt(jQuery('.popup-image-resize-in .image').width()) ,
			height: parseInt(jQuery('.popup-image-resize-in .image').height()) ,
			crop_width: parseInt(jQuery('.crop').width()) ,
			crop_height: parseInt(jQuery('.crop').height()) ,
			top: parseInt(jQuery('.crop').css('top')) ,
			left: parseInt(jQuery('.crop').css('left')) ,
		},
		success: function(result){
			console.log(result);
			jQuery('#wrapper').removeClass('blur');
			jQuery('.popup-image-resize').remove();

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
				setTimeout(function(){
					window.open(files.attr('window') , '_self');
				} , 1000);
			}
		}
	});
}

function progress_bar(e){
	wid = Math.round(e.loaded * 10000 / e.total) / 100 + '%';
	files.parent().children('.progress').children('.progress-in').width(wid);
}

function resize_pic(url)
{
	jQuery('#wrapper').addClass('blur');
	jQuery('body').append('<div class="popup-image-resize xa"></div>');
	jQuery('.popup-image-resize').append('<div class="popup-image-resize-in xa s8 m6 l4 es1 em2 el3 aes1 aem2 ael3"></div>');
	jQuery('.popup-image-resize-in').append('<img class="image " src="' + url + '">')
	jQuery('.popup-image-resize-in').append('<div class="resize-square"></div>');
	jQuery('.popup-image-resize-in').append('<div class="save-resize">' + files.attr('save') + '</div>')
	jQuery('.popup-image-resize-in').append('<div class="cancel-resize">' + files.attr('cancel') + '</div>')
	jQuery('.resize-square').append('<div class="crop"><div class="crop-item rt" so="rt"></div><div class="crop-item rb" so="rb"></div><div class="crop-item lt" so="lt"></div><div class="crop-item lb" so="lb"></div></div>');

	resized_resize_pic();
}

function resized_resize_pic()
{
	var elm = jQuery('.popup-image-resize-in');

	if(elm.length){
		elm.removeAttr('style');
		elm.children('.image').removeAttr('style');
		elm.children('.resize-square').removeAttr('style');

		wid = parseInt(jQuery(window).width());
		hei = parseInt(jQuery(window).height());

		if(wid < 768){
			elm.height(hei * 0.9);
			elm.css('margin-top' , hei * 0.05);
		} else if(wid < 992){
			elm.height(hei * 0.8);
			elm.css('margin-top' , hei * 0.10);
		} else if(wid < 1200){
			elm.height(hei * 0.7);
			elm.css('margin-top' , hei * 0.15);
		} else {
			elm.height(hei * 0.6);
			elm.css('margin-top' , hei * 0.2);
		}

		elm.children('.image').one('load' , function() {
			if(this.complete){
				pin_wid = Number(elm.width());
				pin_wid_image = Number(jQuery(this).width());

				pin_hei = Number(elm.height());
				pin_hei_image = Number(jQuery(this).height());

				if(pin_wid > pin_wid_image)
					jQuery(this).css('margin-right' , parseInt((pin_wid - pin_wid_image) / 2));

				if(pin_hei > pin_hei_image)
					jQuery(this).css('margin-top' , parseInt((pin_hei - pin_hei_image) / 2));

				elm.children('.resize-square').width(parseInt(jQuery(this).width()));
				elm.children('.resize-square').height(parseInt(jQuery(this).height()));
				elm.children('.resize-square').css('top' , parseInt(jQuery(this).css('margin-top')));
				elm.children('.resize-square').css('right' , parseInt(jQuery(this).css('margin-right')));

				if(parseInt(jQuery(this).width()) > parseInt(jQuery(this).height())){
					jQuery('.crop').width((jQuery(this).height() / jQuery(this).width() * 100) + '%');
					jQuery('.crop').height('100%');
				} else {
					jQuery('.crop').width('100%');
					jQuery('.crop').height((jQuery(this).width() / jQuery(this).height() * 100) + '%');
				}
			}
		});
	}
}