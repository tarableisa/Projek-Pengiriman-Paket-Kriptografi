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
    <title>Data Pengiriman</title>
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
                <li class="active"><a href="data_pengiriman.php">Data Pengiriman</a></li>
                <li><a href="decrypt_data.php">Decrypt Data</a></li>
                <li><a href="file.php">File</a></li>
                  <li><a href="bukti_pengiriman.php">Bukti Pengiriman</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2>ENKRIPSI DATA</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengirim</th>
                            <th>Nama Penerima</th>
                            <th>Alamat Penerima (Encrypted)</th>
                            <th>No. HP</th>
                            <th>Foto Paket</th>
                            <th>Pesan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'connection.php';
                        $stmt = $pdo->query("SELECT* FROM shipping_data ORDER BY id DESC");
                        $no = 1;
                        while ($row = $stmt->fetch()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['sender_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['recipient_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['recipient_address_encrypted']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['package_photo']) . "' 
                                      alt='Package Photo' style='max-width: 100px;'></td>";
                            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                            echo "<td>
                                    <a href='delete.php?id=" . $row['id'] . "' class='delete-btn' 
                                       onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>🗑️</a>
                                 </td>";
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