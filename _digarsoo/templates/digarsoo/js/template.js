jQuery(document).ready(function(){
	var menu = jQuery.cookie('menu');

	if(typeof menu != 'undifined' && menu == 'showing'){
		jQuery('#menu-icon').addClass('showing');
		jQuery('#menu').addClass('showing');
	}

	resized();

	jQuery(window).resize(resized);

	jQuery('#menu-icon').click(function(){
		if(jQuery(this).hasClass('showing'))
			jQuery.removeCookie('menu' , {path: '/'});
		else
			jQuery.cookie('menu' , 'showing' , {path: '/' , expires: 30});

		jQuery('#menu-icon').toggleClass('showing');
		jQuery('#menu').toggleClass('showing');

		resized();
	});

	function resized(){
		jQuery('#menu').width(0);
		jQuery('#menu-icon-parent').width(0);

		wid = parseInt(jQuery(window).width());
		hei = parseInt(jQuery(window).height());

		if(wid < 992){
			jQuery('#wrapper').removeAttr('style');

			jQuery('#menu').removeAttr('style');
			jQuery('#menu-icon-parent').removeAttr('style');
			jQuery('#content').removeAttr('style');
			jQuery('#header-content').removeAttr('style');

			jQuery('#menu').addClass('xa');
			jQuery('#menu-icon-parent').addClass('xa');
		} else {
			jQuery('#menu').removeClass('xa');

			if(jQuery('#menu').hasClass('showing')){
				jQuery('#menu').width(300);
				jQuery('#menu-icon-parent').width(300);

				if(jQuery('html[dir="rtl"]').length){
					jQuery('#header-content').css('padding-right' , 301);
					jQuery('#content').css('padding-right' , 301)
				} else {
					jQuery('#header-content').css('padding-left' , 301);
					jQuery('#content').css('padding-left' , 301)
				}
			} else {
				jQuery('#menu').width(50);
				jQuery('#menu-icon-parent').width(50);

				if(jQuery('html[dir="rtl"]').length){
					jQuery('#header-content').css('padding-right' , 51);
					jQuery('#content').css('padding-right' , 51)
				} else {
					jQuery('#header-content').css('padding-left' , 51);
					jQuery('#content').css('padding-left' , 51)
				}

				parent = jQuery('#menu .menu .parent');
				parent.children('ul').removeClass('showing');
				parent.children('a').removeClass('showing-parent');
				parent.children('ul').removeAttr('style');
			}

			jQuery('#wrapper').height(hei);
			jQuery('#menu').height(hei - 80);
			jQuery('#content').height(hei - 80);
		}
	}

	jQuery('#menu .menu .parent').click(function(e){
		if(	e.target === jQuery(this).children('a').children('.link-text')[0] || 
			e.target === jQuery(this).children('a').children('.icon-text')[0] || 
			e.target === jQuery(this).children('a')[0] && jQuery('#menu').hasClass('showing'))

			if(jQuery(this).children('ul').hasClass('showing')){
				jQuery(this).children('ul').removeClass('showing');
				jQuery(this).children('a').removeClass('showing-parent');
				jQuery(this).children('ul').animate({height : 0} , 300);

				parent = jQuery(this).parent().parent();
					if(parent.hasClass('level-1'))
						parent.children('ul').animate({height : parent.children('ul').children('li').size() * 30} , 300);
			} else {
				jQuery(this).parent().find('.parent').children('ul.showing').animate({height : 0} , 300);
				jQuery(this).parent().find('.parent').children('.showing').removeClass('showing');
				jQuery(this).parent().find('.parent').children('.showing-parent').removeClass('showing-parent');

				jQuery(this).children('ul').addClass('showing');
				jQuery(this).children('a').addClass('showing-parent');

				if(jQuery(window).width() > 992){
					jQuery(this).children('ul').height(0);
					li_size = jQuery(this).children('ul').children('li').size();

					parent = jQuery(this).parent().parent();
					if(parent.hasClass('level-1'))
						parent.children('ul').animate({height : (parent.children('ul').children('li').size() + li_size) * 30} , 300);

					jQuery(this).children('ul').animate({height : li_size * 30} , 300);
				} else {
					jQuery(this).children('ul').height(0);
					li_size = jQuery(this).children('ul').children('li').size();

					parent = jQuery(this).parent().parent();
					if(parent.hasClass('level-1'))
						parent.children('ul').animate({height : (parent.children('ul').children('li').size() + li_size) * 24} , 300);

					jQuery(this).children('ul').animate({height : li_size * 24} , 300);
				}
			}
	});

	jQuery('#menu > .menu > .parent').hover(function(e){
		if(!jQuery('#menu').hasClass('showing')){
			whei = parseInt(jQuery(window).height());
			tops = jQuery(this).offset().top;
			chei = parseInt(jQuery(this).children('ul').children('li').size()) * 30 + 50;

			if(tops + chei > whei - 30){
				jQuery(this).children('a').children('.link-text').css('margin-top' , -1 * (tops + chei - whei + 42));
				jQuery(this).children('ul').css('margin-top' , -1 * (tops + chei - whei + 42));
			}
		}
	} , function(){
		if(!jQuery('#menu').hasClass('showing')){
			jQuery(this).children('a').children('.link-text').removeAttr('style');
			jQuery(this).children('ul').removeAttr('style');
		}
	});

	jQuery('.flag').each(function(){
		cls = jQuery(this).attr('class').split('flag ');
		cls = cls[1].split(' ');

		for(i in cls){
			jQuery(this).append('<div class="flag" flag="' + cls[i] + '"></div>');
			jQuery(this).removeClass(cls[i]);
		}

		jQuery(this).removeClass('flag');
	});

	jQuery('.status-line').each(function(){
		val = jQuery(this).attr('val');
		all = jQuery(this).attr('all');

		if(all > 0)
			str = ((val / all) * 90) + '%';
		else
			str = 0 + '%';

		jQuery(this).append('<div class="status-shadow x9 ex05 aex05"></div>');
		jQuery(this).append('<div class="status-value"></div>');
		jQuery(this).children('.status-value').width(str);
	});

	jQuery('.status-item').hover(function(){
		val = jQuery(this).children('.status-line').attr('val');
		all = jQuery(this).children('.status-line').attr('all');

		if(all > 0)
			str = (Math.round(val * 10000 / all) / 100) + '%';
		else
			str = 0 + '%';

		if(typeof jQuery(this).children('.status-line').attr('val-show') != 'undefined' && typeof jQuery(this).children('.status-line').attr('all-show') != 'undefined')
			str_desc = jQuery(this).children('.status-line').attr('val-show') + ' / ' + jQuery(this).children('.status-line').attr('all-show');
		else
			str_desc = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g , "$1,") + ' / ' + all.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g , "$1,");

		jQuery('#graph-hover').addClass('showing');
		jQuery('#graph-hover-views').text(str);
		jQuery('#graph-hover-dates').text(str_desc);

		wid = parseInt(jQuery(this).width());
		left_pos = parseInt(jQuery(this).offset().left) - 60 + parseInt(wid / 2);
		top_pos = parseInt(jQuery(this).offset().top) - parseInt(jQuery(document).scrollTop()) - 20;

		if(top_pos < 0)
		{
			top_pos = jQuery(this).offset().top;
			jQuery('#graph-hover').addClass('top');
		}

		jQuery('#graph-hover').css({
			left: left_pos , 
			top: top_pos
		});
	} , function(){
		jQuery('#graph-hover').removeClass('showing top');
	});
});