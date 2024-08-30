<?php

$conn = mysqli_connect("localhost", "root", "mysql1412", "rekapan_data_db");

if (!$conn) {
    echo "Database error : " . mysqli_connect_error();
}