<?php
session_start();
if (!isset($_SESSION['username'])) {  // Correct session check
    header("Location: ogin.php");
    exit();
}
$username = $_SESSION['username'];  // Correct session variable
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengiriman</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="assets/logo.png" alt="Logo">
            </div>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>  <!-- Display username -->
            <ul class="sidebar-menu">
                <li class="active"><a href="form_pengiriman.php">Beranda</a></li>
                <li><a href="data_pengiriman.php">Data Pengiriman</a></li>
                <li><a href="decrypt_data.php">Decrypt Data</a></li>
                <li><a href="file.php">File</a></li>
                <li><a href="bukti_pengiriman.php">Bukti Pengiriman</a></li>
            </ul>
        </div>
        <div class="main-content">
            <a href="login.php" class="logout-btn">Logout</a>
            <div class="form-container">
                <h2>FORM PENGIRIMAN PAKET ANDA </h2>
                <form action="process_pengiriman.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Pengirim:</label>
                        <input type="text" name="nama_pengirim" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Penerima:</label>
                        <input type="text" name="nama_penerima" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Penerima:</label>
                        <input type="text" name="alamat_penerima" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP:</label>
                        <input type="text" name="nomor_hp" required>
                    </div>
                    <div class="form-group">
                        <label>Foto Paket:</label>
                        <input type="file" name="foto_paket" required>
                    </div>
                    <div class="form-group">
                        <label>Pesan:</label>
                        <textarea name="pesan" required></textarea>
                    </div>
                    <button type="submit" class="btn">Enkripsi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
