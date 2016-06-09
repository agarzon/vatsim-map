<?php
header('Content-Type: application/json');
require_once 'Classes.php';
$obj = new Classes\Vatsim;

/* Here you can choose what you want to show */

$airlinePilots = $obj->showByAirline('TCA');
//$allATC = $obj->showType('ATC');
//$allPilots = $obj->showType('PILOT');

echo json_encode($airlinePilots);
