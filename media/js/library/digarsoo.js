/*
	Version : 2.0.0;
*/

jQuery(document).ready(function(e) {
	jQuery('.gradient').each(function(){
		colora = jQuery(this).attr('colora');
		colorb = jQuery(this).attr('colorb');
		model = jQuery(this).attr('model');
		type = 'linear';

		if(model == 'horizontal'){
			firefox = 'left';
			chrome = 'linear , left top , right top';
			w3c = 'to right';
		} else if(model == 'vertical'){
			firefox = 'top';
			chrome = 'linear , left top , left bottom';
			w3c = 'to bottom';
		} else if(model == 'radial'){
			firefox = 'center , ellipse cover';
			chrome = 'radial , center center , 0px , center center , 100%';
			w3c = 'ellipse at center';
			type = 'radial';
		} else {
			firefox = model + 'deg';
			chrome = 'linear , left top , right top';
			if(model[0] == '-')
				w3c = (180 + parseInt(model)) + 'deg';
			else
				w3c = model + 'deg';
		}

		jQuery(this).css({
			'background-image': '-moz-' + type + '-gradient('+ firefox + ' , ' + colora + ' 0% , ' + colorb + ' 100%)' , 
			'background-image': '-webkit-gradient(' + chrome + ' , color-stop( 0% , ' + colora + ') , color-stop(100% , ' + colorb + '))' , 
			'background-image': '-webkit-' + type + '-gradient(' + firefox + ' , ' + colora + ' 0% , ' + colorb + ' 100%)' , 
			'background-image': '-o-' + type + '-gradient(' + firefox + ' , ' + colora + ' 0% , ' + colorb + ' 100%)' , 
			'background-image': '-ms-' + type + '-gradient(' + firefox + ' , ' + colora + ' 0% , ' + colorb + ' 100%)' , 
			'background-image':  type + '-gradient(' + w3c + ' , ' + colora + ' 0% , ' + colorb + ' 100%)' , 
			'filter': 'progid:DXImageTransform.Microsoft.gradient(startColorstr = "' + colora + '" , endColorstr="' + colorb + '" , GradientType=0)'
		});
	});

	jQuery('.radius').each(function(){
		radius = jQuery(this).attr('radius');
		jQuery(this).css({
			'-webkit-border-radius': radius + 'px' ,
			'-moz-border-radius': radius + 'px' ,
			'border-radius': radius + 'px'
		});
	});

	jQuery(document).on('click' , '.messages .icon-close' , function(){
		jQuery(this).parent().remove();
	});

	jQuery('.messages').each(function(){
		messages = jQuery(this);
		if(typeof messages.attr('times') == 'undefined')
		{
			index = jQuery(this).index();
			messages.attr('times' , index);

			setTimeout(function(){
				jQuery('.messages[times="' + index + '"]').remove().trigger('remove');
			} , 60000);
		}
	});
});