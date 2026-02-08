<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_POST['username'], $_POST['password'])) {
    header("Location: ../public/login.php");
    exit();
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../public/dashboard.php");
        exit();
    }
}

header("Location: ../public/login.php?error=1");
exit();
?>