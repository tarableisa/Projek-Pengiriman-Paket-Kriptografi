<?php
session_start();
if (!isset($_SESSION['username'])) {  // Correct session check
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];  // Correct session variable
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkripsi & Dekripsi File</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="assets/logo.png" alt="Logo">
            </div>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
            <ul class="sidebar-menu">
                <li><a href="form_pengiriman.php">Beranda</a></li>
                <li><a href="data_pengiriman.php">Data Pengiriman</a></li>
                <li><a href="decrypt_data.php">Decrypt Data</a></li>
                <li class="active"><a href="file.php">File</a></li>
                <li><a href="bukti_pengiriman.php">Bukti Pengiriman</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h2>ENKRIPSI & DEKRIPSI FILE</h2>
            <div class="form-container">
                <h3>Upload File untuk Enkripsi</h3>
                <form action="process_file.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih File:</label>
                        <input type="file" name="file" required>
                    </div>
                    <button type="submit" name="encrypt" class="btn">Enkripsi File</button>
                </form>

                <h3>Upload File untuk Dekripsi</h3>
                <form action="process_file.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih File Terenkripsi:</label>
                        <input type="file" name="encrypted_file" required>
                    </div>
                    <button type="submit" name="decrypt" class="btn">Dekripsi File</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
