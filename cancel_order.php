<?php
include 'db.php';

if(isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];
    $reason = $_POST['reason']; // optional

    $sql = "UPDATE orders SET order_status='canceled', cancel_reason='$reason' WHERE order_id='$order_id'";
    if(mysqli_query($conn, $sql)){
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
}
?>
