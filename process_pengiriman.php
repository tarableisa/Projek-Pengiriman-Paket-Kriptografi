<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'connection.php';
    require_once 'encryption.php';
    
    $encryption = new Encryption();
    
    try {
        // Get form data
        $nama_pengirim = $_POST['nama_pengirim'];
        $nama_penerima = $_POST['nama_penerima'];
        $alamat_penerima = $_POST['alamat_penerima'];
        $nomor_hp = $_POST['nomor_hp'];
        $pesan = $_POST['pesan'];
        
        // Validate file upload
        if (!isset($_FILES['foto_paket']) || $_FILES['foto_paket']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error uploading file");
        }
        
        // Handle file upload
        $foto_paket = file_get_contents($_FILES['foto_paket']['tmp_name']);
        
        // Encrypt data
        $encrypted_address = $encryption->superEncrypt($alamat_penerima);
        $steg_data = $encryption->hideMessage($foto_paket, $pesan);
        
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO shipping_data (sender_name, recipient_name, recipient_address, 
            recipient_address_encrypted, phone_number, package_photo, message, steg_data) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $nama_pengirim, 
            $nama_penerima, 
            $alamat_penerima, 
            $encrypted_address, 
            $nomor_hp, 
            $foto_paket, 
            $pesan, 
            $steg_data
        ]);
        
        $_SESSION['success_message'] = "Data berhasil disimpan!";
        header("Location: data_pengiriman.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
        header("Location: form_pengiriman.php");
        exit();
    }
}