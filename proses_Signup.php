<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'kriptara');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM login WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='signup.php';</script>";
    } else {
        $query = "INSERT INTO login (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($query)) {
            header('Location: login.php');
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
