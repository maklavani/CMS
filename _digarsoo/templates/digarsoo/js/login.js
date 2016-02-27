jQuery(document).ready(function(){
	var times = setTimeout(resized , 1);

	jQuery(window).resize(function(){
		clearTimeout(times);
		times = setTimeout(resized , 50);
	});

	function resized(){
		hei = parseInt(jQuery(window).height());
		hei_wrap = parseInt(jQuery("#wrapper-in").height());

		jQuery("#wrapper-in").css('margin-top' , parseInt((hei - hei_wrap) / 2));
	}
});