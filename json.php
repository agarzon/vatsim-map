<?php
header('Content-Type: application/json');
require_once('vatsim.php');

$obj = new Vatsim;
$pilots = $obj->showType();
echo json_encode($pilots);