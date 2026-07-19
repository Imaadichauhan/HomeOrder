<?php
/* =========================
   DATABASE CONNECTION
========================= */
$conn = new mysqli("localhost", "root", "", "homeorder");
if ($conn->connect_error) {
    die("Database Connection Failed");
}

/* =========================
   CREATE TABLE (SAFE)
========================= */
$conn->query("
CREATE TABLE IF NOT EXISTS orders (
    order_id VARCHAR(50) PRIMARY KEY,
    rider_name VARCHAR(100),
    order_date DATE,
    customer_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    order_item VARCHAR(100),
    quantity INT DEFAULT 1,
    payment_method VARCHAR(50),
    price DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending'
)");

/* =========================
   DELETE ORDER (SAME PAGE)
========================= */
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    if (!$conn->query("DELETE FROM orders WHERE order_id='$id'")) {
        echo "Error deleting order: " . $conn->error;
    }
}

/* =========================
   COUNTS
========================= */
$total = 0;
$completed = 0;
$pending = 0;
$canceled = 0;

$result = $conn->query("SELECT COUNT(*) c FROM orders");
if ($result) {
    $total = $result->fetch_assoc()['c'];
} else {
    echo "Error fetching total count: " . $conn->error;
}

$result = $conn->query("SELECT COUNT(*) c FROM orders WHERE order_status='completed'");
if ($result) {
    $completed = $result->fetch_assoc()['c'];
} else {
    echo "Error fetching completed count: " . $conn->error;
}

$result = $conn->query("SELECT COUNT(*) c FROM orders WHERE order_status='pending'");
if ($result) {
    $pending = $result->fetch_assoc()['c'];
} else {
    echo "Error fetching pending count: " . $conn->error;
}

$result = $conn->query("SELECT COUNT(*) c FROM orders WHERE order_status='canceled' OR order_status='cancelled'");
if ($result) {
    $canceled = $result->fetch_assoc()['c'];
} else {
    echo "Error fetching canceled count: " . $conn->error;
}

