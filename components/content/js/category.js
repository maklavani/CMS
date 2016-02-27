jQuery(document).ready(function(){
	jQuery(document).on('click' , '.pagination-item[page]' , function(){
		url = window.location.href.replace(/\?&?page=.+/ , "");
		window.open(url + (jQuery(this).attr('page') > 1 ? '?page=' + jQuery(this).attr('page') : "") , "_self");
	});
});