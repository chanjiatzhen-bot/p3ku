<?php
require_once __DIR__ . '/../include/auth.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - P3KU</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="logo">P3KU Staff Portal</div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manage_requests.php">Manage Visit Requests</a></li>
        <li><a href="calendar.php">Visit Calendar</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="../include/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<!-- Main Dashboard -->
<div class="main">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Role: <?= htmlspecialchars($_SESSION['role']) ?></p>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Pending Requests</h3>
            <p>Click to review submitted school visits</p>
            <a href="manage_requests.php" class="btn">View Requests</a>
        </div>

        <div class="card">
            <h3>Visit Calendar</h3>
            <p>View upcoming scheduled visits</p>
            <a href="calendar.php" class="btn">View Calendar</a>
        </div>

        <div class="card">
            <h3>Reports</h3>
            <p>Monthly visit overview</p>
            <a href="reports.php" class="btn">View Reports</a>
        </div>
    </div>
</div>

</body>
</html>
