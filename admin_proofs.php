<?php
include "../db.php";
include "login.php"; // admin login check

$query = "
SELECT 
  dp.id,
  dp.image_path,
  dp.uploaded_at,
  o.order_number,
  r.name AS rider_name
FROM delivery_proofs dp
JOIN orders o ON dp.order_id = o.id
JOIN riders r ON dp.rider_id = r.id
ORDER BY dp.uploaded_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivery Proofs</title>
</head>
<body>

<h2>Delivery Proofs</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Order Number</th><th>Rider Name</th><th>Proof Image</th><th>Uploaded At</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['order_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rider_name']) . "</td>";
        echo "<td><img src='../" . htmlspecialchars($row['image_path']) . "' width='100'></td>";
        echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No delivery proofs found.";
}
?>

</body>
</html>

