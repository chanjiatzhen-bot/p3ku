<?php
// Database connection settings
$host = "localhost";
$user = "root";
$pass = "";
$db   = "p3ku";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    echo "Database connection failed!";
    exit;
}

// Validate and collect form data
if (!isset($_POST['schoolName'], $_POST['address'], $_POST['teacherName'], $_POST['teacherEmail'], $_POST['teacherPhone'], $_POST['visitDate'], $_POST['numStudents'], $_POST['purpose'])) {
  echo("Error: Missing required form fields.");
}

$schoolName = trim($_POST['schoolName']);
$address = trim($_POST['address']);
$teacherName = trim($_POST['teacherName']);
$teacherEmail = trim($_POST['teacherEmail']);
$teacherPhone = trim($_POST['teacherPhone']);
$visitDate = $_POST['visitDate'];
$numStudents = intval($_POST['numStudents']);
$purpose = trim($_POST['purpose']);

// Use prepared statement to prevent SQL injection
$sql = "INSERT INTO registrations (schoolName, address, teacherName, teacherEmail, teacherPhone, visitDate, numStudents, purpose) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssssis", $schoolName, $address, $teacherName, $teacherEmail, $teacherPhone, $visitDate, $numStudents, $purpose);

if ($stmt->execute()) {
  $stmt->close();
  $conn->close();
  header("Location: confrimation.html");
  exit();
} else {
  echo "Error: " . $stmt->error;
  $stmt->close();
}

$conn->close();
?>