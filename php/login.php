<?php

session_start();
include_once "./config.php";

if (isset($_SESSION['unique_id'])) {
    header("location: /");
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($username) && !empty($password)) {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        if ($row['password'] == $password) {
            $_SESSION["unique_id"] = $row['unique_id'];
            echo "success";
            return;
        }
    }
}

echo "username / password salah.";
return;