<?php

session_start();

include_once "./config.php";

if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];

    $stmt = $conn->prepare("INSERT INTO customers (nama) VALUES (?)");
    $stmt->bind_param("s", $nama);

    $stmt->execute();

    $data = [
        "message" => "success",
        "status" => 200,
        "nama" => $nama,
        "id" => $stmt->insert_id
    ];

    echo json_encode($data);

    $stmt->close();
    $conn->close();
}