<?php
ob_start();
session_start();
$conn = new mysqli("localhost","root","","homeorder");
if ($conn->connect_error) {
    die("DB Error");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        *{
  /* =======================
   RESET
======================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
    background:#F6F7FB;
    color:#1F2937;
    overflow-x:hidden;
}

/* =======================
   SIDEBAR
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
    transition:transform 0.3s ease;
    z-index:1000;
}

#menubar h1{
    font-size:26px;
    font-weight:700;
    padding-left:18px;
    margin-bottom:28px;
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
    opacity:0.85;
    text-align:center;
}

/* =======================
   HEADER
======================= */
Header{
    height:76px;
    background:#FFFFFF;
    width:calc(100% - 260px);
    margin-left:260px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 28px;
    border-radius:0 0 22px 22px;
}

#username{
    font-size:20px;
    font-weight:600;
}

#start a{
    color:#6D28D9;
    font-weight:600;
    font-size:14px;
}

/* =======================
   MAIN WRAPPER
======================= */
#over{
    margin-left:260px;
    padding:28px;
}

/* =======================
   DASHBOARD CARDS
======================= */
#view{
    background:#FFFFFF;
    border-radius:24px;
    padding:26px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:18px;
    box-shadow:0 10px 35px rgba(0,0,0,0.08);
}

.card{
    background:#F4F1FA;
    border-radius:18px;
    padding:18px;
    min-height:110px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.card h3{
    font-size:13px;
    color:#6B7280;
    margin-bottom:6px;
}

.card p{
    font-size:28px;
    font-weight:700;
    color:#4C2A8C;
}

/* =======================
   FORM
======================= */
form{
    background:#FFFFFF;
    padding:24px;
    border-radius:16px;
    box-shadow:0 12px 35px rgba(0,0,0,0.1);
    max-width:100%;
    margin:30px auto;
    display:flex;
    flex-direction:column;
    gap:15px;
}

input[type="file"]{
    padding:12px;
    border:1px dashed #CBD5E1;
    border-radius:10px;
    width:40%;
      margin: auto;
}

input[type="submit"]{
    background:#4F46E5;
    color:white;
    border:none;
    padding:14px;
    font-size:16px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    margin: auto;
}

/* =======================
   TABLE
======================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
    background:#FFFFFF;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 12px 30px rgba(0,0,0,0.08);
}

th{
    background:#4F46E5;
    color:white;
    padding:14px;
    font-size:14px;
}

td{
    padding:12px;
    border-bottom:1px solid #E5E7EB;
    font-size:14px;
}

tr:hover{
    background:#EEF2FF;
}

/* =======================
   RESPONSIVE – TABLET
======================= */
@media (max-width: 1024px){
    Header{
        width:100%;
        margin-left:0;
    }

    #over{
        margin-left:0;
    }

    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
    }
}

/* =======================
   RESPONSIVE – MOBILE
======================= */

#menuToggle {
    display: none;
    font-size: 22px;
    cursor: pointer;
    color: #4C2A8C;
}

/* =======================
   MOBILE SIDEBAR SYSTEM
======================= */
@media (max-width: 768px) {

    /* Show hamburger */
    #menuToggle {
        display: block;
    }

    /* Sidebar hidden by default */
    #menubar {
        position: fixed;
        top: 0;
        left: -260px;
        height: 100vh;
        width: 260px;
        transition: left 0.3s ease;
        z-index: 2000;
    }

    /* Sidebar visible */
    #menubar.active {
        left: 0;
    }

    /* Reset layout spacing */
    Header {
        width: 100%;
        margin-left: 0;
        border-radius: 0;
    }

    #over,
    table,
    #map,
    #graph {
        width: 100%;
        margin-left: 0;
    }

    /* Disable floats on mobile */
    #map,
    #graph {
        float: none;
    }

    /* Overlay */
    body::after {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 1500;
    }

    body.sidebar-open::after {
        opacity: 1;
        pointer-events: all;
    }
}



/* ================= LINKS ================= */
a{
    text-decoration:none;
    color:inherit;
}

    </style>
</head>
<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'homeorder');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$total_orders = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='completed'");
$completed = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='pending'");
$pending = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='canceled'OR order_status='cancelled'");
$canceled = $result ? $result->fetch_assoc()['count'] : 0;

$conn->close();
?>
<body>
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
          <Header>
    <div id="menuToggle"><i class="fas fa-bars"></i></div>
    <div id="username">Hi, Aditya !</div>
    <div id="start"><a href="#">Ready to Start ?</a></div>
