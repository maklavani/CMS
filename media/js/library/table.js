jQuery(window).load(function(){
	var inter = false;
	append_space();
	resized();

	jQuery(window).resize(resized);

	jQuery(document).bind("DOMNodeInserted" , function(e){
		clearTimeout(inter);
		inter = setTimeout(function(){
			append_space();
			resized();
		} , 5);
	});

	function resized(){
		jQuery('.space-outer').each(function(){
			margin = parseInt(jQuery(this).parent().children('.space').css('margin-right')) + parseInt(jQuery(this).parent().children('.space').css('margin-left'));
		});
	}

	function append_space(){
		jQuery('.level').each(function(){
			if(!jQuery(this).children('.space').length){
				html = jQuery(this).html();
				jQuery(this).html('<div class="space">&nbsp;</div><div class="space-outer">' + html + '</div>');
			}
		});
	}
});