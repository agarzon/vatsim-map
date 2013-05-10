<?php
require_once('FirePHPCore/fb.php');
class Vatsim {

	public $data = array();

	public function __construct() {
		$this->data = $this->getClients();		
	}

	/**
	 * Parse vatsim data
	 * @return array $data connected clients
	 */
	public static function getClients() {
		$file    = file("vatsim-data.txt");//http://status.vatsim.net/status.txt
		$allowed = false;
		$data    = array();

		foreach ($file as $ifile) {
			if (substr($ifile, 0, 1) != ";") {
				$ifile = utf8_decode(rtrim($ifile));
				if ($allowed == true && substr($ifile, 0, 1) != "!") {
					$data[] = explode(":", $ifile);
				} else {
					$allowed = false;
					if ($ifile == "!CLIENTS:") {
						$allowed = true;
					}					
				}
			}
		}

		FB::log(count($data));
		return $data;
	}

	public function showAtc() {
		return array_filter($this->data, array($this, '_filterByAtc'));
	}

	private function _parseAssociative($data) {
		$keys = array(
			'callsign', 'cid', 'realname', 'clienttype', 'frequency', 'latitude', 'longitude', 
			'altitude', 'groundspeed', 'planned_aircraft', 'planned_tascruise', 'planned_depairport', 
			'planned_altitude', 'planned_destairport', 'server', 'protrevision', 'rating', 'transponder', 
			'facilitytype', 'visualrange', 'planned_revision', 'planned_flighttype', 'planned_deptime', 
			'planned_actdeptime', 'planned_hrsenroute', 'planned_minenroute', 'planned_hrsfuel', 
			'planned_minfuel', 'planned_altairport', 'planned_remarks', 'planned_route', 
			'planned_depairport_lat', 'planned_depairport_lon', 'planned_destairport_lat', 
			'planned_destairport_lon', 'atis_message', 'time_last_atis_received', 'time_logon', 
			'heading', 'QNH_iHg', 'QNH_Mb',
		);

		return array_combine($keys, $data);
	}

	private function _filterByPilot() {
		return (is_array($data) && $data[3] == 'PILOT');
	}

	private function _filterByAtc($data) {
		return (is_array($data) && $data[3] == 'ATC');
	}

}

$obj = new Vatsim;