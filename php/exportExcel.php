<?php
session_start();
ob_start(); // Start output buffering

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
require "../vendor/autoload.php";

$unique_id = $_SESSION['unique_id'] ?? null;
if (!$unique_id) {
    header("location: login.php");
    exit;
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Header
$sheet->setCellValue('A1', 'Nama Pembeli');
$sheet->setCellValue('B1', 'Tanggal Pembelian');
$sheet->setCellValue('C1', 'Nama Produk');
$sheet->setCellValue('D1', 'Kuantitas');
$sheet->setCellValue('E1', 'Total Harga');

// Make header font bold
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
];
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Execute query and handle errors
$query = "SELECT c.nama AS customer_name, o.tanggal_order, p.nama_produk, op.qty, o.total_harga
          FROM orders o
          JOIN customers c ON o.customer_id = c.id
          JOIN order_products op ON o.id = op.order_id
          JOIN products p ON op.product_id = p.id";

$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error); // Handle query failure
}

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$rowNumber = 2; // Start from the second row
foreach ($data as $row) {
    $formattedPrice = 'Rp ' . number_format($row['total_harga'], 0, ',', '.');

    $sheet->setCellValue('A' . $rowNumber, $row['customer_name']);
    $sheet->setCellValue('B' . $rowNumber, $row['tanggal_order']);
    $sheet->setCellValue('C' . $rowNumber, $row['nama_produk']);
    $sheet->setCellValue('D' . $rowNumber, $row['qty']);
    $sheet->setCellValue('E' . $rowNumber, $formattedPrice);

    $rowNumber++;
}

// Auto size columns based on content
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Get the current date in ddMMyyyy format
$currentDate = date('dmY');

// Create the filename
$filename = "Rekapan_data_cv_prima_multimedia_" . $currentDate . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

ob_end_flush(); // End output buffering and send output
