<?php
$conn = new mysqli('localhost', 'root', '', 'homeorder');
if ($conn->connect_error) {
    echo json_encode(["status"=>"error","message"=>"DB error"]);
    exit;
}

$id = $_POST['proof_id'] ?? 0;

// image path fetch
$res = $conn->query("SELECT image_path FROM delivery_proofs WHERE id=$id");
if ($res->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"Record not found"]);
    exit;
}

$row = $res->fetch_assoc();
$imagePath = $row['image_path'];

// delete record
$conn->query("DELETE FROM delivery_proofs WHERE id=$id");

// delete image file
if (file_exists($imagePath)) {
    unlink($imagePath);
}

echo json_encode([
    "status" => "success",
    "message" => "Delivery proof deleted successfully"
]);
