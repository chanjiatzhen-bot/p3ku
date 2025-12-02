<?php
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../config/db.php';

// Handle actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    $stmt = null;

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE registrations SET status='Approved' WHERE id=?");
        $_SESSION['message'] = 'Request approved successfully!';
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE registrations SET status='Rejected' WHERE id=?");
        $_SESSION['message'] = 'Request rejected.';
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM registrations WHERE id=?");
        $_SESSION['message'] = 'Request deleted successfully!';
    }

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: manage_requests.php");
    exit;
}

// Fetch all registrations
$result = $conn->query("SELECT * FROM registrations ORDER BY visitDate ASC");
$rows = $result->fetch_all(MYSQLI_ASSOC);
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Visit Requests</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Manage School Visit Requests</h1>
            <p>Review and manage all submitted school visit requests</p>
        </div>

        <div class="controls">
            <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
            <span class="request-count">Total Requests: <?= count($rows) ?></span>
        </div>

        <?php if ($message): ?>
            <div class="success-message flash-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="table-wrapper">
            <?php if ($rows): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Visit Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row):
                            $statusClass = 'status-' . strtolower($row['status']);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['schoolName']) ?></td>
                                <td><?= htmlspecialchars($row['teacherName']) ?></td>
                                <td><?= htmlspecialchars($row['teacherEmail']) ?></td>
                                <td><?= htmlspecialchars($row['teacherPhone']) ?></td>
                                <td><?= date('M d, Y', strtotime($row['visitDate'])) ?></td>
                                <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                                <td>
                                    <div class="actions">
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <a href="?action=approve&id=<?= $row['id'] ?>" class="btn-action btn-approve">✓ Approve</a>
                                            <a href="?action=reject&id=<?= $row['id'] ?>" class="btn-action btn-reject">✗ Reject</a>
                                        <?php endif; ?>
                                        <a href="?action=delete&id=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete this registration?')">🗑 Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3>No Requests Found</h3>
                    <p>There are currently no school visit requests to manage.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>