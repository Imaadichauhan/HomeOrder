<?php
session_start();
include 'db.php';

if(!isset($_SESSION['rider_name'])){
    header("Location: rider_login.php");
    exit();
}

if(isset($_POST['order_id'], $_POST['status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=? AND rider_name=?");
    $stmt->bind_param("sss", $status, $order_id, $_SESSION['rider_name']);
    $stmt->execute();

    header("Location: rider_panel.php");
    exit();
}
?>
