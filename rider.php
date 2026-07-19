<?php
/* =========================
   DATABASE CONNECTION
========================= */
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection Failed");
}

/* =========================
   CREATE DATABASE
========================= */
$conn->query("CREATE DATABASE IF NOT EXISTS homeorder");
$conn->select_db("homeorder");

/* =========================
   CREATE TABLE
========================= */
$conn->query("
CREATE TABLE IF NOT EXISTS riders (
    emp_id VARCHAR(50),
    fname VARCHAR(50),
    lname VARCHAR(50),
    dob DATE,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) // Add password column to the riders table if it doesn't exist
)
");

/* =========================
   INSERT DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $emp_id  = $_POST['emp_id'];
    $fname   = $_POST['fname'];
    $lname   = $_POST['lname'];
    $dob     = $_POST['dob'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Debugging: Check if the prepare statement fails
    $stmt = $conn->prepare("
        INSERT INTO riders (emp_id, fname, lname, dob, phone, address, password)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Output the SQL error for debugging
    }

    $stmt->bind_param("sssssss", $emp_id, $fname, $lname, $dob, $phone, $address, $password);
    $stmt->execute();

    // 🔥 MOST IMPORTANT LINE
    header("Location: rider.php?success=1");
    exit;
}

?>
<?php
// DELETE RECORD
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM riders WHERE emp_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    header("Location: ".$_SERVER['PHP_SELF']); // refresh page
    exit;
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
   /* =======================
   GLOBAL RESET
======================= */
/* =======================
   GLOBAL RESET
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
    transition: all 0.3s ease;
}

#menubar h1{
    font-size:26px;
    font-weight:700;
    text-align:left;
    padding-left:18px;
    margin-bottom:28px;
}

ul{
    list-style:none;
    padding-left:0;
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

/* =======================
   HEADER
======================= */
header{
    height:76px;
    background:#FFFFFF;
    width:calc(100% - 260px);
    margin-left:260px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 28px;
    border-radius:0 0 22px 22px;
    transition: all 0.3s ease;
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
    transition: all 0.3s ease;
}

/* =======================
   OVERVIEW CONTAINER
======================= */
#view{
    background:#FFFFFF;
    border-radius:24px;
    padding:26px;
    display:grid;
    grid-template-columns: 220px repeat(4, 1fr);
    gap:18px;
    box-shadow:0 10px 35px rgba(0,0,0,0.08);
}

/* LEFT OVERVIEW TEXT */
#view::before{
    content:"Today's Overview";
    grid-column:1;
    font-size:18px;
    font-weight:600;
    color:#4C2A8C;
}

/* =======================
   CARDS (PIDGE STYLE)
======================= */
.card{
    background:#F4F1FA;
    border-radius:18px;
    padding:18px;
    height:110px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.card h3{
    font-size:13px;
    font-weight:500;
    color:#6B7280;
    margin-bottom:6px;
}

.card p{
    font-size:30px;
    font-weight:700;
    color:#4C2A8C;
}

/* =======================
   TABLE (PIDGE LOOK)
======================= */
table{
    width:calc(100% - 260px);
    margin:30px 0 0 260px;
    background:#FFFFFF;
    border-collapse:separate;
    border-spacing:0;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

th{
    background:#F4F1FA;
    color:#6B21A8;
    padding:14px;
    font-size:13px;
    font-weight:600;
    text-align:center;
}

td{
    padding:14px;
    font-size:13px;
    color:#374151;
    border-top:1px solid #E5E7EB;
}

tr:hover{
    background:#FAF7FF;
}

/* =======================
   BUTTON / LINKS
======================= */
a{
    text-decoration:none;
    color:inherit;
}

/* =======================
   RIDER FORM
======================= */
#riderForm{
    width:420px;
    background:#4C2A8C;
    margin:40px auto;
    border-radius:24px;
    padding:25px;
    display:none;
    justify-content:center;
    align-items:center;
    flex-wrap:wrap;
    box-shadow:0 12px 35px rgba(0,0,0,0.12);


}

#riderForm h3{
    margin-bottom:15px;
    color:white;
    text-align: center;
    font-weight:600;
}

