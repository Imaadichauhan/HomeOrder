<?php
session_start();
include 'db.php';

// SAFE VARIABLES (NO WARNING)
$fname = $_POST['fname'] ?? '';
$password = $_POST['password'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($fname != '' && $password != '') {

        $sql = "SELECT * FROM riders WHERE fname = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $fname);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $rider = $res->fetch_assoc();

            if (password_verify($password, $rider['password'])) {
                $_SESSION['rider_name'] = $rider['fname'];
                $_SESSION['emp_id']     = $rider['emp_id'];

                header("Location: rider_panel.php");
                exit();
            } else {
                $error = "Invalid Login Details";
            }
        } else {
            $error = "Invalid Login Details";
        }

    } else {
        $error = "All fields required";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Rider Login</title>
<style>
/* ===============================
   DARK GLASS RIDER LOGIN
=============================== */

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
}

/* ---------- BACKGROUND ---------- */
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(800px 400px at 10% -10%, rgba(79,70,229,0.14), transparent 60%),
        radial-gradient(700px 400px at 90% 110%, rgba(56,189,248,0.12), transparent 60%),
        linear-gradient(180deg, #020617, #020617);
    color: #e5e7eb;
}

/* ---------- GLASS FORM ---------- */
form {
    width: 380px;
    padding: 38px 34px;
    border-radius: 18px;
    position: relative;
    overflow: hidden;

    background: rgba(8, 12, 20, 0.78);
    backdrop-filter: blur(26px);
    -webkit-backdrop-filter: blur(26px);

    border: 1px solid rgba(255,255,255,0.08);

    box-shadow:
        0 35px 100px rgba(0,0,0,0.85),
        inset 0 1px 0 rgba(255,255,255,0.04);
}

/* ---------- ANIMATED GLASS BORDER ---------- */
form::before {
    content: "";
    position: absolute;
    inset: 0;
    padding: 1.3px;
    border-radius: inherit;

    background: linear-gradient(
        120deg,
        transparent 30%,
        rgba(255,255,255,0.35),
        rgba(99,102,241,0.6),
        rgba(56,189,248,0.45),
        rgba(255,255,255,0.35),
        transparent 70%
    );

    background-size: 300% 300%;
    animation: glassBorder 8s linear infinite;

    -webkit-mask:
        linear-gradient(#000 0 0) content-box,
        linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;

    pointer-events: none;
}

@keyframes glassBorder {
    0% { background-position: 0% 50%; }
    100% { background-position: 300% 50%; }
}

/* ---------- TITLE ---------- */
form h3 {
    text-align: center;
    font-size: 22px;
    font-weight: 600;
    color: #f9fafb;
    margin-bottom: 26px;
}

/* ---------- INPUTS ---------- */
input {
    width: 100%;
    padding: 14px 16px;
    margin-bottom: 16px;
    border-radius: 12px;

    border: 1px solid rgba(255,255,255,0.14);
    background: rgba(255,255,255,0.05);

    font-size: 14px;
    color: #f9fafb;
    outline: none;

    backdrop-filter: blur(12px);
    transition: all 0.25s ease;
}

input::placeholder {
    color: rgba(255,255,255,0.6);
}

input:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.25);
}

/* ---------- BUTTON ---------- */
button {
    width: 100%;
    padding: 14px;
    margin-top: 6px;

    background: linear-gradient(
        180deg,
        rgba(124,58,237,0.55),
        rgba(124,58,237,0.35)
    );

    color: #ffffff;
    font-size: 15px;
    font-weight: 600;

    border: none;
    border-radius: 14px;
    cursor: pointer;

    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 28px rgba(124,58,237,0.55);
}

/* ---------- ERROR ---------- */
.error {
    margin-top: 16px;
    padding: 12px;
    background: rgba(254,226,226,0.18);
    color: #fecaca;
    border-radius: 10px;
    font-size: 13px;
    text-align: center;
    border: 1px solid rgba(239,68,68,0.35);
}
</style>

</head>
<body>

<form method="POST" action="">
    <h3>Rider Login</h3>

    <input type="text" name="fname" placeholder="First Name" value="<?= htmlspecialchars($fname) ?>" required>
    <input type="password" name="password" placeholder="Password" required> <!-- Updated to password field -->

    <button type="submit">Login</button>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
</form>

</body>
</html>
