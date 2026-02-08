<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_POST['username'], $_POST['full_name'], $_POST['email'], $_POST['password'])) {
    header("Location: ../public/schoolRegister.php");
    exit();
}

$username  = trim($_POST['username']);
$full_name = trim($_POST['full_name']);
$email     = trim($_POST['email']);
$password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    header("Location: ../public/schoolRegister.php?error=username_taken");
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO users (username, password_hash, full_name, email, role) 
    VALUES (?, ?, ?, ?, 'registered_member')
");
$stmt->bind_param("ssss", $username, $password, $full_name, $email);

if ($stmt->execute()) {
    header("Location: ../public/login.php?registered=1");
} else {
    header("Location: ../public/schoolRegister.php?error=1");
}

exit();
?>
