jQuery(document).ready(function(){
	jQuery(document).on('mouseenter' , '.visit-graph-circle' , function(){
		jQuery('#graph-hover').addClass('showing');
		jQuery('#graph-hover-views').text(jQuery(this).attr('views').toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g , "$1,"));
		jQuery('#graph-hover-dates').text(jQuery(this).attr('dates'));

		vgw = parseInt(jQuery('#visit-graph').width()) / parseInt(jQuery('#visit-graph').attr('width'));
		wid = parseInt(parseInt(jQuery(this)[0].r.animVal.value) * vgw);
		left_pos = parseInt(jQuery(this).offset().left) - 60 + parseInt(wid);
		top_pos = parseInt(jQuery(this).offset().top) - parseInt(jQuery(document).scrollTop()) - 60;

		if(top_pos < 0)
		{
			top_pos = jQuery(this).offset().top;
			jQuery('#graph-hover').addClass('top');
		}

		jQuery('#graph-hover').css({
			left: left_pos , 
			top: top_pos
		});
	});

	jQuery(document).on('mouseout' , '.visit-graph-circle' , function(){
		jQuery('#graph-hover').removeClass('showing top');
	});
});