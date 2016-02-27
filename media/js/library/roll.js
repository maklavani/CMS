(function($){
	var min = 0;
	var max = 100;
	var element = false;
	var text = false;
	var min_input = false;
	var max_input = false;

	$.fn.roll = function(arr_input){
		this.empty();
		init();

		if(typeof arr_input != "undefined"){
			if(typeof arr_input.min != "undefined")
				min = arr_input.min;

			if(typeof arr_input.max != "undefined")
				max = arr_input.max;

			if(typeof arr_input.text != "undefined")
				text = arr_input.text;

			if(typeof arr_input.min_input != "undefined")
				min_input = arr_input.min_input;

			if(typeof arr_input.max_input != "undefined")
				max_input = arr_input.max_input;
		}

		this.append('<div class="roll xa"><div class="roll-line x9 ex05 aex05"></div><div class="roll-shadow"></div><div class="roll-control roll-control-min"></div><div class="roll-control roll-control-max"></div></div>');

		this.children('.roll').removeAttr('min_controll');
		this.children('.roll').removeAttr('max_controll');

		if(min_input && min_input.val() != "")
			this.children('.roll').attr('min_controll' , min_input.val());

		if(max_input && max_input.val() != "")
			this.children('.roll').attr('max_controll' , max_input.val());

		this.children('.roll').attr('min' , min);
		this.children('.roll').attr('max' , max);
		roll_control(this.children('.roll'));

		this.on('mousedown' , '.roll-control' , function(){
			element = jQuery(this);
			jQuery('.roll.rolling').removeClass('rolling');
			element.parent().addClass('rolling');
		});

		this.on('mousemove' , function(event){
			if(element){
				min_left = (Math.ceil((Number(element.parent().children('.roll-control-min').position().left) / Number(element.parent().width())) * 100) - 5) * 10 / 9;
				max_left = (Math.ceil((Number(element.parent().children('.roll-control-max').position().left) / Number(element.parent().width())) * 100) - 5) * 10 / 9;

				min_elem = parseInt(element.parent().attr('min'));
				max_elem = parseInt(element.parent().attr('max'));
				diff = max_elem - min_elem;

				left = (Number(event.pageX - element.parent().children('.roll-line').offset().left) / Number(element.parent().children('.roll-line').width())) * 100;

				if(element.hasClass('roll-control-min') && left < max_left && left >= 0){
					element.parent().attr('min_controll' , left * diff / 100 + min_elem);
					roll_control(element.parent());
					jQuery(this).trigger('move');
				} else if(element.hasClass('roll-control-max') && left > min_left && left <= 100){
					element.parent().attr('max_controll' , left * diff / 100 + min_elem);
					roll_control(element.parent());
					jQuery(this).trigger('move');
				}
			}
		});

		this.on('mouseup' , function(){
			if(element){
				roll_control(jQuery(this).children('.roll'));
				jQuery(this).trigger('move');
				element.parent().removeClass('rolling');
				element = false;
			}
		});

		this.on('click' , function(event){
			min_left = (Math.ceil((Number(jQuery(this).children('.roll').children('.roll-control-min').position().left) / Number(jQuery(this).children('.roll').width())) * 100) - 5) * 10 / 9;
			max_left = (Math.ceil((Number(jQuery(this).children('.roll').children('.roll-control-max').position().left) / Number(jQuery(this).children('.roll').width())) * 100) - 5) * 10 / 9;

			min_elem = parseInt(jQuery(this).children('.roll').attr('min'));
			max_elem = parseInt(jQuery(this).children('.roll').attr('max'));
			diff = max_elem - min_elem;

			left = (Number(event.pageX - jQuery(this).children('.roll').children('.roll-line').offset().left) / Number(jQuery(this).children('.roll').children('.roll-line').width())) * 100;

			if(left >= 0 && left <= 100){
				if(Math.abs(left - max_left) > Math.abs(left - min_left))
					jQuery(this).children('.roll').attr('min_controll' , left * diff / 100 + min_elem);
				else
					jQuery(this).children('.roll').attr('max_controll' , left * diff / 100 + min_elem);
				
				roll_control(jQuery(this).children('.roll'));
				jQuery(this).trigger('move');
			}
		});

		return this;
	};

	function init(){
		min = 0;
		max = 100;
		element = text = min_input = max_input = false;
	}

	function roll_control(element){
		min_elem = element.attr('min');
		max_elem = element.attr('max');
		diff = max_elem - min_elem;

		if(typeof element.attr('min_controll') != 'undefined')
			min_controll = Number(element.attr('min_controll'));
		else
			min_controll = Number(min_elem);

		if(typeof element.attr('max_controll') != "undefined")
			max_controll = Number(element.attr('max_controll'));
		else
			max_controll = Number(max_elem);

		element.children('.roll-shadow').css('left' , (5 + ((min_controll - min_elem) / diff) * 90) + '%');
		element.children('.roll-shadow').css('width' , (((max_controll - min_controll) / diff) * 90) + '%');

		element.children('.roll-control-min').css('left' , (5 + ((min_controll - min_elem) / diff) * 90) + '%');
		element.children('.roll-control-max').css('left' , (5 + ((max_controll - min_elem) / diff) * 90) + '%');

		if(text){
			element.children('.roll-text').remove();
			element.append('<div class="roll-text x9 ex05 aex05"><div class="roll-text-in roll-text-min">' + tostr(parseInt(min_controll)) + '</div><div class="roll-text-in roll-text-max">' + tostr(parseInt(max_controll)) + '</div></div>');
		}

		if(min_input)
			min_input.val(min_controll);

		if(max_input)
			max_input.val(max_controll);
	}

	function tostr(str){
		return str.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g , "$1,");
	}
}(jQuery));