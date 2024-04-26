<?php 

include_once "config.php";

$nama_pembeli = mysqli_real_escape_string($conn, $_POST['nama_pembeli']);
$nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
$qty = mysqli_real_escape_string($conn, $_POST['qty']);
$total_harga = mysqli_real_escape_string($conn, $_POST['total_harga']);
$total_harga = preg_replace('/[^0-9.]/', '', $total_harga);
$alamat_pengiriman = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman']);


$insertData = mysqli_query($conn, "INSERT INTO orders (nama_pembeli, nama_produk, qty, total_harga, alamat_pengiriman, tanggal_order)
VALUES ('$nama_pembeli', '$nama_produk', $qty, $total_harga, '$alamat_pengiriman', CURDATE())");

if ($insertData) {
    return;
} else {
    echo "add yang salah";
}