<?php

session_start();

include_once "./config.php";

if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];

    if (!empty($searchTerm)) {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT id, nama FROM customers WHERE nama LIKE ? ORDER BY id DESC");
        $stmt->execute(['%' . $searchTerm . '%']);
        $stmt->bind_param("s", $searchTerm);
    } else {
        // Fetch all records if no search term
        $stmt = $conn->prepare("SELECT id, nama FROM customers ORDER BY id DESC");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the results as an associative array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Output the data as JSON
        echo json_encode($data);
    } else {
        echo json_encode(["status" => "no_data", "query" => $searchTerm]);
    }


    $stmt->close();
    $conn->close();
}
?>