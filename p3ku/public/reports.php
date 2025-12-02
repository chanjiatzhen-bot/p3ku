<?php
require_once __DIR__ . '/../include/auth.php'; 
require_once __DIR__ . '/../config/db.php';    


function detect_request_table($conn)
{
    $candidates = ['visit_requests'];
    $res = $conn->query("SHOW TABLES");
    if ($res) {
        $tables = [];
        while ($row = $res->fetch_row()) {
            $tables[] = $row[0];
        }
        foreach ($candidates as $t) {
            if (in_array($t, $tables, true)) return $t;
        }
    }
    return 'visit_request'; 
}
$request_table = detect_request_table($conn);

$status_labels = [];
$status_counts = [];

// Query visits grouped by status
$status_query = $conn->query("
    SELECT status, COUNT(*) AS count
    FROM `" . $conn->real_escape_string($request_table) . "`
    GROUP BY status
");

while ($row = $status_query->fetch_assoc()) {
    $status_labels[] = $row['status'];
    $status_counts[] = $row['count'];
}


$month_labels = ["Total Visits"];
$month_counts = [0];

$total_query = $conn->query("
    SELECT COUNT(*) AS total
    FROM visit_calendar
");

if ($total_query) {
    $row = $total_query->fetch_assoc();
    $month_counts = [$row['total']];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Reports</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 Visit Reports</h1>
            <p>Analytics and statistics for school visits</p>
        </div>

        <div class="controls">
            <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <div class="charts-wrapper">
            <div class="chart-container">
                <div class="chart-title">Visits by Status</div>
                <canvas id="statusChart"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-title">Monthly Visits</div>
                <canvas id="monthChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Status Pie Chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: <?= json_encode($status_labels) ?>,
                datasets: [{
                    data: <?= json_encode($status_counts) ?>,
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
            }
        });

        // Monthly Visits Bar Chart
        const ctxMonth = document.getElementById('monthChart').getContext('2d');
        const monthChart = new Chart(ctxMonth, {
            type: 'bar',
            data: {
                labels: <?= json_encode($month_labels) ?>,
                datasets: [{
                    label: 'Number of Visits',
                    data: <?= json_encode($month_counts) ?>,
                    backgroundColor: '#0077b6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>