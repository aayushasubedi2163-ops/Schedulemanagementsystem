<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access denied. Admins only.");
}

include "../config/database.php";

?>
<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<header class="app-bar">

    <div class="app-bar-title">
        <span class="material-symbols-outlined">admin_panel_settings</span>
        <h1>Admin Dashboard</h1>
    </div>

    <div class="app-bar-user">
        <p>Welcome, Admin.</p>
    </div>

</header>

<nav class="app-nav">

    <a href="index.php">
        <span class="material-symbols-outlined">dashboard</span>
        Admin Home
    </a>

    <a href="users.php">
        <span class="material-symbols-outlined">group</span>
        Manage Users
    </a>

    <a href="groups.php">
        <span class="material-symbols-outlined">diversity_3</span>
        Manage Groups
    </a>

    <a href="schedules.php">
        <span class="material-symbols-outlined">event</span>
        Manage Schedules
    </a>

    <a href="tasks.php">
        <span class="material-symbols-outlined">checklist</span>
        Manage Tasks
    </a>

    <a href="../dashboard/index.php">
        <span class="material-symbols-outlined">person</span>
        User Dashboard
    </a>

    <a href="../auth/logout.php" class="nav-logout">
        <span class="material-symbols-outlined">logout</span>
        Logout
    </a>

</nav>

<div class="container">

<div class="section">

    <h2>Admin Functions</h2>

    <div class="cards">

        <a href="users.php" class="card" style="text-decoration:none;">
            <span class="material-symbols-outlined" style="font-size:26px;color:var(--md-primary);">group</span>
            <h3 style="margin-top:10px;">Users</h3>
            <p style="font-size:14px;color:var(--md-on-surface-muted);font-weight:400;">Manage accounts &amp; roles</p>
        </a>

        <a href="groups.php" class="card" style="text-decoration:none;">
            <span class="material-symbols-outlined" style="font-size:26px;color:var(--md-primary);">diversity_3</span>
            <h3 style="margin-top:10px;">Groups</h3>
            <p style="font-size:14px;color:var(--md-on-surface-muted);font-weight:400;">Manage all groups</p>
        </a>

        <a href="schedules.php" class="card" style="text-decoration:none;">
            <span class="material-symbols-outlined" style="font-size:26px;color:var(--md-primary);">event</span>
            <h3 style="margin-top:10px;">Schedules</h3>
            <p style="font-size:14px;color:var(--md-on-surface-muted);font-weight:400;">Manage all schedules</p>
        </a>

        <a href="tasks.php" class="card" style="text-decoration:none;">
            <span class="material-symbols-outlined" style="font-size:26px;color:var(--md-primary);">checklist</span>
            <h3 style="margin-top:10px;">Tasks</h3>
            <p style="font-size:14px;color:var(--md-on-surface-muted);font-weight:400;">Manage all tasks</p>
        </a>

    </div>

</div>

</div>

</body>

</html>
