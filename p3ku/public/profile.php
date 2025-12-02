<?php
require_once __DIR__ . '/../include/auth.php'; 
require_once __DIR__ . '/../config/db.php';      

// Use the authenticated user's id from session
$id = $_SESSION['user_id'];

// Fetch current user data from users table
$q = $conn->prepare("SELECT full_name, username, email, password_hash FROM users WHERE user_id = ?");
$q->bind_param("i", $id);
$q->execute();
$q->bind_result($full_name, $username, $email, $dbPass);
$q->fetch();
$q->close();

$pw_error = '';

if (isset($_POST['saveProfile'])) {
    $n = trim($_POST['name']);
    $e = trim($_POST['email']);

    $u = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE user_id = ?");
    $u->bind_param("ssi", $n, $e, $id);
    $u->execute();
    $u->close();

    $full_name = $n;
    $email = $e;
}

if (isset($_POST['savePassword'])) {
    if (!empty($dbPass) && password_verify($_POST['old'], $dbPass)) {
        $newHash = password_hash($_POST['new'], PASSWORD_DEFAULT);
        $p = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $p->bind_param("si", $newHash, $id);
        $p->execute();
        $p->close();
    } else {
        $pw_error = 'Old password is incorrect.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="main">
        <h2>My Profile</h2>

        <form method="POST" class="card">
            <input name="name" value="<?= htmlspecialchars($full_name) ?>" required><br><br>
            <input name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>
            <button name="saveProfile">Save</button>
        </form>

        <hr>

        <form method="POST" class="card">
            <?php if (!empty($pw_error)): ?>
                <p style="color:red;"><?= htmlspecialchars($pw_error) ?></p>
            <?php endif; ?>
            <input type="password" name="old" placeholder="Old Password" required><br><br>
            <input type="password" name="new" placeholder="New Password" required><br><br>
            <button name="savePassword">Change Password</button>
        </form>
    </div>

</body>

</html>