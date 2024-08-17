<?php

include_once "./config.php";

$data = json_decode(file_get_contents('php://input'), true);

mysqli_begin_transaction($conn);

$namaPembeli = $data['namaPembeli'];
$tanggalOrder = $data['tanggalOrder'];
$totalHarga = $data['totalHarga'];
$idPembeli = $data['idPembeli'];

$getCustomer = mysqli_prepare($conn, "SELECT * FROM customers WHERE nama = ? AND id = ? LIMIT 1");
mysqli_stmt_bind_param($getCustomer, "si", $namaPembeli, $idPembeli);

$customerExec = mysqli_stmt_execute($getCustomer);
if (!$customerExec) {
    mysqli_rollback($conn);
    echo "Gagal menyimpan data";
    exit;
}

// Store the result to be able to use mysqli_stmt_num_rows
mysqli_stmt_store_result($getCustomer);

if (mysqli_stmt_num_rows($getCustomer) < 1) {
    mysqli_rollback($conn);
    echo "Gagal menyimpan data, data konsumen tidak ditemukan";
    exit;
}

// Close the customer statement since it's no longer needed
mysqli_stmt_close($getCustomer);

$insertOrderSql = "INSERT INTO orders (customer_id, tanggal_order, total_harga) VALUE (?, ?, ?)";
$orderStmt = mysqli_prepare($conn, $insertOrderSql);
mysqli_stmt_bind_param($orderStmt, "sss", $idPembeli, $tanggalOrder, $totalHarga);

$orderExec = mysqli_stmt_execute($orderStmt);

if (!$orderExec) {
    mysqli_rollback($conn);
    echo "Gagal menyimpan data.";
    exit;
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
        exit;
    }
}

mysqli_commit($conn);
echo "Berhasil menyimpan data, harap tunggu...";
exit;