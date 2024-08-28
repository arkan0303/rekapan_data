<?php

$conn = mysqli_connect("localhost", "root", "", "rekapan_data_db");

if (!$conn) {
    echo "Database error : " . mysqli_connect_error();
}