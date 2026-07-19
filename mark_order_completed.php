<?php
include 'db.php';

if(isset($_POST['order_id'])){
    $order_id = $_POST['order_id'];

    mysqli_query($conn,
        "UPDATE orders 
         SET order_status='completed' 
         WHERE order_id='$order_id'"
    );
}
