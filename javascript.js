function initialize() {
	var mapOptions = {
		center: new google.maps.LatLng(7.4673, -67.0235),
		zoom: 5,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: false,
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"),
		mapOptions);

	var weatherLayer = new google.maps.weather.WeatherLayer({
		temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS
	});
	weatherLayer.setMap(map);

	var cloudLayer = new google.maps.weather.CloudLayer();
	cloudLayer.setMap(map);

	var jsonMarkers = (function () {
		var jsonMarkers = null;
		$.ajax({
			'async': false,
			'global': false,
			'url': 'json.php',
			'dataType': "json",
			'success': function (data) {
				jsonMarkers = data;
			}
		});
		return jsonMarkers;
	})(); 

	console.log(jsonMarkers);

	for (index in jsonMarkers) addMarker(jsonMarkers[index]);

		function addMarker(data) {
		// Create the marker
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(data.latitude, data.longitude),
			map: map,
			title: data.callsign,
			icon: 'red_dot.png'
		});

		var contentString = '<div style="font-size: 8pt;">'+
		'<h3>'+ data.callsign +'</h3>' +
		'<b>Capit&#225;n: </b>' + data.realname + '<br>' +
		'<b>Vuelo: </b>' + data.planned_depairport + ' -> ' + data.planned_destairport + '<br>' +
		'<b>Equipo: </b>' + data.planned_aircraft + '<br>' +
		'<b>Velocidad: </b>' + data.groundspeed + 'knots' + '<br>' +
		'<b>Altitud: </b>' + data.altitude + 'ft.' + '<br>' +
		'</div>';

		var infowindow = new google.maps.InfoWindow({
			content: contentString,
			maxWidth: 350
		});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	}

}

google.maps.event.addDomListener(window, 'load', initialize);