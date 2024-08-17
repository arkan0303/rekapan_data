<?php
session_start();

include_once "config.php";

$unique_id = $_SESSION['unique_id'];
if (!isset($unique_id)) {
    header("location: login.php");
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if (!$id) {
    echo "ID produk tidak valid";
    exit;
}

// Start the transaction
mysqli_begin_transaction($conn);

// Fetch the image path
$getGambar = mysqli_query($conn, "SELECT gambar FROM products WHERE id = $id FOR UPDATE");
if (mysqli_num_rows($getGambar) < 1) {
    mysqli_rollback($conn);
    echo "ID tidak ditemukan";
    exit;
}

$rowGambar = mysqli_fetch_assoc($getGambar);

// Delete the product
$deleteQuery = mysqli_query($conn, "DELETE FROM products WHERE id = $id");
if (!$deleteQuery || mysqli_affected_rows($conn) <= 0) {
    mysqli_rollback($conn);
    echo "Gagal menghapus data.";
    exit;
}

// Attempt to delete the image file
if (!unlink($rowGambar['gambar'])) {
    mysqli_rollback($conn);
    echo "Gagal menghapus gambar.";
    exit;
}

// Commit the transaction if everything is successful
mysqli_commit($conn);
echo "Berhasil menghapus data, harap tunggu...";
exit;
