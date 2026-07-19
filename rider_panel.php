<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rider_name'])) {
    header("Location: rider_login.php");
    exit();
}

$rider_name = $_SESSION['rider_name'];

$sql = "SELECT * FROM orders WHERE rider_name='$rider_name' ORDER BY order_id DESC";
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Rider Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:#f1f5f9}
.header{position:sticky;top:0;background:#4f46e5;padding:16px;color:#fff;font-size:18px;font-weight:600;text-align:center}
.card{background:#fff;margin:14px;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,.08)}
.card-header{padding:16px;display:flex;justify-content:space-between;cursor:pointer}
.badge{background:#e0f2fe;color:#0369a1;padding:6px 14px;border-radius:999px;font-size:13px}
.details{display:none;padding:16px;background:#f8fafc}
.details div{padding:6px 0;font-size:14px}
.btn{flex:1;padding:12px;border:none;border-radius:12px;font-size:14px;font-weight:600}
.out-for-delivery{background:#f59e0b;color:#fff}
.delivered{background:#22c55e;color:#fff}
.no-order{text-align:center;margin:80px;color:#64748b}
</style>

<script>
function toggle(id){
    let box=document.getElementById(id);
    box.style.display = box.style.display==="block" ? "none" : "block";
}

/* ===== ADDITION: SAVE COMPLETED STATUS ===== */
function markCompleted(cardId, orderId){

    // existing UI behaviour
    document.getElementById('actions-' + cardId).style.display = 'none';
    document.getElementById('status-' + cardId).style.display = 'inline';
    document.getElementById('completed-' + cardId).style.display = 'block';

    // save in database
    fetch('mark_order_completed.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'order_id=' + orderId
    });
}

function cancelOrder(orderId, cardId){
    let reason = document.getElementById('cancelReason-' + cardId).value;
    if(reason === ''){
        alert('Please select a reason for cancellation.');
        return;
    }

    if(!confirm('Are you sure you want to cancel this order?')){
        return;
    }

    // Send AJAX request to update DB
    fetch('cancel_order.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'order_id=' + orderId + '&reason=' + encodeURIComponent(reason)
    })
    .then(response => response.text())
    .then(data => {
        // Update UI
        document.getElementById('actions-' + cardId).style.display = 'none';
        document.getElementById('status-' + cardId).style.display = 'inline';
        document.getElementById('status-' + cardId).innerText = '(Canceled)';
        document.getElementById('completed-' + cardId).style.display = 'block';
        document.getElementById('completed-' + cardId).innerHTML = 
            '<button class="btn" style="background:#DC2626;width:100%">Canceled ❌</button>';
    });
}

</script>
</head>

<body>

<div class="header">Rider Panel – Hi, <?= htmlspecialchars($rider_name) ?></div>

<?php
$i=1;
if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){

$phone = "91".preg_replace('/\D/','',$row['phone']);

$msg_out = urlencode(
    "Hello ".$row['customer_name']."\n\n".
    "This is ".$row['rider_name']." from Cinnamon Kitchen.\n".
    "I will be arriving at your location to deliver your shipment.\n".
    "Please share your current location.\n\n".
    "https://cinnamon.kitchen/"
);


$msg_reached = urlencode(
    "Hello ".$row['customer_name']."\n".
    "This is ".$row['rider_name']." from Cinnamon Kitchen.\n".
    "I have reached your location. Please collect your order.\n\nhttps://cinnamon.kitchen/"
);

$msg_delivered = urlencode(
    "Hello ".$row['customer_name']."\n\n".
    "This is ".$row['rider_name']." from Cinnamon Kitchen.\n".
    ", your order has been DELIVERED successfully. Thank you!\nhttps://cinnamon.kitchen/"
);

$msg_cancelled = urlencode(
    "Hello ".$row['customer_name']."\n\n".
    "This is ".$row['rider_name']." from Cinnamon Kitchen.\n".
    ", your order has been Cancelled as per your concern Thank you!\nhttps://cinnamon.kitchen/"
);
?>

<div class="card">

    <div class="card-header" onclick="toggle('d<?= $i ?>')">
        <span>
            <b>
                #<?= $row['order_id'] ?>

                <!-- ADDITION: STATUS FROM DB -->
                <span id="status-<?= $i ?>"
                style="display:<?= ($row['order_status']=='completed') ? 'inline' : 'none' ?>;
                color:#16a34a;font-size:13px;">
                    (Order Completed)
                </span>
                <span id="status-cancelled-<?= $i ?>"
      style="display:<?= ($row['order_status']=='canceled' || $row['order_status']=='cancelled') ? 'inline' : 'none' ?>;
             color:#dc2626;font-size:13px;">
    (Order Cancelled)
</span>


            </b><br>
            <?= htmlspecialchars($row['customer_name']) ?>
        </span>
        <span class="badge">View</span>
    </div>

    <div class="details" id="d<?= $i ?>">
        <div><b>Phone:</b> <?= $row['phone'] ?></div>
        <div><b>Address:</b> <?= $row['address'] ?></div>
        <div><b>City:</b> <?= $row['city'] ?></div>
        <div><b>Product:</b> <?= $row['order_item'] ?></div>
        <div><b>Qty:</b> <?= $row['quantity_raw'] ?></div>
        <div><b>Price:</b> ₹<?= $row['price'] ?></div>

        <!-- ACTION BUTTONS -->
        <div id="actions-<?= $i ?>"
        style="display:<?= ($row['order_status']=='completed') ? 'none' : 'flex' ?>;
        gap:10px;margin-top:15px">

            <a href="https://wa.me/<?= $phone ?>?text=<?= $msg_out ?>" target="_blank" style="flex:1">
                <button class="btn out-for-delivery" type="button">
                    Out for Delivery
                </button>
            </a>

            <a href="https://wa.me/<?= $phone ?>?text=<?= $msg_reached ?>" target="_blank" style="flex:1">
                <button class="btn reached" type="button">Reached Location</button>
            </a>
            
            <a href="https://wa.me/<?= $phone ?>?text=<?= $msg_delivered ?>"
               target="_blank"
               onclick="markCompleted(<?= $i ?>,'<?= $row['order_id'] ?>')"
               style="flex:1">
                <button class="btn delivered" type="button">
                    Order Delivered
                </button>
            </a>

            <!-- CANCEL ORDER BUTTON & REASON -->
<div style="margin-top:10px;display:flex;gap:10px;align-items:center;flex-wrap:wrap">
    <select id="cancelReason-<?= $i ?>" style="padding:8px;border-radius:8px;font-size:13px">
        <option value="">Select reason</option>
        <option value="Customer Not Available">Customer Not Available</option>
        <option value="Wrong Address">Wrong Address</option>
        <option value="Order Refused">Order Refused</option>
        <option value="Other">Other</option>
    </select>
    <a href="https://wa.me/<?= $phone ?>?text=<?= $msg_cancelled ?>" target="_blank" style="flex:1">
    <button class="btn" 
        style="background:#DC2626;color:#fff" 
        onclick="cancelOrder('<?= $row['order_id'] ?>', <?= $i ?>)">
        Cancel Order
    </button>
</a>
</div>



        </div>

        <!-- COMPLETED BUTTON -->
        <div id="completed-<?= $i ?>"
        style="display:<?= ($row['order_status']=='completed') ? 'block' : 'none' ?>;margin-top:15px">
            <button class="btn delivered" style="background:#16a34a;width:100%">
                Order Completed ✅
            </button>
        </div>

        <!-- PROOF UPLOAD (UNCHANGED) -->
        <form action="upload_delivery_proof.php" method="POST" enctype="multipart/form-data" style="margin-top:10px">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <input type="file" name="delivery_image" required>
            <button type="submit">Upload Delivery Proof</button>
        </form>

    </div>
</div>

<?php
$i++;
}} else {
echo "<div class='no-order'>No orders assigned</div>";
}
?>
<script>function cancelOrder(orderId, cardId){
    let reason = document.getElementById('cancelReason-' + cardId).value;
    if(reason === ''){
        alert('Please select a reason for cancellation.');
        return;
    }

    if(!confirm('Are you sure you want to cancel this order?')){
        return;
    }

    fetch('cancel_order.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'order_id=' + encodeURIComponent(orderId) + '&reason=' + encodeURIComponent(reason)
    })
    .then(response => response.text())
    .then(data => {
        if(data.trim() === 'success'){
            // Update Rider Panel UI
            document.getElementById('actions-' + cardId).style.display = 'none';
            document.getElementById('status-' + cardId).style.display = 'inline';
            document.getElementById('status-' + cardId).innerText = '(Canceled)';
            document.getElementById('completed-' + cardId).style.display = 'block';
            document.getElementById('completed-' + cardId).innerHTML = 
                '<button class="btn" style="background:#DC2626;width:100%">Canceled ❌</button>';
        } else {
            alert('Failed to cancel order: ' + data);
        }
    })
    .catch(err => alert('AJAX error: ' + err));
}
</script>
</body>
</html>
