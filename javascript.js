$(document).ready(function(){
	var map = new GMaps({
		div: '#map',
		lat: 7.4673,
		lng: -67.0235,
		zoom: 5
	});

	// Add Layers
	map.addLayer("weather");
	map.addLayer("clouds");

	// Get JSON data
	var markers = null;
	$.ajax({
		'async': false,
		'global': false,
		'url': 'json.php',
		'dataType': "json",
		'success': function (data) {
			markers = data;
		}
	});
	console.log(markers);

	for (index in markers) addMarker(markers[index]);

	// Custom function to add markers
	function addMarker(data) {

		var contentString = '<div style="font-size: 8pt;">'+
		'<h3>'+ data.callsign +'</h3>' +
		'<b>Capit&#225;n: </b>' + data.realname + '<br>' +
		'<b>Vuelo: </b>' + data.planned_depairport + ' -> ' + data.planned_destairport + '<br>' +
		'<b>Equipo: </b>' + data.planned_aircraft + '<br>' +
		'<b>Velocidad: </b>' + data.groundspeed + 'knots' + '<br>' +
		'<b>Altitud: </b>' + data.altitude + 'ft.' + '<br>' +
		'</div>';

		map.addMarker({
			lat: data.latitude,
			lng: data.longitude,
			title: data.callsign,
			icon: "red_dot.png",
			infoWindow: {
				content: contentString,
				maxWidth: 350
			}
		});
	}

});
