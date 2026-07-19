<?php
session_start();

/* ===== AUTH CREDENTIALS ===== */
$ADMIN_USERNAME = "Aditya";
$ADMIN_PASSWORD = "Aditya@123";
/* ============================ */

$error = "";

if (isset($_POST['login'])) {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if ($u === $ADMIN_USERNAME && $p === $ADMIN_PASSWORD) {
        $_SESSION['admin'] = $u;
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HomeOrder Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* ======================================================
   DARK GLASS LOGIN UI — PROFESSIONAL
====================================================== */

/* ---------- RESET ---------- */
* {
    box-sizing: border-box;
    font-family: "Inter", "Segoe UI", system-ui, Arial, sans-serif;
    margin:0;
    padding:0;
}

/* ---------- BACKGROUND ---------- */
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(900px 400px at 10% -10%, rgba(79,70,229,0.12), transparent 60%),
        radial-gradient(700px 400px at 90% 110%, rgba(14,165,233,0.10), transparent 60%),
        linear-gradient(180deg, #020617, #020617);
    color: #e5e7eb;
}

/* ---------- LOGIN CONTAINER ---------- */
.login-wrapper {
    width: 92%;
    max-width: 900px;
    display: flex;
    border-radius: 20px;
    overflow: hidden;

    background: rgba(8, 12, 20, 0.78);
    backdrop-filter: blur(28px);
    -webkit-backdrop-filter: blur(28px);

    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow:
        0 40px 120px rgba(0, 0, 0, 0.85),
        inset 0 1px 0 rgba(255, 255, 255, 0.04);

    transition: transform 0.3s ease;
}

/* ---------- LEFT FORM PANEL ---------- */
.login-form {
    flex: 1;
    padding: 46px;
}

.login-form h2 {
    font-size: 30px;
    font-weight: 600;
    color: #f9fafb;
    margin-bottom: 8px;
}

.login-form p {
    font-size: 14px;
    color: #9ca3af;
    margin-bottom: 36px;
}

.form-group {
    margin-bottom: 18px;
}

input {
    width: 100%;
    padding: 14px 16px;
    font-size: 15px;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    outline: none;
    background: rgba(255,255,255,0.05);
    color: #f9fafb;
    backdrop-filter: blur(10px);
}

input::placeholder {
    color: rgba(255,255,255,0.6);
}

input:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
}

button {
    width: 100%;
    padding: 14px;
    background: rgba(124,58,237,0.4);
    color: #fff;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    backdrop-filter: blur(10px);
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

button:hover {
    background: rgba(124,58,237,0.6);
    box-shadow: 0 0 20px rgba(124,58,237,0.45);
    transform: translateY(-2px);
}

.error {
    background: rgba(254,226,226,0.2);
    color: #fee2e2;
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 15px;
}

/* ---------- RIGHT SIDE PANEL ---------- */
.login-side {
    flex: 1;
    background: linear-gradient(135deg,#7c3aed,#9333ea);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    clip-path: polygon(20% 0,100% 0,100% 100%,0 100%);
}

.login-side h1 {
    color: #fff;
    font-size: 42px;
    text-align: center;
    line-height: 1.2;
}

/* ---------- GLASS BORDER ANIMATION ---------- */
.login-wrapper {
    position: relative;
    overflow: hidden;
}

.login-wrapper::before {
    content: "";
    position: absolute;
    inset: 0;
    padding: 1.2px; /* border thickness */
    border-radius: inherit;
    background: linear-gradient(
        120deg,
        transparent 30%,
        rgba(255,255,255,0.35),
        rgba(124,58,237,0.55),
        rgba(56,189,248,0.45),
        rgba(255,255,255,0.35),
        transparent 70%
    );
    background-size: 300% 300%;
    animation: glassBorder 8s linear infinite;
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;
    pointer-events: none;
}

@keyframes glassBorder {
    0% { background-position: 0% 50%; }
    100% { background-position: 300% 50%; }
}

/* ---------- RESPONSIVE ---------- */
@media(max-width:900px){
    .login-wrapper{
        flex-direction:column;
    }
    .login-side{
        clip-path:none;
        padding:40px 20px;
        width:100%;
    }
    .login-form{
        width:100%;
        padding:40px 20px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT FORM -->
    <div class="login-form">
        <h2>Login</h2>
        <p>Enter your credentials to access HomeOrder</p>

        <?php if($error!=""){ ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button name="login">LOGIN</button>
        </form>
    </div>

    <!-- RIGHT SIDE PANEL -->
    <div class="login-side">
        <h1>Welcome<br>Back.</h1>
    </div>

</div>

</body>
</html>
