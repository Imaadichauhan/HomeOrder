<?php
session_start();
$conn = new mysqli("localhost","root","","homeorder");

$data = json_decode(file_get_contents("php://input"), true);

$rider = $_SESSION['rider_username'];

$lat = $data['lat'] ?? null;
$lng = $data['lng'] ?? null;
$tracking = $data['tracking'];

if ($tracking == 1) {
    $conn->query("
    INSERT INTO rider_live_location
    (rider_username, latitude, longitude, is_tracking)
    VALUES ('$rider','$lat','$lng',1)
    ON DUPLICATE KEY UPDATE
    latitude='$lat', longitude='$lng', is_tracking=1
    ");
} else {
    $conn->query("
    UPDATE rider_live_location
    SET is_tracking=0
    WHERE rider_username='$rider'
    ");
}
