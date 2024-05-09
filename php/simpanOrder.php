<?php

include_once "./config.php";

$data = json_decode(file_get_contents('php://input'), true);

mysqli_begin_transaction($conn);

$namaPembeli = $data['namaPembeli'];
$tanggalOrder = $data['tanggalOrder'];
$totalHarga = $data['totalHarga'];

$insertOrderSql = "INSERT INTO orders (nama_pembeli, tanggal_order, total_harga) VALUE (?, ?, ?)";
$orderStmt = mysqli_prepare($conn, $insertOrderSql);
mysqli_stmt_bind_param($orderStmt, "sss", $namaPembeli, $tanggalOrder, $totalHarga);

$orderExec = mysqli_stmt_execute($orderStmt);

if (!$orderExec) {
    mysqli_rollback($conn);
    echo "Gagal menyimpan data.";
    return;
}

$orderId = mysqli_insert_id($conn);

$insertProductSql = "INSERT INTO order_products (order_id, product_id, qty) VALUES (?, ?, ?)";
$productStmt = mysqli_prepare($conn, $insertProductSql);

foreach ($data['products'] as $product) {
    $productId = $product['productId'];
    $qty = $product['productQty'];
    mysqli_stmt_bind_param($productStmt, "iii", $orderId, $productId, $qty);
    $productExec = mysqli_stmt_execute($productStmt);

    if (!$productExec) {
        mysqli_rollback($conn);
        echo "Gagal menyimpan data.";
        return;
    }
}

mysqli_commit($conn);
echo "Berhasil menyimpan data, harap tunggu...";
return;