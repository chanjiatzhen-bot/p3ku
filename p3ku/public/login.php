<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - P3KU Staff Portal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-page">

    <div class="login-container">
        <div class="login-header">
            <h1>🎓 P3KU</h1>
            <p>Staff Portal - Login</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> Invalid username or password
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Registration successful! Please log in.
            </div>
        <?php endif; ?>

        <form action="../include/processLogin.php" method="POST">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="schoolRegister.php">Register here</a></p>
        </div>
    </div>

</body>

</html>