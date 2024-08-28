<?php

include_once "./config.php";

$data = json_decode(file_get_contents('php://input'), true);

mysqli_begin_transaction($conn);

$namaPembeli = $data['namaPembeli'];
$tanggalOrder = $data['tanggalOrder'];
$totalHarga = $data['totalHarga'];
$idPembeli = $data['idPembeli'];
$orderId = isset($data['orderId']) ? $data['orderId'] : null;

if ($orderId) {
    // Check if the order exists for the customer
    $getOrder = mysqli_prepare($conn, "SELECT id FROM orders WHERE id = ? AND customer_id = ?");
    mysqli_stmt_bind_param($getOrder, "ii", $orderId, $idPembeli);

    $orderExec = mysqli_stmt_execute($getOrder);
    if (!$orderExec) {
        mysqli_rollback($conn);
        echo "Gagal mengupdate data.";
        exit;
    }

    // Store the result to be able to use mysqli_stmt_num_rows
    mysqli_stmt_store_result($getOrder);

    if (mysqli_stmt_num_rows($getOrder) < 1) {
        mysqli_rollback($conn);
        echo "Gagal mengupdate data, pesanan tidak ditemukan.";
        exit;
    }

    // Close the order statement since it's no longer needed
    mysqli_stmt_close($getOrder);

    // Update the order details
    $updateOrderSql = "UPDATE orders SET tanggal_order = ?, total_harga = ? WHERE id = ?";
    $orderStmt = mysqli_prepare($conn, $updateOrderSql);
    mysqli_stmt_bind_param($orderStmt, "ssi", $tanggalOrder, $totalHarga, $orderId);

    $orderExec = mysqli_stmt_execute($orderStmt);

    if (!$orderExec) {
        mysqli_rollback($conn);
        echo "Gagal mengupdate data.";
        exit;
    }

    // Update customer name
    $updateCustomerSql = "UPDATE customers SET nama = ? WHERE id = ?";
    $customerStmt = mysqli_prepare($conn, $updateCustomerSql);
    mysqli_stmt_bind_param($customerStmt, "si", $namaPembeli, $idPembeli);
    $customerExec = mysqli_stmt_execute($customerStmt);

    if (!$customerExec) {
        mysqli_rollback($conn);
        echo "Gagal mengupdate nama konsumen.";
        exit;
    }

    // Delete old products
    $deleteProducts = mysqli_prepare($conn, "DELETE FROM order_products WHERE order_id = ?");
    mysqli_stmt_bind_param($deleteProducts, "i", $orderId);
    $deleteProductsExec = mysqli_stmt_execute($deleteProducts);
    if (!$deleteProductsExec) {
        mysqli_rollback($conn);
        echo "Gagal mengupdate data";
        exit;
    }

    // Insert new products
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

    // Commit the transaction
    mysqli_commit($conn);
    echo "Berhasil mengupdate data.";
} else {
    // Handle the case where no orderId is provided (could be a new insert)
    echo "Gagal mengupdate data, orderId tidak ditemukan.";
}

exit;
