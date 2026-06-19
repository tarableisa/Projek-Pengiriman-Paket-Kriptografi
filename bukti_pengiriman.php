<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if (isset($_POST['upload'])) {
    // Proses Penyisipan Pesan
    $image = $_FILES['image']['tmp_name'];
    $message = $_POST['message'];

    // Tambahkan karakter null (\0) sebagai penanda akhir pesan
    $message .= "\0";

    $outputImage = 'output_' . uniqid() . '.png';

    $imageResource = imagecreatefrompng($image);
    $width = imagesx($imageResource);
    $height = imagesy($imageResource);

    $messageLength = strlen($message);

    if ($messageLength * 8 > $width * $height) {
        die("Pesan terlalu panjang untuk dimasukkan ke dalam gambar ini!");
    }

    // Sisipkan pesan ke LSB
    $messageIndex = 0;
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($messageIndex >= $messageLength * 8) break;

            $rgb = imagecolorat($imageResource, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // Ambil bit pesan sesuai indeks
            $bit = (ord($message[$messageIndex >> 3]) >> (7 - ($messageIndex % 8))) & 1;

            // Sisipkan bit pesan ke LSB dari blue channel
            $b = ($b & 0xFE) | $bit;

            // Set warna pixel baru
            $newColor = imagecolorallocate($imageResource, $r, $g, $b);
            imagesetpixel($imageResource, $x, $y, $newColor);

            $messageIndex++;
        }
    }

    // Simpan gambar yang telah disisipkan pesan
    imagepng($imageResource, $outputImage);
    imagedestroy($imageResource);

    echo "Pesan berhasil disisipkan! Gambar disimpan sebagai $outputImage.";
} elseif (isset($_POST['decrypt'])) {
    // Proses Ekstraksi Pesan
    $image = $_FILES['image']['tmp_name'];

    $imageResource = imagecreatefrompng($image);
    $width = imagesx($imageResource);
    $height = imagesy($imageResource);

    $messageBits = [];
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($imageResource, $x, $y);
            $b = $rgb & 0xFF;

            // Ambil LSB dari blue channel
            $messageBits[] = $b & 1;
        }
    }
    imagedestroy($imageResource);

    // Konversi bit-bit menjadi karakter
    $binaryMessage = '';
    foreach ($messageBits as $bit) {
        $binaryMessage .= $bit;
    }

    $message = '';
    for ($i = 0; $i < strlen($binaryMessage); $i += 8) {
        $byte = substr($binaryMessage, $i, 8);
        $char = chr(bindec($byte));

        // Hentikan jika mencapai karakter null (\0)
        if ($char === "\0") {
            break;
        }

        $message .= $char;
    }

    // Simpan hasil dekripsi ke session
    $_SESSION['decrypted_message'] = $message;

    // Redirect ke hasil dekripsi
    header("Location: decrypt_result.php");
    exit();
}
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
                <li><a href="file.php">File</a></li>
                <li class="active"><a href="bukti_pengiriman.php">Bukti Pengiriman</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Bukti Pengiriman</h1>
            <div class="form-container">
                <h2>Enkripsi</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Upload Gambar:</label>
                        <input type="file" name="image" id="image" accept="image/png" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan Teks:</label>
                        <textarea name="message" id="message" rows="4" placeholder="Masukkan pesan yang ingin dirahasiakan" required></textarea>
                    </div>
                    <button type="submit" name="upload" class="btn">Sisipkan Pesan</button>
                </form>
            </div>

            <div class="form-container">
                <h2>Dekripsi</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Upload Gambar:</label>
                        <input type="file" name="image" id="image" accept="image/png" required>
                    </div>
                    <button type="submit" name="decrypt" class="btn">Ekstrak Pesan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
