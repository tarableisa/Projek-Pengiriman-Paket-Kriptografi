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
    <title>Dekripsi Data</title>
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
                <li class="active"><a href="decrypt_data.php">Decrypt Data</a></li>
                <li><a href="file.php">File</a></li>
                  <li><a href="bukti_pengiriman.php">Bukti Pengiriman</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2>DEKRIPSI DATA</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengirim</th>
                            <th>Nama Penerima</th>
                            <th>Alamat Penerima</th>
                            <th>No. HP</th>
                            <th>Foto Paket</th>
                            <th>Pesan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'connection.php';
                        require_once 'encryption.php';
                        
                        $encryption = new Encryption();
                        $stmt = $pdo->query("SELECT * FROM shipping_data");
                        $no = 1;
                        
                        while ($row = $stmt->fetch()) {
                            $decrypted_address = $encryption->superDecrypt($row['recipient_address_encrypted']);
                            
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['sender_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['recipient_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($decrypted_address) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['package_photo']) . "' width='50'></td>";
                            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                            echo "<td><a href='delete.php?id=" . $row['id'] . "' class='delete-btn'>🗑️</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php