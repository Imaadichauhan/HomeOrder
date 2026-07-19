<?php
header('Content-Type: application/json');

if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
    echo json_encode([]);
    exit;
}

$lat = $_GET['lat'];
$lng = $_GET['lng'];

$opts = [
    "http" => [
        "header" => "User-Agent: RiderApp/1.0\r\n"
    ]
];

$context = stream_context_create($opts);

$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lng";
$response = file_get_contents($url, false, $context);

echo $response;
