<?php
header('Content-Type: application/json');

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$q = urlencode($_GET['q']);

/* Required User-Agent by OSM policy */
$opts = [
    "http" => [
        "header" => "User-Agent: RiderApp/1.0\r\n"
    ]
];

$context = stream_context_create($opts);

$url = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=$q";
$response = file_get_contents($url, false, $context);

echo $response;
