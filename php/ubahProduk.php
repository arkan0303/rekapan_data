<?php

include_once "config.php";

// Retrieve and sanitize input data
$id = mysqli_real_escape_string($conn, $_POST['productId']);
$namaProduk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga = mysqli_real_escape_string($conn, $_POST['harga']);
$harga = preg_replace('/[^0-9.]/', '', $harga);

$gambar = $_FILES['gambar'];
$maxFileSize = 3 * 1024 * 1024; // max : 3mb

// Start the transaction
mysqli_begin_transaction($conn);

// Check if the product exists
$getProduct = mysqli_query($conn, "SELECT gambar FROM products WHERE id = '$id' FOR UPDATE");
if (mysqli_num_rows($getProduct) < 1) {
    mysqli_rollback($conn);
    echo "Produk tidak ditemukan.";
    exit;
}

$row = mysqli_fetch_assoc($getProduct);
$oldImagePath = $row['gambar'];

$newImageUploaded = isset($gambar) && $gambar['size'] > 0;

if ($newImageUploaded) {
    // Validate the image
    if ($gambar['size'] > $maxFileSize) {
        mysqli_rollback($conn);
        echo "Ukuran Gambar harus dibawah dari 3mb";
        exit;
    }

    // Set up the upload directory
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique filename for the new image
    $fileExt = pathinfo($gambar['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . microtime(true) . '.' . $fileExt;
    $uploadPath = $uploadDir . $filename;

    // Upload the new image
    if (!move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
        mysqli_rollback($conn);
        echo "Gagal upload gambar.";
        exit;
    }

    // Update the product with the new image path
    $sql = "UPDATE products SET nama_produk = '$namaProduk', deskripsi = '$deskripsi', gambar = '$uploadPath', harga = '$harga', ppn = 11 WHERE id = '$id'";
} else {
    // Update the product without changing the image
    $sql = "UPDATE products SET nama_produk = '$namaProduk', deskripsi = '$deskripsi', harga = '$harga', ppn = 11 WHERE id = '$id'";
}

$queryUpdate = mysqli_query($conn, $sql);

if ($queryUpdate) {
    // If a new image was uploaded, delete the old image
    if ($newImageUploaded && $oldImagePath && file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }

    // Commit the transaction
    mysqli_commit($conn);
    echo "Berhasil mengupdate produk, harap tunggu...";
} else {
    // If the update fails, rollback and delete the new image if uploaded
    if ($newImageUploaded && file_exists($uploadPath)) {
        unlink($uploadPath);
    }
    mysqli_rollback($conn);
    echo "Gagal mengupdate produk.";
}
exit;

