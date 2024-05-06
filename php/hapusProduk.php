<?php 
session_start();

include_once "config.php";

$unique_id = $_SESSION['unique_id'];
if (!isset($unique_id)) {
    header("location: login.php");
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if (!$id) {
    echo "ID produk tidak valid";
    return;
}

$getGambar = mysqli_query($conn, "SELECT gambar FROM products WHERE id = $id");
if (mysqli_num_rows($getGambar) < 1) {
    echo "Id tidak ditemukan";
    return;
}

$deleteQuery = mysqli_query($conn, "DELETE FROM products WHERE id = $id");
if ($deleteQuery && mysqli_affected_rows($conn) > 0) {
    $rowGambar = mysqli_fetch_assoc($getGambar);
    
    unlink($rowGambar['gambar']);
    echo "Berhasil menghapus data, harap tunggu...";
    return;
} else {
    echo "Gagal menghapus data.";
    return;
}