input, textarea{
    width:70%;
    padding:10px 12px;
    margin:8px 0;
    border-radius:8px;
    border:1px solid #CBD5E1;
    outline:none;
    font-size:14px;
}

input:focus, textarea:focus{
    border-color:#4F46E5;
}

.sub{
    background:#22C55E;
    color:white;
    padding:10px 20px;
    position: relative;
    left: 600px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
}

.sub:hover{
    background:#16A34A;
}

#ride{
    background:#4F46E5;
    color:white;
    padding:10px 18px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
}

#ride:hover{
    background:#4338CA;
}

/* =======================
   TABLE RIDER LIST
======================= */
#riderTable h3{
    margin-left:0;
    margin-top:20px;
    font-weight:600;
    text-align:center;
}

#riderTable table{
    width:75%;
    border-collapse:collapse;
    margin:20px 0px 0px 400px auto;
    background:#FFFFFF;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

#riderTable th{
    background:#4F46E5;
    color:white;
    padding:14px;
    font-size:14px;
}

#riderTable td{
    padding:12px;
    border-bottom:1px solid #E5E7EB;
    font-size:14px;
    border-radius: 10px;
}

#riderTable tr:hover{
    background:#EEF2FF;
}

/* =======================
   RESPONSIVE MEDIA QUERIES
======================= */

/* TABLETS / MEDIUM SCREENS */
@media (max-width: 1024px){
    #menubar{
        width:200px;
    }
    header{
        width:calc(100% - 200px);
        margin-left:200px;
    }
    #over{
        margin-left:200px;
        padding:20px;
    }
    #riderForm{
        width:360px;
        left:50%;
        transform: translateX(-50%);
        top:20px;
    }
}

/#menuToggle {
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
#ride{
    left: 650px;
    position: relative;
}
input,textarea{
    left: 300px;
    position: relative;
}

    </style>
</head>

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
    <button id="ride" onclick="showForm()">➕ New Rider</button>


    <form method="POST" action="">
        <input type="text" name="emp_id" placeholder="EMP ID" required>
        <input type="text" name="fname" placeholder="First Name" required>
        <input type="text" name="lname" placeholder="Last Name" required>
        <input type="date" name="dob" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <textarea name="address" placeholder="Address"></textarea>
        <input type="password" name="password" placeholder="Password" required> <!-- Added password field -->
<br><br>
        <button type="submit" class="sub">Save Rider</button>
        <button type="button" class="sub" onclick="hideForm()">Cancel</button>
    </form>
</div>

<div id="riderTable">
    <h3>Riders List</h3>

    <table border="1">
        <tr>
            <th>EMP ID</th>
            <th>Name</th>
            <th>DOB</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Created</th>
        </tr>

        <?php
$result = $conn->query("SELECT * FROM riders ORDER BY created_at DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {

        echo "<tr>
                <td>{$row['emp_id']}</td>
                <td>{$row['fname']} {$row['lname']}</td>
                <td>{$row['dob']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='?delete={$row['emp_id']}'
                       onclick=\"return confirm('Are you sure you want to delete this rider?')\">
                       ❌
                    </a>
                </td>
              </tr>";
    }
}
?>

    </table>
</div>


      <script>

function showForm() {
    document.getElementById("riderForm").style.display = "flex";
    document.getElementById("riderTable").style.display = "none";
}

function hideForm() {
    document.getElementById("riderForm").style.display = "none";
    document.getElementById("riderTable").style.display = "block";
}


    const menuToggle = document.getElementById("menuToggle");
const sidebar = document.getElementById("menubar");

menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    document.body.classList.toggle("sidebar-open");
});



</script>

    </body>
</html>
