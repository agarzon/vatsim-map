<?php
header('Content-Type: application/json');
require_once('vatsim.php');
$obj = new Vatsim;

/* Here you can choose what you want to show */
$airlinePilots = $obj->showByAirline('TCA');
//$allPilots = $obj->showByAirline('TCA');
//$allATC = $obj->showByAirline('TCA');

echo json_encode($airlinePilots);
