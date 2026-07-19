<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION['admin'];
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
/* =======================
   GLOBAL RESET
======================= */
/* =======================
   GLOBAL RESET
======================= */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
    background: #F6F7FB;
    color: #1F2937;
}

/* =======================
   SIDEBAR
======================= */
#menubar {
    height: 100vh;
    width: 260px;
    background: #4C2A8C;
    color: #FFFFFF;
    position: fixed;
    left: 0;
    top: 0;
    padding: 22px 14px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 1000;
}

#menubar h1 {
    font-size: 24px;
    font-weight: 700;
    padding-left: 18px;
}

ul {
    list-style: none;
}

ul li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 15px;
    border-radius: 14px;
    margin-bottom: 6px;
    cursor: pointer;
    transition: 0.25s ease;
}

ul li:hover {
    background: rgba(255, 255, 255, 0.15);
}

ul li:first-child {
    background: #EDE9FE;
    color: #4C2A8C;
    font-weight: 600;
}

h2 {
    font-size: 14px;
    opacity: 0.8;
    text-align: center;
}

/* =======================
   HEADER
======================= */
Header {
    height: 76px;
    background: #FFFFFF;
    width: calc(100% - 260px);
    margin-left: 260px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    border-radius: 0 0 22px 22px;
}

#username {
    font-size: 18px;
    font-weight: 600;
}

#start a {
    color: #6D28D9;
    font-weight: 600;
    font-size: 14px;
}

/* =======================
   MAIN WRAPPER
======================= */
#over {
    margin-left: 260px;
    padding: 28px;
}

/* =======================
   OVERVIEW
======================= */
#view {
    background: #FFFFFF;
    border-radius: 24px;
    padding: 26px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 18px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
}

/* =======================
   CARDS
======================= */
.card {
    background: #F4F1FA;
    border-radius: 18px;
    padding: 18px;
    min-height: 110px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.card h3 {
    font-size: 13px;
    font-weight: 500;
    color: #6B7280;
    margin-bottom: 6px;
}

.card p {
    font-size: 28px;
    font-weight: 700;
    color: #4C2A8C;
}

/* =======================
   TABLE
======================= */
table {
    width: calc(100% - 260px);
    margin: 30px 0 0 260px;
    background: #FFFFFF;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 22px;
    overflow-x: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

th {
    background: #F4F1FA;
    color: #6B21A8;
    padding: 14px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
}

td {
    padding: 14px;
    font-size: 13px;
    color: #374151;
    border-top: 1px solid #E5E7EB;
}

tr:hover {
    background: #FAF7FF;
}

/* =======================
   MAP & GRAPH
======================= */
#map,
#graph {
    background: #FFFFFF;
    border-radius: 14px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
}

#map {
    width: calc(40% - 20px);
    height: 400px;
    margin-left: 260px;
    margin-top: 30px;
    float: left;
}

#graph {
    width: calc(40% - 20px);
    height: 400px;
    margin-top: 30px;
    float: right;
}

iframe {
    width: 100%;
    height: 100%;
    border-radius: 12px;
    border: none;
}

canvas {
    width: 100% !important;
    height: 100% !important;
}

/* =======================
   RESPONSIVE
======================= */
@media (max-width: 1024px) {
    #map,
    #graph {
        width: calc(100% - 260px);
        float: none;
        margin-left: 260px;
    }
}
/* =======================
   HAMBURGER ICON
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
a{
    text-decoration:none;
    color:inherit;
}
#graphRider {
    background: #FFFFFF;
    border-radius: 14px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    width: 400px;
    height: 400px;
    position: absolute;
    top: 280px;
    right: 30px;
    margin-left: 260px;
    margin-top: 30px;
    float: left;
}

@media (max-width: 1024px) {
    #graphRider { width: calc(100% - 260px); float: none; margin-left: 260px; }
}

    </style>
</head>
<?php
// Database connection - create DB if not exists
$conn = new mysqli('localhost', 'root', '');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS homeorder");
$conn->select_db('homeorder');

// Create table if not exists
$createTable = "CREATE TABLE IF NOT EXISTS orders (
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
        price DECIMAL(10,2)
    )";
         
$conn->query($createTable);

$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$total_orders = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='completed'");
$completed = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='pending'");
$pending = $result ? $result->fetch_assoc()['count'] : 0;

$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='canceled' OR order_status='cancelled'");
$canceled = $result ? $result->fetch_assoc()['count'] : 0;

$conn->close();
?>
<body>
<?php
$conn = new mysqli('localhost', 'root', '', 'homeorder');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$riders = [];
$riderOrders = [];

$sql = "SELECT rider_name, COUNT(*) AS total FROM orders GROUP BY rider_name";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $riders[] = $row['rider_name'];
        $riderOrders[] = (int)$row['total'];
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>




    <div id="menubar">
        <div id="username">Hi, <?= htmlspecialchars($adminName) ?>!</div>


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
        <h2><a href="logout.php">Log Out</a></h2>
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

        <div id="map">
            <iframe
  width="450px"
  height="400px"
  src="https://www.openstreetmap.org/export/embed.html?bbox=68.1%2C6.5%2C97.4%2C35.9&layer=mapnik&marker=20.5937%2C78.9629">
</iframe>
        </div>
<div id="graphRider">
    <h3 style="text-align: center; color: #333; margin-bottom: 10px;">Orders per Rider</h3>
    <canvas id="riderChart"></canvas>
</div>

<script>
const ctxRider = document.getElementById('riderChart').getContext('2d');

new Chart(ctxRider, {
    type: 'bar',
    data: {
        labels: <?= json_encode($riders) ?>,
        datasets: [{
            label: 'Total Orders',
            data: <?= json_encode($riderOrders) ?>,
            backgroundColor: 'rgba(30, 64, 175, 0.9)',
            borderRadius: 10,
            barThickness: 40
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111827',
                titleColor: '#F9FAFB',
                bodyColor: '#E5E7EB',
                padding: 12,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: '#374151', font: { size: 13, weight: '600' } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#E5E7EB', drawBorder: false },
                ticks: { stepSize: 1, color: '#6B7280' }
            }
        }
    }
});
</script>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $conn = new mysqli('localhost', 'root', '');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->query("CREATE DATABASE IF NOT EXISTS homeorder");
        $conn->select_db('homeorder');
        


       $order_id = uniqid("ORD"); // Unique order ID
$rider_name = $conn->real_escape_string($_POST['rider_name']);
$order_date = date('Y-m-d'); // Current date

$customer_name = $conn->real_escape_string($_POST['customer_name']);
$phone = $conn->real_escape_string($_POST['phone']);

$address = $conn->real_escape_string($_POST['address']);
$city = $conn->real_escape_string($_POST['city']);
$state = $conn->real_escape_string($_POST['state']);

$order_item = $conn->real_escape_string($_POST['order_item']);
$quantity = (int)$_POST['quantity'];
$payment_method = $conn->real_escape_string($_POST['payment_method']);
$price = (float)$_POST['price'];

$status = "pending"; // Default status

$sql = "INSERT INTO orders 
(order_id, rider_name, order_date, customer_name, phone, address, city, state, order_item, quantity, payment_method, price)
VALUES
('$order_id', '$rider_name', '$order_date', '$customer_name', '$phone', '$address', '$city', '$state', '$order_item', '$quantity', '$payment_method', '$price')";




        $conn->close();

        // Redirect to prevent resubmission
        header('Location: home.php');
        exit();
    }
    ?>

</body>
</html>