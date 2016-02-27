jQuery(document).ready(function(){
	jQuery('.tel .input-hidden-tel').each(function(){
		val = jQuery(this).val();

		if(val != ""){
			tel = val.split(":");
			if(typeof tel[0] != 'undefined' && typeof tel[1] != 'undefined'){
				jQuery(this).parent().children('.tel-area-code').val(tel[0]).trigger("change");
				jQuery(this).parent().children('.tel-code').val(tel[1]);
			}
		}
		else
		{
			jQuery(this).parent().children('.tel-area-code').val(98).trigger("change");
			jQuery(this).val('98:');
		}
	});

	jQuery(document).on('change' , '.tel-area-code' , function(){
		tel = jQuery(this).val() + ':' + jQuery(this).parent().children('.tel-code').val();
		jQuery(this).parent().children('.input-hidden-tel').val(tel);
	});

	jQuery(document).on('keyup' , '.tel-code' , function(){
		tel = jQuery(this).parent().children('.tel-area-code').val() + ':' +  jQuery(this).val();
		jQuery(this).parent().children('.input-hidden-tel').val(tel);
	});
});