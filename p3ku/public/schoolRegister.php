<!DOCTYPE html>
<html>

<head>
    <title>School Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="main">
        <h2>Register</h2>

        <form action="../include/processRegister.php" method="POST" class="card">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="text" name="full_name" placeholder="Full Name" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Register</button>
        </form>

        <a href="login.php">Back to Login</a>
    </div>

</body>

</html>
