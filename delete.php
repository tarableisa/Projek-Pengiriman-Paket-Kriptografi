<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'connection.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the DELETE query to remove the record from the database
    $stmt = $pdo->prepare("DELETE FROM shipping_data WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // After successful deletion, redirect back to the data page
        header("Location: data_pengiriman.php");
        exit();
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "No ID specified for deletion.";
}
?>
