<?php

namespace Classes;

class Vatsim
{
    public $data = array();
    public $icao = '';

    public function __construct()
    {
        $this->data = $this->getClients();
    }

    /**
     * Generate random boolean
     *
     * @return boolean
     */
    private function getRandom()
    {
        return rand() > rand();
    }

    /**
     * Parse vatsim data taked from vatsim-data servers
     *
     * @return array $data clients connected
     */
    public function getClients()
    {
        $servers = array(
            'http://info.vroute.net/vatsim-data.txt',
            'http://vatsim.aircharts.org/vatsim-data.txt',
            'http://vatsim-data.hardern.net/vatsim-data.txt',
            'http://data.vattastic.com/vatsim-data.txt',
            );

        uksort($servers, array($this, 'getRandom'));

        $file    = file($servers[0]);
        $allowed = false;
        $data    = array();

        foreach ($file as $ifile) {
            if (substr($ifile, 0, 1) != ";") {
                $ifile = utf8_decode(rtrim($ifile));
                if ($allowed == true && substr($ifile, 0, 1) != "!") {
                    $data[] = self::parseAssociative(explode(":", $ifile));
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

    /**
     * Create an array using a propoer keys names
     *
     * @param  array $array loaded from vatsim-data.txt
     * @return array parsed as associative.
     */
    private static function parseAssociative($array)
    {
        $keys = array(
            'callsign', 'cid', 'realname', 'clienttype', 'frequency', 'latitude', 'longitude',
            'altitude', 'groundspeed', 'planned_aircraft', 'planned_tascruise', 'planned_depairport',
            'planned_altitude', 'planned_destairport', 'server', 'protrevision', 'rating', 'transponder',
            'facilitytype', 'visualrange', 'planned_revision', 'planned_flighttype', 'planned_deptime',
            'planned_actdeptime', 'planned_hrsenroute', 'planned_minenroute', 'planned_hrsfuel',
            'planned_minfuel', 'planned_altairport', 'planned_remarks', 'planned_route',
            'planned_depairport_lat', 'planned_depairport_lon', 'planned_destairport_lat',
            'planned_destairport_lon', 'atis_message', 'time_last_atis_received', 'time_logon',
            'heading', 'QNH_iHg', 'QNH_Mb', 'NO_USED'
            );

        return array_combine($keys, $array);
    }

    /**
     * Filter array to show only PILOTS or ATC
     *
     * @param  string $type PILOT or ATC
     * @return array
     */
    public function showType($type = 'PILOT')
    {
        if ($type === 'PILOT') {
            return array_filter($this->data, array($this, 'filterByPilot'));
        }

        return array_filter($this->data, array($this, 'filterByAtc'));
    }

    /**
     * Filter array by callsign
     *
     * @param  string $icao Airline ICAO
     * @return array
     */
    public function showByAirline($icao = 'TCA')
    {
        $this->icao = $icao;

        return array_filter($this->data, array($this, 'filterByAirline'));
    }

    /**
     * Internal filter by PILOT
     *
     * @param  array $data
     * @return array
     */
    private function filterByPilot($data)
    {
        return $data['clienttype'] == 'PILOT';
    }

    /**
     * Internal filter by ATC
     *
     * @param  array $data
     * @return array
     */
    private function filterByAtc($data)
    {
        return $data['clienttype'] == 'ATC';
    }

    /**
     * Internal filter by callsign
     *
     * @param  array $data
     * @return array
     */
    private function filterByAirline($data)
    {
        return (!preg_match("/^$this->icao/", $data['callsign']) === false);
    }
}
