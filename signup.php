<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <form action="proses_Signup.php" method="post">
            <label for="chk" aria-hidden="true">Sign Up</label>
            <input type="text" name="username" placeholder="User name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <div class="checkbox-container">
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>
            <button type="submit">Sign up</button>
            <p class="signup-text"> Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
