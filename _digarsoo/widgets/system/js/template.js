jQuery(document).ready(function(){
	var inter , interb;
	var cpu = [];
	var num = 20;

	for (i = 0;i < num;i++)
		cpu[i] = 0;

	get_speed();
	get_datetime();

	inter = setInterval(get_speed , 1000);
	interb = setInterval(get_datetime , 1000);

	function get_speed(){
		jQuery.ajax({
			type: "GET",
			url: jQuery('#system').attr('ajax') ,
			success: function(result){
				jQuery('.system-percentage').text(result + '%');
				cpu.shift();
				cpu[num - 1] = result;
				create_graph();
			}
		});
	}

	function get_datetime(){
		jQuery.ajax({
			type: "GET",
			url: jQuery('.system-datetime').attr('ajax') ,
			success: function(result){
				jQuery('.system-datetime').text(result);
			}
		});	
	}

	function create_graph(){
		jQuery('#system-graph').empty();

		for (i = 0;i < num + 1;i++){
			x = parseInt(i * (1000 / num));
			line = makeSVG('line' , {class: 'line-vertical-system' , x1: x , x2: x , y1: 0 , y2: 200});
			document.getElementById('system-graph').appendChild(line);
		}

		for (i = 0;i < 5;i++){
			y = i * 40;
			line = makeSVG('line' , {class: 'line-horizontal-system' , x1: 0 , x2: 1000 , y1: y , y2: y});
			document.getElementById('system-graph').appendChild(line);
		}

		points_cpu = '0 , 200 ';
		for (i = 0;i < num;i++){
			x = parseInt(i * (1000 / num));
			y = (200 - parseInt(cpu[i] * 2));
			points_cpu += ' ' + x + ' , ' + y;
		}

		points_cpu += ' 1000 , 200 0 , 200';

		polygon = makeSVG('polygon' , {class: 'polygon-system' , points: points_cpu});
		document.getElementById('system-graph').appendChild(polygon);
	}

	function makeSVG(tag , attrs) {
		var el = document.createElementNS('http://www.w3.org/2000/svg' , tag);
		for (var k in attrs)
			el.setAttribute(k, attrs[k]);
		return el;
	}
});