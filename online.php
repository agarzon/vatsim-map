<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Refresh" content="300" />
<style type="text/css">
.Gbox {
	color: #000000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	text-align: left;
	margin: 2px auto 2px auto;
}
</style>
<script type="text/javascript">
		//<![CDATA[

		function load() {
			if (GBrowserIsCompatible()) {
			// Hace el mapa
			var map = new GMap2(document.getElementById("map"));

			// Agrega controles
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			//map.addControl(new GOverviewMapControl());
			//map.addControl(new GSmallZoomControl());
			map.addControl(new GScaleControl());

			// Centra el mapa
			map.setCenter(new GLatLng(7.4673, -67.0235), 5, G_HYBRID_MAP);


			// Crea un icono
			var icon = new GIcon();
			icon.image = "img/icon/airplane_ico.png";
			icon.iconSize = new GSize(16, 16);
			icon.iconAnchor = new GPoint(8, 0);
			icon.infoWindowAnchor = new GPoint(8, 0);

			// Funcion para crear las cajas de informacion
			function createMarker(point, icon, info) {
				var marker = new GMarker(point, icon);
				GEvent.addListener(marker, "click", function() {
					marker.openInfoWindowHtml(info);
				});
				return marker;
			}


			<?php
			$fetch_data = false;
			//More: http://status.vatsim.net/status.txt
			$data_file = fopen('http://data.vattastic.com/vatsim-data.txt','r');

			while($line = (fgets($data_file))) {

				if (substr($line,0,1) == ';') {
					continue;
				} elseif (substr($line,0,1) == '!' && trim($line) == '!CLIENTS:') {
					$fetch_data = true;
				} elseif (substr($line,0,1) == '!' && trim($line) != '!CLIENTS:'){
					$fetch_data = false;
				} elseif ($fetch_data) {
					$record = explode(':',$line);

					if (($record[3] == 'PILOT') && (eregi("TCA", $record[0]))){


						$callsign					= $record[0];
						$cid						= $record[1];
						$realname					= $record[2];
						$clienttype					= $record[3];
						$frequency					= $record[4];
						$latitude					= $record[5];
						$longitude					= $record[6];
						$altitude					= $record[7];
						$groundspeed				= $record[8];
						$planned_aircraft			= $record[9];
						$planned_tascruise			= $record[10];
						$planned_depairport			= $record[11];
						$planned_altitude			= $record[12];
						$planned_destairport		= $record[13];
						$server						= $record[14];
						$protrevision				= $record[15];
						$rating						= $record[16];
						$transponder				= $record[17];
						$facilitytype				= $record[18];
						$visualrange				= $record[19];
						$planned_revision			= $record[20];
						$planned_flighttype			= $record[21];
						$planned_deptime			= $record[22];
						$planned_actdeptime			= $record[23];
						$planned_hrsenroute			= $record[24];
						$planned_minenroute			= $record[25];
						$planned_hrsfuel			= $record[26];
						$planned_minfuel			= $record[27];
						$planned_altairport			= $record[28];
						$planned_remarks			= $record[29];
						$planned_route				= $record[30];
						$planned_depairport_lat		= $record[31];
						$planned_depairport_lon		= $record[32];
						$planned_destairport_lat	= $record[33];
						$planned_destairport_lon	= $record[34];
						$atis_message				= $record[35];
						$time_last_atis_received	= $record[36];
						$time_logon					= $record[37];
						$heading					= $record[38];
						$QNH_iHg					= $record[39];
						$QNH_Mb						= $record[40];


						$info  = "<div class=Gbox>";
						$info .= "<div><img src=img/icon/tca-small.png /></div>";
						$info .= "<div>Callsign: <b>$callsign</b></div>";
						$info .= "<div>Capitán: $realname</div>";
						$info .= "<div>Equipo: $planned_aircraft</div>";
						$info .= "<div>Salida: $planned_depairport</div>";
						$info .= "<div>Destino: $planned_destairport</div>";
						$info .= "<div>Nivel Crucero: $planned_altitude</div>";
						$info .= "<div>Altitud actual: $altitude</div>";
						$info .= "<div>Velocidad: $groundspeed nudos</div>";
						$info .= "<div>Rumbo: $heading º</div>";
						$info .= "</div>";
						?>
						var point = new GLatLng(<? echo $latitude ?>, <? echo $longitude ?>);
						map.addOverlay(createMarker(point, icon, "<? echo $info ?>"));

						<?php
					};
				};
			};
			?>

		}
	}

	//]]>
	</script>
<script
	src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAkrFq91eBVl81uBFHowSrgBRer6vA_ihHlOsMKKeTCM0sxWSVvRRepGe0jQywFPFwxf0y8dzcyYw3-A"
	type="text/javascript"></script>
</head>
<body onload="load()" onunload="GUnload()">
	<h1>Pilotos conectados a la red VATSIM</h1>
	<div id="map" style="width: 650px; height: 450px"></div>
</body>
</html>
