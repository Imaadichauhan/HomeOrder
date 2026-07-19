<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Secure Login Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* ======================================================
   ENTERPRISE DARK GLASS UI — PROFESSIONAL GRADE
====================================================== */

/* ---------- RESET ---------- */
* {
    box-sizing: border-box;
    font-family: "Inter", "Segoe UI", system-ui, Arial, sans-serif;
}

/* ---------- BACKGROUND ---------- */
body {
    margin: 0;
    min-height: 100vh;
    background:
        radial-gradient(900px 400px at 10% -10%, rgba(79,70,229,0.12), transparent 60%),
        radial-gradient(700px 400px at 90% 110%, rgba(14,165,233,0.10), transparent 60%),
        linear-gradient(180deg, #020617, #020617);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e5e7eb;
}

/* ---------- MAIN GLASS CONTAINER ---------- */
.container {
    width: 92%;
    max-width: 900px;
    padding: 46px;
    border-radius: 20px;

    background: rgba(8, 12, 20, 0.78);
    backdrop-filter: blur(28px);
    -webkit-backdrop-filter: blur(28px);

    border: 1px solid rgba(255, 255, 255, 0.06);

    box-shadow:
        0 40px 120px rgba(0, 0, 0, 0.85),
        inset 0 1px 0 rgba(255, 255, 255, 0.04);
}

/* ---------- HEADINGS ---------- */
.title {
    text-align: center;
    font-size: 30px;
    font-weight: 600;
    letter-spacing: -0.4px;
    color: #f9fafb;
    margin-bottom: 6px;
}

.subtitle {
    text-align: center;
    font-size: 14px;
    color: #9ca3af;
    margin-bottom: 42px;
}

/* ---------- CARD GRID ---------- */
.cards {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
}

/* ---------- GLASS CARDS ---------- */
.card {
    flex: 1;
    min-width: 280px;
    padding: 42px 34px;
    border-radius: 18px;
    text-align: center;

    background: linear-gradient(
        180deg,
        rgba(15, 23, 42, 0.78),
        rgba(2, 6, 23, 0.78)
    );

    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);

    border: 1px solid rgba(255, 255, 255, 0.07);

    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease,
        border-color 0.35s ease;
}

.card:hover {
    transform: translateY(-10px);
    border-color: rgba(255, 255, 255, 0.16);
    box-shadow:
        0 35px 90px rgba(0, 0, 0, 0.9),
        inset 0 0 0 rgba(255,255,255,0);
}

/* ---------- ICON ---------- */
.icon {
    width: 76px;
    height: 76px;
    margin: 0 auto 22px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 34px;

    background: radial-gradient(
        circle at 30% 30%,
        rgba(255,255,255,0.16),
        rgba(255,255,255,0.04)
    );

    border: 1px solid rgba(255,255,255,0.12);
}

/* ROLE ACCENTS */
.admin .icon {
    color: #a5b4fc;
    box-shadow: 0 0 22px rgba(99,102,241,0.25);
}

.rider .icon {
    color: #7dd3fc;
    box-shadow: 0 0 22px rgba(56,189,248,0.22);
}

/* ---------- TEXT ---------- */
.card h2 {
    font-size: 20px;
    font-weight: 600;
    color: #f3f4f6;
    margin-bottom: 12px;
}

.card p {
    font-size: 14px;
    color: #9ca3af;
    margin-bottom: 30px;
    line-height: 1.6;
}

/* ---------- BUTTONS ---------- */
.card a {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 14px 34px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;

    border-radius: 12px;
    color: #f9fafb;

    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.12),
        rgba(255,255,255,0.02)
    );

    border: 1px solid rgba(255,255,255,0.14);

    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    transition:
        background 0.3s ease,
        box-shadow 0.3s ease,
        transform 0.2s ease;
}

/* ADMIN BUTTON */
.admin a:hover {
    transform: translateY(-1px);
    background: rgba(99,102,241,0.22);
    box-shadow: 0 0 28px rgba(99,102,241,0.45);
}

/* RIDER BUTTON */
.rider a:hover {
    transform: translateY(-1px);
    background: rgba(56,189,248,0.22);
    box-shadow: 0 0 28px rgba(56,189,248,0.45);
}

/* ---------- FOOTER ---------- */
.footer-note {
    margin-top: 42px;
    text-align: center;
    font-size: 12px;
    color: #6b7280;
    letter-spacing: 0.3px;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
    .cards {
        flex-direction: column;
    }

    .container {
        padding: 34px;
    }
}
/* ==========================================
   ANIMATED GLASS BORDER SHINE
========================================== */

.card {
    position: relative;
    overflow: hidden;
}

/* SHINE BORDER */
.card::before {
    content: "";
    position: absolute;
    inset: 0;
    padding: 1.2px; /* border thickness */

    border-radius: inherit;

    background: linear-gradient(
        120deg,
        transparent 30%,
        rgba(255,255,255,0.35),
        rgba(99,102,241,0.55),
        rgba(56,189,248,0.45),
        rgba(255,255,255,0.35),
        transparent 70%
    );

    background-size: 300% 300%;
    animation: glassBorder 8s linear infinite;

    /* MASK — only border visible */
    -webkit-mask:
        linear-gradient(#000 0 0) content-box,
        linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;

    pointer-events: none;
}

/* BORDER ANIMATION */
@keyframes glassBorder {
    0% {
        background-position: 0% 50%;
    }
    100% {
        background-position: 300% 50%;
    }
}

</style>
</head>

<body>

<div class="container">
    <div class="title">Login Portal</div>
    <div class="subtitle">Choose your role to securely access the system</div>

    <div class="cards">

        <!-- Admin -->
        <div class="card admin">
            <div class="icon">🛡️</div>
            <h2>Admin Dashboard</h2>
            <p>Manage orders, users, reports, and overall system operations.</p>
            <a href="login.php">Login as Admin</a>
        </div>

        <!-- Rider -->
        <div class="card rider">
            <div class="icon">🛵</div>
            <h2>Rider Panel</h2>
            <p>View assigned deliveries, update status, and track progress.</p>
            <a href="rider_login.php">Login as Rider</a>
        </div>

    </div>

    <div class="footer-note">
        © 2026 TCKI-RIDER'S. All rights reserved.
    </div>
</div>

</body>
</html>
