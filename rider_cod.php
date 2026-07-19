<?php

$conn = new mysqli("localhost","root","","homeorder");

if ($conn->connect_error) {
    die("DB connection failed");
}

$rider = $_GET['rider'] ?? '';

$result = null;
$total_cod = 0;

if ($rider != "") {

    /* ALL ORDERS OF RIDER */

    $stmt = $conn->prepare("
        SELECT order_id, price, order_date, payment_method, order_status
        FROM orders
        WHERE rider_name=?
        ORDER BY order_date DESC
    ");

    $stmt->bind_param("s", $rider);
    $stmt->execute();
    $result = $stmt->get_result();


    /* TOTAL COD CASH */

    $stmt2 = $conn->prepare("
        SELECT SUM(price) AS total_cod
        FROM orders
        WHERE rider_name=?
        AND payment_method='COD'
        AND order_status='delivered'
    ");

    $stmt2->bind_param("s", $rider);
    $stmt2->execute();
    $total_cod = $stmt2->get_result()->fetch_assoc()['total_cod'] ?? 0;
}

/* TOTAL PRICE OF ALL ORDERS */

$stmt3 = $conn->prepare("
SELECT SUM(price) AS total_orders
FROM orders
WHERE rider_name=?
");

$stmt3->bind_param("s",$rider);
$stmt3->execute();

$total_orders = $stmt3->get_result()->fetch_assoc()['total_orders'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>Rider Orders & COD</title>

<style>

body{
font-family:Arial;
background:#F6F7FB;
padding:20px;
}

table{
width:100%;
border-collapse:collapse;
background:white;
}

th,td{
padding:10px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#4C2A8C;
color:white;
}

</style>

</head>

<body>

<h2>Rider Order Report</h2>

<form method="GET">
<input type="text" name="rider" placeholder="Enter Rider Name" required>
<button type="submit">Search</button>
</form>

<br>

<?php if($rider!=""){ ?>

<h3>Rider: <?= htmlspecialchars($rider) ?></h3>
<h3>Total COD Collected: ₹<?= number_format($total_cod,2) ?></h3>
<h3>Total COD Collected: ₹<?= number_format($total_cod,2) ?></h3>
<h3>Total Orders Amount: ₹<?= number_format($total_orders,2) ?></h3>

<table>

<tr>
<th>Order ID</th>
<th>Amount</th>
<th>Payment</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>".$row['order_id']."</td>";
        echo "<td>₹".$row['price']."</td>";
        echo "<td>".$row['payment_method']."</td>";
        echo "<td>".$row['order_status']."</td>";
        echo "<td>".$row['order_date']."</td>";
        echo "</tr>";

    }

} else {

    echo "<tr><td colspan='5'>No orders found</td></tr>";

}

?>

</table>

<?php } ?>

</body>
</html>