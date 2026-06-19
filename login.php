<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>
</head>
<body>
    <div class="main">
        <form action="proses_login.php" method="post">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <div class="checkbox-container">
            <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>
            <button type="submit">Login</button>
            <p class="signup-text">Don't have an account? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
