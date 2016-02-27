jQuery(document).ready(function(){
	var times , elm;
	jQuery('.price').each(function(){
		jQuery(".price-field[price=\"" + jQuery(this).attr('name') + "\"]").val(tostr(jQuery(this).val()));
	});

	jQuery(document).on('keyup' , '.price-field' , function(){
		vals = Number(jQuery(this).val().replace(/[^0-9\.]+/g , ""));
		jQuery(this).val(tostr(vals));
		jQuery("[name=\"" + jQuery(this).attr('price') + "\"]").val(vals);

		elm = jQuery(this);
		clearTimeout(times);
		times = setTimeout(function(){
			elm.trigger('change');
			jQuery("[name=\"" + elm.attr('price') + "\"]").trigger('change');
		} , 300);
	});

	function tostr(str){
		return str.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g , "$1,");
	}
});