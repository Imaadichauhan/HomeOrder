<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'homeorder');
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$sql = "SELECT order_id, customer_name, address, latitude, longitude FROM orders WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
$result = $conn->query($sql);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = [
        'order_id' => $row['order_id'],
        'customer_name' => $row['customer_name'],
        'address' => $row['address'],
        'latitude' => (float)$row['latitude'],
        'longitude' => (float)$row['longitude']
    ];
}

$conn->close();

echo json_encode($locations);
?>