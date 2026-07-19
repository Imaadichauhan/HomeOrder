<?php
session_start();
include "db.php";

if (!isset($_SESSION['emp_id'])) {
    die("Rider not logged in");
}

$rider_id = $_SESSION['emp_id'];
$order_id = $_POST['order_id'];

$uploadDir = __DIR__ . "/uploads/delivery_proofs/"; // Corrected folder path

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die("Failed to create upload directory");
    }
}

if (!isset($_FILES['delivery_image'])) {
    error_log("No image received", 0);
    die("No image received");
}

$image = $_FILES['delivery_image'];

if ($image['error'] !== 0) {
    error_log("File upload error code: " . $image['error'], 0);
    die("File upload error code: " . $image['error']);
}

$ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png'];

if (!in_array($ext, $allowed)) {
    error_log("Invalid image type: " . $ext, 0);
    die("Invalid image type");
}

$filename = "order_{$order_id}_rider_{$rider_id}_" . time() . "." . $ext;

$fullPath = $uploadDir . $filename;                 // server path
$dbPath   = "uploads/delivery_proofs/" . $filename; // Corrected DB path

if (move_uploaded_file($image['tmp_name'], $fullPath)) {
    $stmt = $conn->prepare(
        "INSERT INTO delivery_proofs (order_id, rider_id, image_path)
         VALUES (?, ?, ?)"
    );
    if (!$stmt) {
        error_log("Database prepare failed: " . $conn->error, 0);
        die("Database error");
    }
    $stmt->bind_param("iis", $order_id, $rider_id, $dbPath);
    if (!$stmt->execute()) {
        error_log("Database execute failed: " . $stmt->error, 0);
        die("Database error");
    }
    echo "Delivery proof uploaded successfully";
} else {
    error_log("Failed to move uploaded file", 0);
    die("Failed to move uploaded file");
}