/* =========================
   FETCH ORDERS
========================= */
$orders = $conn->query("
    SELECT * FROM orders
    WHERE order_status = 'pending'
    ORDER BY order_date DESC
");


if (isset($_POST['assign_all'])) {

    $filtered = $conn->real_escape_string($_POST['filtered_rider']);
    $new_rider = $conn->real_escape_string($_POST['assign_rider']);

    if ($filtered === "all") {
        // Assign ALL orders
        $sql = "
            UPDATE orders 
            SET rider_name='$new_rider', order_status='assigned'
            WHERE order_status='pending'
        ";
    } else {
        // Assign only FILTERED rider orders
        $sql = "
            UPDATE orders 
            SET rider_name='$new_rider', order_status='assigned'
            WHERE rider_name='$filtered' AND order_status='pending'
        ";
    }

    // Debugging the SQL query to ensure proper assignment
   if ($conn->query($sql)) {
        // Redirect to the same page to prevent form resubmission
        header("Location: order.php");
        exit();
    } else {
        echo "<script>alert('Failed to assign orders: " . $conn->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orders Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Inter,Arial;background:#F6F7FB}

body{
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
    background:#F6F7FB;
    color:#1F2937;
}

/* =======================
   SIDEBAR (PIDGE STYLE)
======================= */
#menubar{
    height:100vh;
    width:260px;
    background:#4C2A8C;
    color:#FFFFFF;
    position:fixed;
    left:0;
    top:0;
    padding:22px 14px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}
a{
    text-decoration:none;
    color:inherit;
}s

#menubar h1{
    font-size:26px;
    font-weight:700;
    text-align:left;
    padding-left:18px;
    margin-bottom:28px;
}
/* HEADER */
header{
    margin-left:260px;height:70px;background:#fff;
    display:flex;align-items:center;justify-content:space-between;
    padding:0 25px;border-radius:0 0 20px 20px
}

ul{
    list-style:none;
}

ul li{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 18px;
    font-size:15px;
    border-radius:14px;
    margin-bottom:6px;
    cursor:pointer;
    transition:0.25s ease;
}

ul li:hover{
    background:rgba(255,255,255,0.15);
}

ul li:first-child{
    background:#EDE9FE;
    color:#4C2A8C;
    font-weight:600;
}

h2{
    font-size:14px;
    opacity:0.8;
    text-align:center;
}
/* MAIN */
#main{margin-left:260px;padding:25px}

/* CARDS */
#cards{
    display:grid;grid-template-columns:repeat(4,1fr);
    gap:15px;margin-bottom:25px
}
.card{
    background:#F4F1FA;padding:18px;border-radius:18px;
    text-align:center
}
.card h3{font-size:14px;color:#6B7280}
.card p{font-size:30px;font-weight:700;color:#4C2A8C}

/* TABLE */
table{
    width:100%;background:#fff;border-radius:20px;
    border-collapse:collapse;overflow:hidden
}
th,td{padding:12px;text-align:center;font-size:13px}
th{background:#F4F1FA;color:#6B21A8}
tr:hover{background:#FAF7FF}

/* BUTTON */
#toggleBtn{
    padding:8px 14px;border:none;border-radius:10px;
    background:#4C2A8C;color:#fff;cursor:pointer
}
#toggleBtn:hover{background:#6D28D9}

.hide #menubar{display:none}
.hide header,.hide #main{margin-left:0;width:100%}

select{
    padding:8px;border-radius:10px;font-weight:600
}
a{
    text-decoration: none;
}
#srch{
    width: 100px;
    height: 40px;
    background-color: red;
    color: white;
    border-radius: 30px;
    border: none;
    cursor: pointer;
}
</style>
</head>

<body class="hide">

<div id="menubar">
    <h1>Aditya</h1>
      <ul>
            <a href="home.php"><li>📊 Dashbaord</li></a>
            <a href="upload.php"><li>➕ Create Order</li></a>
            <a href="order.php"><li>📋 Order's</li></a>
            <a href="rider.php"><li>🛣️ Rider's</li></a>
            <a href="image.php"><li>🔍 Image Proof</li></a>
            <a href="allorders.php"><li>🌍 All order</li></a>
            <a href="rider_cod.php"><li>💵 Cash management</li></a>
            <a href="rider_login.php"><li>Go to Rider Panel</li></a>
        </ul>
        <h2>Log Out</h2>
</div>

<header>
    <div><b>Hi, Aditya 👋</b></div>
    <div>
        <select id="riderFilter">
    <option value="all">All Riders</option>
    <option value="arun">ARUN</option>
    <option value="abhishek">ABHISHEK</option>
    <option value="raider 1">RAIDER 1</option>
    <option value="raider 2">RAIDER 2</option>
</select>

        <button id="toggleBtn">☰ Menu</button>
    </div>
</header>

<div id="main">

<div id="cards">
    <div class="card"><h3>Total Orders</h3><p><?= $total ?></p></div>
    <div class="card"><h3>Completed</h3><p><?= $completed ?></p></div>
    <div class="card"><h3>Pending</h3><p><?= $pending ?></p></div>
    <div class="card"><h3>Canceled</h3><p><?= $canceled ?></p></div>
</div>

<?php
$search = $_GET['search'] ?? '';

if ($search !== '') {

    $stmt = $conn->prepare("
        SELECT * FROM orders
        WHERE order_status='pending'
        AND order_id LIKE ?
        ORDER BY order_date DESC
    ");

    $like = "%{$search}%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $orders = $stmt->get_result();

} else {

    $orders = $conn->query("
        SELECT * FROM orders
        WHERE order_status='pending'
        ORDER BY order_date DESC
    ");
}
?>

<form method="get" style="margin-bottom:15px;">
    <input
        type="text"
        name="search"
        placeholder="Search by Order ID"
        value="<?= htmlspecialchars($search) ?>"
        style="padding:10px;border-radius:8px;border:1px solid #ccc;"
    >
    <button id="srch" type="submit">Search</button>
</form>


<table>
<tr>
<th>ID</th>
<th>Rider</th>
<th>Date</th>
<th>Customer</th>
<th>Phone</th>
<th>Address</th>
<th>Item</th>
<th>Qty</th>
<th>Price</th>
<th>Order Status</th> <!-- NEW COLUMN -->

<th>Action</th>
</tr>


<?php while($row = $orders->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['order_id']) ?></td>
<td><?= htmlspecialchars($row['rider_name']) ?></td>
<td><?= date("d-m-Y", strtotime($row['order_date'])) ?></td>
<td><?= htmlspecialchars($row['customer_name']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>
<td><?= htmlspecialchars($row['address']) ?></td>
<td class="item-col"><?= htmlspecialchars($row['order_item']) ?></td>
<td><?= htmlspecialchars($row['quantity_raw']) ?></td>
<td>₹<?= number_format($row['price'], 2) ?></td>

<!-- ORDER STATUS -->
<td>
<?php 
if(isset($row['order_status'])){
    if($row['order_status']=='completed'){
        echo '<span style="color:#16A34A;font-weight:600;">Completed</span>';
    } else if($row['order_status']=='canceled'){
        echo '<span style="color:#DC2626;font-weight:600;">Cancelled</span>';
    } else if($row['order_status']=='pending'){
        echo '<span style="color:#F59E0B;font-weight:600;">Pending</span>';
    } else if($row['order_status']=='assigned'){
        echo '<span style="color:#F59E0B;font-weight:600;">Assigned</span>';
    }
}
?>
</td>

<!-- PAYMENT STATUS -->
<td>
<?php if (strtoupper($row['payment_method']) === 'COD'): ?>
    <span style="color:#DC2626;font-weight:600;">COD</span>
<?php else: ?>
    <span style="color:#16A34A;font-weight:600;">PREPAID</span>
<?php endif; ?>
</td>

</tr>
<?php endwhile; ?>

</table>

</div>

<script>
document.getElementById("toggleBtn").onclick=()=>{
    document.body.classList.toggle("hide");
};


</script>

</body>
</html>