</Header>
        <div id="over">
            <div id="view">
                
                <div class="card">
                    <h3><i class="fas fa-shopping-cart"></i> Total Orders</h3>
                    
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-check-circle"></i> Completed Orders</h3>
                  
                    <p><?php echo $completed; ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-clock"></i> Pending Orders</h3>
                    
                    <p><?php echo $pending; ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-times-circle"></i> Canceled Orders</h3>
                    
                    <p><?php echo $canceled; ?></p>
                </div>
            </div>
        </div>

        <div id="main">
        
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".txt,.csv" required>
            <input type="submit" value="Upload and Update Orders">
        </form>
        <?php
// Always show orders from database

ob_start();


/* =========================
   DATABASE CONNECTION
========================= */
$conn = new mysqli("localhost", "root", "", "homeorder");
if ($conn->connect_error) {
    die("Database Connection Failed");
}

/* =========================
   CREATE TABLE (ONE TIME)
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
    order_item TEXT,
    quantity INT DEFAULT 1,
    quantity_raw VARCHAR(50),
    payment_method VARCHAR(50),
    price DECIMAL(10,2) DEFAULT 0,
    status VARCHAR(20)
)");


/* =========================
   CSV UPLOAD & UPDATE
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['file'])) {

    $handle = fopen($_FILES['file']['tmp_name'], "r");
    if (!$handle) {
        die("Cannot read file");
    }

    fgetcsv($handle); // skip header

    $inserted = 0;
    $updated  = 0;

    $sql = "
        INSERT INTO orders (
            order_id, rider_name, order_date, customer_name,
            phone, address, city, state, order_item,
            quantity, quantity_raw, payment_method, price, status
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE
            rider_name=VALUES(rider_name),
            order_date=VALUES(order_date),
            customer_name=VALUES(customer_name),
            phone=VALUES(phone),
            address=VALUES(address),
            city=VALUES(city),
            state=VALUES(state),
            order_item=VALUES(order_item),
            quantity=VALUES(quantity),
            quantity_raw=VALUES(quantity_raw),
            payment_method=VALUES(payment_method),
            price=VALUES(price),
            status=VALUES(status)
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL ERROR: " . $conn->error);
    }

    while (($row = fgetcsv($handle, 2000, ",")) !== false) {

        if (empty($row[0])) continue;

        // DATE FIX (Excel safe)
        $dateRaw = trim($row[2]);
        if (is_numeric($dateRaw)) {
            $order_date = date("Y-m-d", strtotime("1899-12-30 +$dateRaw days"));
        } else {
            $order_date = date("Y-m-d", strtotime(str_replace('/', '-', $dateRaw)));
        }

        // QUANTITY FIX
        $quantity_raw = trim($row[9]);

if (is_numeric($quantity_raw)) {
    $quantity = (int)$quantity_raw;   // EXACT VALUE
} else {
    $quantity = 1;                    // fallback
}


        // PAYMENT STATUS FIX
        $payment_method = strtolower(trim($row[10]));
        $status = ($payment_method === 'cod') ? 'COD' : 'PREPAID';

        // BIND DATA
        $price = is_numeric($row[11] ?? null) ? (float)$row[11] : 0;
$order_id       = trim($row[0]);
$rider_name     = trim($row[1]);

$dateRaw = trim($row[2]);
if (is_numeric($dateRaw)) {
    $order_date = date("Y-m-d", strtotime("1899-12-30 +$dateRaw days"));
} else {
    $order_date = date("Y-m-d", strtotime(str_replace('/', '-', $dateRaw)));
}

$customer_name  = trim($row[3]);
$phone          = trim($row[4]);
$address        = trim($row[5]);
$city           = trim($row[6]);
$state          = trim($row[7]);
$order_item     = trim($row[8]);

$quantity_raw = trim($row[9]);
$quantity = is_numeric($quantity_raw) ? (int)$quantity_raw : 1;


$payment_method = strtolower(trim($row[10]));
$price          = is_numeric($row[11] ?? '') ? (float)$row[11] : 0;

$status         = ($payment_method === 'cod') ? 'COD' : 'PREPAID';
$stmt->bind_param(
    "sssssssssissds",
    $order_id,
    $rider_name,
    $order_date,
    $customer_name,
    $phone,
    $address,
    $city,
    $state,
    $order_item,
    $quantity,
    $quantity_raw,
    $payment_method,
    $price,
    $status
);


        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $inserted++;
        } else {
            $updated++;
        }
    }

    fclose($handle);

    echo "<p style='color:green;text-align:center;font-weight:600;'>
        Upload Successful<br>
        Inserted: $inserted | Updated: $updated
    </p>";
}

$conn->close();
?>


    </body>
</html>
