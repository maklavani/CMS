jQuery(document).ready(function(){
	var vals;

	var basicCenter = new google.maps.LatLng(Number(jQuery('#map-input').attr('latitude')) , Number(jQuery('#map-input').attr('longitude')));

	var mapProp = {
		center: basicCenter ,
		zoom: Number(jQuery('#map-input').attr('zoom')) ,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var icon = 	new google.maps.MarkerImage(
				jQuery('#map-input').attr('location') + 'marker-red.png');

	var map;
	var shapes = [];

	var drawing = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.POLYGON , 
		drawingControl: true , 
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: [
				google.maps.drawing.OverlayType.MARKER , 
				google.maps.drawing.OverlayType.POLYGON
			]
		},
		polygonOptions: {
			clickable: true , 
			editable: true , 
			draggable: true , 
			fillColor: 'rgba(233 , 30 , 99 , 0.5)',
			strokeColor: 'rgba(194 , 24 , 91 , 0.9)' , 
			fillOpacity: 1 , 
			strokeWeight: 5 , 
			zIndex: 2
		},
		markerOptions: {
			clickable: true , 
			editable: true , 
			draggable: true , 
			icon: icon , 
			zIndex: 2
		}
	});

	var new_shape;

	function initialize() {
		map = new google.maps.Map(document.getElementById("map") , mapProp);

		if(jQuery('#map-input').val() != ""){
			vals = jQuery.parseJSON(jQuery('#map-input').val());

			if(typeof vals.type != 'undefined'){
				if(vals.type == 'marker'){
					icon = new google.maps.MarkerImage(jQuery('#map-input').attr('location') + 'marker-blue.png');

					marker = new google.maps.Marker({
						position: new google.maps.LatLng(vals.latitude , vals.longitude) , 
						map: map , 
						icon: icon
					});
				} else if(vals.type == 'polygon'){
					points = [];

					for(i in vals.points)
						points[i] = new google.maps.LatLng(vals.points[i].G , vals.points[i].K);

					// Construct the polygon.
					polygon = new google.maps.Polygon({
						paths: points , 
						fillColor: 'rgba(255 , 87 , 34 , 0.2)' , 
						strokeColor: 'rgba(230 , 74 , 25 , 0.3)' , 
						fillOpacity: 1 , 
						strokeWeight: 1 , 
						zIndex: 1
					});

					polygon.setMap(map);
				}
			}
		}
		
		drawing.setMap(map);

		google.maps.event.addListener(drawing , "overlaycomplete" , function(event){
			new_shape = event.overlay;
			new_shape.type = event.type;
			shapes.push(new_shape);

			if(drawing.getDrawingMode())
				drawing.setDrawingMode(null);
		});

		google.maps.event.addListener(drawing , "drawingmode_changed" , function(){
			if(this.drawingMode != null){
				jQuery('#map-input').val('');

				if(drawing.getDrawingMode() != null) {
					for (var i = 0; i < shapes.length; i++)
						shapes[i].setMap(null);

					shapes = [];
				}
			}
		});

		google.maps.event.addListener(drawing , "overlaycomplete" , function(event){
			change_vals();
			google.maps.event.addListener(event.overlay , "dragend" , change_vals);
			if(new_shape.type == 'marker')
				google.maps.event.addListener(event.overlay.getPosition() , 'insert_at' , change_vals);
			else if(new_shape.type == 'polygon')
				google.maps.event.addListener(event.overlay.getPath() , 'insert_at' , change_vals);
			// google.maps.event.addListener(event.overlay.getPath() , 'set_at' , change_vals);
		});

		google.maps.event.addListener(map , 'zoom_changed' , function(event){
			if(typeof vals.zoom != 'undefined'){
				vals.zoom = map.zoom;
				jQuery('#map-input').val(JSON.stringify(vals));
			}
		});
	}

	google.maps.event.addDomListener(window , "load" , initialize);

	function change_vals(){
		if(new_shape.type == 'marker'){
			pos = new_shape.getPosition();
			change_filed_marker(pos.G , pos.K , map.zoom);
		} else if(new_shape.type == 'polygon'){
			change_filed_polygon(new_shape.getPath().getArray() , map.zoom);
		}
	}

	function change_filed_marker(latitude , longitude , zoom){
		vals = {type: 'marker' , latitude: latitude , longitude: longitude , zoom: zoom};
		jQuery('#map-input').val(JSON.stringify(vals));
	}

	function change_filed_polygon(points , zoom){
		vals = {type: 'polygon' , points: points , zoom: zoom};
		jQuery('#map-input').val(JSON.stringify(vals));
	}
});