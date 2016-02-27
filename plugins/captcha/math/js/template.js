jQuery(document).ready(function(){
	jQuery(document).on('click' , '.captcha-button' , function(){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.captcha').attr('ajax') ,
			data: {
				"plugins": "captcha"
			},
			success: function(result){
				elm = jQuery('.captcha').parent();
				jQuery('.captcha').remove();
				elm.prepend(result);
			}
		});
	});
});