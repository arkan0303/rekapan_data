<?php 

include_once "config.php";


$namaProduk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga = mysqli_real_escape_string($conn, $_POST['harga']);
$harga = preg_replace('/[^0-9.]/', '', $harga);

$gambar = $_FILES['gambar'];
$maxFileSize = 3 * 1024 * 1024;// max : 3mb

if (!isset($gambar)) {
    echo "Gambar tidak boleh kosong";
    return;
}

if ($gambar['size'] > $maxFileSize) {
    echo "Ukuran Gambar harus dibawah dari 3mb";
    return;
}


$uploadDir = "uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileExt = pathinfo($gambar['name'], PATHINFO_EXTENSION);
$filename = uniqid() . microtime(true) .'.'. $fileExt;
$uploadPath = $uploadDir.$filename;

if (!move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
    echo "Gagal upload gambar.";
    return;
}
$sql = "INSERT INTO products (nama_produk, deskripsi, gambar, harga, ppn) VALUES ('$namaProduk', '$deskripsi', '$uploadPath', '$harga', 11)";

$queryInsert = mysqli_query($conn, $sql);

if ($queryInsert) {
    echo "Berhasil, harap tunggu...";
    return;
} else {
    unlink($uploadPath);
    echo "Gagal menyimpan data produk";
    return;
}