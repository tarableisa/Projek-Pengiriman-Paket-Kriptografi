<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php'; // Include your database connection file

$username = $_SESSION['username'];

function encryptFile($filePath, $key) {
    $contents = file_get_contents($filePath);
    $iv = random_bytes(16); // Generate a random IV
    $encrypted = openssl_encrypt($contents, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return $iv . $encrypted; // Prepend IV to the encrypted data
}

function decryptFile($filePath, $key) {
    $contents = file_get_contents($filePath);
    $iv = substr($contents, 0, 16); // Extract IV from the data
    $encryptedData = substr($contents, 16); // Extract the encrypted content
    $decrypted = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return $decrypted;
}

$encryptedDir = 'uploads/encrypted/';
$decryptedDir = 'uploads/decrypted/';

// Check if the directories exist, if not, create them
if (!is_dir($encryptedDir)) {
    mkdir($encryptedDir, 0777, true);  // Creates the directory with full permissions
}

if (!is_dir($decryptedDir)) {
    mkdir($decryptedDir, 0777, true);  // Creates the directory with full permissions
}

if (isset($_POST['encrypt'])) {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = basename($file['name']);
        $fileTmpName = $file['tmp_name'];
        
        // Generate a random encryption key (store securely in database)
        $encryptionKey = bin2hex(random_bytes(16));  // Generate a secure key

        // Encrypt the file
        $encryptedData = encryptFile($fileTmpName, $encryptionKey);

        // Save the encrypted file (in a secure location)
        $encryptedFileName = "encrypted_" . $fileName;
        $encryptedFilePath = $encryptedDir . $encryptedFileName;
        file_put_contents($encryptedFilePath, $encryptedData, LOCK_EX);

        // Insert file metadata into database
        $stmt = $pdo->prepare("INSERT INTO encrypted_files (original_name, encrypted_name, file_path, encryption_key) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fileName, $encryptedFileName, $encryptedFilePath, $encryptionKey]);

        echo "File successfully encrypted and uploaded!";
    } else {
        echo "No file uploaded!";
    }
} elseif (isset($_POST['decrypt'])) {
    if (isset($_FILES['encrypted_file'])) {
        $file = $_FILES['encrypted_file'];
        $fileName = basename($file['name']);
        $fileTmpName = $file['tmp_name'];

        // Get encryption key from database (you can add a check to match the user)
        $stmt = $pdo->prepare("SELECT encryption_key FROM encrypted_files WHERE encrypted_name = ?");
        $stmt->execute([$fileName]);
        $row = $stmt->fetch();

        if ($row) {
            $encryptionKey = $row['encryption_key'];

            // Decrypt the file
            $decryptedData = decryptFile($fileTmpName, $encryptionKey);

            // Save the decrypted file
            $decryptedFileName = str_replace('encrypted_', '', $fileName); // Restore original file name
            $decryptedFilePath = $decryptedDir . $decryptedFileName;
            file_put_contents($decryptedFilePath, $decryptedData, LOCK_EX);

            // Send the file back to the user for download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $decryptedFileName . '"');
            header('Content-Length: ' . filesize($decryptedFilePath));
            readfile($decryptedFilePath);
            exit;
        } else {
            echo "Encryption key not found in the database!";
        }
    } else {
        echo "No encrypted file uploaded!";
    }
}
?>
