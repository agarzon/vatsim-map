<?php
require_once('FirePHPCore/fb.php');
class Vatsim {

	public $data = array();
	public $icao = '';

	public function __construct() {
		$this->data = $this->getClients();
	}

	/**
	 * Parse vatsim data taked from http://status.vatsim.net/status.txt servers
	 * @return array $data clients connected
	 */
	public function getClients() {
		$servers = array(
			'http://www.pcflyer.net/DataFeed/vatsim-data.txt',
			'http://www.klain.net/sidata/vatsim-data.txt',
			'http://fsproshop.com/servinfo/vatsim-data.txt',
			'http://info.vroute.net/vatsim-data.txt',
			'http://data.vattastic.com/vatsim-data.txt',
			);

		uksort($servers, function() { return rand() > rand(); });

		$file    = file($servers[0]);
		$allowed = false;
		$data    = array();

		foreach ($file as $ifile) {
			if (substr($ifile, 0, 1) != ";") {
				$ifile = utf8_decode(rtrim($ifile));
				if ($allowed == true && substr($ifile, 0, 1) != "!") {
					$data[] = self::_parseAssociative(explode(":", $ifile));
				} else {
					$allowed = false;
					if ($ifile == "!CLIENTS:") {
						$allowed = true;
					}
				}
			}
		}

		return $data;
	}

	private static function _parseAssociative($array) {
		$keys = array(
			'callsign', 'cid', 'realname', 'clienttype', 'frequency', 'latitude', 'longitude', 
			'altitude', 'groundspeed', 'planned_aircraft', 'planned_tascruise', 'planned_depairport', 
			'planned_altitude', 'planned_destairport', 'server', 'protrevision', 'rating', 'transponder', 
			'facilitytype', 'visualrange', 'planned_revision', 'planned_flighttype', 'planned_deptime', 
			'planned_actdeptime', 'planned_hrsenroute', 'planned_minenroute', 'planned_hrsfuel', 
			'planned_minfuel', 'planned_altairport', 'planned_remarks', 'planned_route', 
			'planned_depairport_lat', 'planned_depairport_lon', 'planned_destairport_lat', 
			'planned_destairport_lon', 'atis_message', 'time_last_atis_received', 'time_logon', 
			'heading', 'QNH_iHg', 'QNH_Mb', 'wtf'
			);

		return array_combine($keys, $array);
	}

	public function showType($type = 'PILOT') {
		if ($type === 'PILOT') {
			return array_filter($this->data, array($this, '_filterByPilot'));
		}

		return array_filter($this->data, array($this, '_filterByAtc'));
	}

	public function showByAirline($icao = 'TCA') {
		$this->icao = $icao;
		return array_filter($this->data, array($this, '_filterByAirline'));
	}

	private function _filterByPilot($data) {
		return $data['clienttype'] == 'PILOT';
	}

	private function _filterByAtc($data) {
		return $data['clienttype'] == 'ATC';
	}

	private function _filterByAirline($data) {
		return (!preg_match("/^$this->icao/", $data['callsign']) === false);
	}
}
