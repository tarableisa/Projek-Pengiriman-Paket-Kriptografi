<?php
session_start();

// Ambil pesan yang telah didekripsi dari session
if (isset($_SESSION['decrypted_message'])) {
    $decryptedMessage = htmlspecialchars($_SESSION['decrypted_message']);
    unset($_SESSION['decrypted_message']); // Hapus dari session setelah ditampilkan
} else {
    $decryptedMessage = "Tidak ada pesan yang ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Dekripsi</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h1>Hasil Dekripsi</h1>
            <div class="form-container">
                <h2>Pesan Tersembunyi:</h2>
                <p class="message-box"><?php echo $decryptedMessage; ?></p>
                <a href="bukti_pengiriman.php" class="btn">Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>
