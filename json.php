<?php
header('Content-Type: application/json');
require_once('vatsim.php');
$obj = new Vatsim;

/* Here you can choose what you want to show */
//$airlinePilots = $obj->showByAirline('TCA');
$allPilots = $obj->showType('PILOT');
//$allATC = $obj->showType('ATC');

echo json_encode($allPilots);
