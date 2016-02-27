jQuery(document).ready(function(){
	jQuery('.date').each(function(){
		inp = jQuery(jQuery(this).attr('inp'));
		min_year = inp.attr('min_year');
		max_year = inp.attr('max_year');

		year = parseInt(inp.attr('year'));
		month = parseInt(inp.attr('month'));
		day = parseInt(inp.attr('day'));

		leap_month = inp.attr('leap_month');
		leap_year = jQuery.parseJSON(inp.attr('leap_year'));

		jQuery(this).attr('year' , year);
		jQuery(this).attr('month' , month);
		jQuery(this).attr('day' , day);

		jQuery(this).children('.date-item').each(function(){
			jQuery(this).append("<div class=\"date-btn-up date-btn icon-arrow-up\"></div><div class=\"date-item-in\"></div><div class=\"date-btn-down date-btn icon-arrow-down\"></div>");
		});

		jQuery(this).children('.date-year').children('.date-item-in').append("<div class=\"date-item-all\"></div>");
		jQuery(this).children('.date-month').children('.date-item-in').append("<div class=\"date-item-all\"></div>");
		jQuery(this).children('.date-day').children('.date-item-in').append("<div class=\"date-item-all\"></div>");

		jQuery(this).children('.date-year').attr('def' , year);
		jQuery(this).children('.date-month').attr('def' , month);
		jQuery(this).children('.date-day').attr('def' , day);

		//year
		for(i = min_year;i <= max_year;i++)
			jQuery(this).children('.date-year').children('.date-item-in').children('.date-item-all').append("<div class=\"date-item-clk\" val=\"" + i + "\">" + i + "</div>");
		tops = (year - min_year) * 50 * -1;
		jQuery(this).children('.date-year').children('.date-item-in').children('.date-item-all').css('top' , tops);

		// month
		for(i = 1;i <= 12;i++)
			jQuery(this).children('.date-month').children('.date-item-in').children('.date-item-all').append("<div class=\"date-item-clk\" val=\"" + i + "\">" + i + "</div>");
		tops = (month - 1) * 50 * -1;
		jQuery(this).children('.date-month').children('.date-item-in').children('.date-item-all').css('top' , tops);

		// day
		month_day = jQuery.parseJSON(inp.attr('month_day'));
		days = month_day[month];
		if(jQuery.inArray(year , leap_year) !== -1 && month == leap_month)
			days++;
		for(i = 1;i <= days;i++)
			jQuery(this).children('.date-day').children('.date-item-in').children('.date-item-all').append("<div class=\"date-item-clk\" val=\"" + i + "\">" + i + "</div>");
		tops = (day - 1) * 50 * -1;
		jQuery(this).children('.date-day').children('.date-item-in').children('.date-item-all').css('top' , tops);

		hidden_clk(jQuery(this).children('.date-year'));
		hidden_clk(jQuery(this).children('.date-month'));
		hidden_clk(jQuery(this).children('.date-day'));
	});


	jQuery(document).on('click' , '.date-btn-up' , function(){
		up_clk(jQuery(this));
		update_value();
	});

	jQuery(document).on('click' , '.date-btn-down' , function(){
		down_clk(jQuery(this));
		update_value();
	});

	jQuery(document).on('mousewheel' , '.date-item' , function(event){
		elm = jQuery(this);

		if(event.deltaFactor > 1){
			if(event.deltaY >= 1)
				up_clk(elm.children('.date-btn-up'));
			else if(event.deltaY <= -1)
				down_clk(elm.children('.date-btn-down'));

			update_value();
		}

		event.preventDefault();
	});

	function up_clk(elm){
		def = elm.parent().attr('def');
		def_elm = elm.parent().children('.date-item-in').children('.date-item-all').children("[val=\"" + def + "\"]");
		inx = def_elm.index();

		if(inx > 0){
			tops = def_elm.prev().index() * 50 * -1;
			elm.parent().children('.date-item-in').children('.date-item-all').css('top' , tops);
			elm.parent().attr('def' , def_elm.prev().attr('val'));

			if(elm.parent().hasClass('date-month') || elm.parent().hasClass('date-year'))
				month_reset(elm.parent().parent());
		}

		hidden_clk(elm.parent());
	}

	function down_clk(elm){
		def = elm.parent().attr('def');
		def_elm = elm.parent().children('.date-item-in').children('.date-item-all').children("[val=\"" + def + "\"]");
		inx = def_elm.index();

		if(inx + 1 < elm.parent().children('.date-item-in').children('.date-item-all').children('.date-item-clk').length){
			tops = def_elm.next().index() * 50 * -1;
			elm.parent().children('.date-item-in').children('.date-item-all').css('top' , tops);
			elm.parent().attr('def' , def_elm.next().attr('val'));

			if(elm.parent().hasClass('date-month') || elm.parent().hasClass('date-year'))
				month_reset(elm.parent().parent());
		}

		hidden_clk(elm.parent());
	}

	function hidden_clk(elm){
		def = elm.attr('def');
		def_elm = elm.children('.date-item-in').children('.date-item-all').children("[val=\"" + def + "\"]");
		inx = def_elm.index();

		elm.children('.date-btn-up').removeClass('hidding');
		elm.children('.date-btn-down').removeClass('hidding');

		if(inx == 0)
			elm.children('.date-btn-up').addClass('hidding');
		else if(inx + 1 == elm.children('.date-item-in').children('.date-item-all').children('.date-item-clk').length)
			elm.children('.date-btn-down').addClass('hidding');
	}

	function month_reset(elm){
		inp = jQuery(elm.attr('inp'));
		year = parseInt(elm.children('.date-year').attr('def'));
		month = parseInt(elm.children('.date-month').attr('def'));
		leap_month = parseInt(inp.attr('leap_month'));
		leap_year = jQuery.parseJSON(inp.attr('leap_year'));

		day = 1;
		elm.children('.date-day').attr('def' , day);
		elm.children('.date-day').children('.date-item-in').children('.date-item-all').empty();
		month_day = jQuery.parseJSON(inp.attr('month_day'));

		days = month_day[month];
		if(jQuery.inArray(year , leap_year) !== -1 && month == leap_month)
			days++;

		for(i = 1;i <= days;i++)
			elm.children('.date-day').children('.date-item-in').children('.date-item-all').append("<div class=\"date-item-clk\" val=\"" + i + "\">" + i + "</div>");
		tops = (day - 1) * 50 * -1;
		elm.children('.date-day').children('.date-item-in').children('.date-item-all').css('top' , tops);

		hidden_clk(elm.children('.date-month'));
		hidden_clk(elm.children('.date-day'));
	}

	function update_value()
	{
		jQuery('.date').each(function(){
			elm = jQuery(this);

			year = parseInt(elm.children('.date-year').attr('def'));
			month = parseInt(elm.children('.date-month').attr('def'));
			day = parseInt(elm.children('.date-day').attr('def'));

			inp = jQuery(elm.attr('inp'));
			inp.val(year + "-" + month + "-" + day);
		});
	}
});