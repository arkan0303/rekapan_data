<?php include "./layout/header.php"; ?>
<?php include_once "./php/config.php"; ?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="text-center">Tambah Order</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="mt-4" id="tambah_order">
                        <div class="mb-3">
                            <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                            <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" placeholder="Masukkan nama pembeli" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" required>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty</label>
                            <input type="number" step="1" min="1" value="1" class="form-control" id="qty" name="qty" placeholder="Qty" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga (Rp)</label>
                            <input type="text" class="form-control" id="total_harga" name="total_harga" placeholder="Total harga" required>
                        </div>
                        <div class="mb-5">
                            <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" class="form-control" placeholder="Alamat pengiriman" required></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-md" id="submit_order">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-12" style="overflow-x: scroll;">
            <form class="d-flex mb-3 gap-3">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <label for="datefrom">Dari</label>
                    <input type="date" class="form-control" name="datefrom" id="datefrom" value="<?= $_GET['datefrom'] ?? date('Y-m-d', strtotime('-30 days')); ?>">
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <label for="dateto">Sampai</label>
                    <input type="date" class="form-control" name="dateto" id="dateto" value="<?= $_GET['dateto'] ?? date('Y-m-d'); ?>">
                </div>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <button class="btn btn-primary" type="submit">Filter Table</button>
                    <button class="btn btn-success" id="export">Export Excel</button>
                </div>
            </form>
            <table class="table table-hover" id="table_export" data-excel-name="Rekapan data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal Order</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (isset($_GET['datefrom']) && isset($_GET['dateto'])) {
                            $sql = "SELECT * FROM orders WHERE tanggal_order >= DATE_FORMAT('$_GET[datefrom]', '%Y-%m-%d') AND tanggal_order <= DATE_FORMAT('$_GET[dateto]', '%Y-%m-%d') ORDER BY tanggal_order DESC";
                        } else {
                            $dateFrom = date('Y-m-d', strtotime('-30 days'));
                            $dateTo = date('Y-m-d');
                            $sql = "SELECT * FROM orders WHERE tanggal_order >= '$dateFrom' AND tanggal_order <= '$dateTo' ORDER BY tanggal_order DESC";
                        }
                        $query = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($query)) {
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td>" . $row['nama_pembeli'] . "</td>";
                                echo "<td>" . date("d//m/Y", strtotime($row['tanggal_order'])) . "</td>";
                                echo "<td>" . $row['nama_produk'] . "</td>";
                                echo "<td>" . $row['qty'] . "</td>";
                                echo "<td> Rp. " . number_format($row['total_harga'], 0, ',', ',') . "</td>";
                                echo "<td>" . $row['alamat_pengiriman'] . "</td>";
                                echo "<tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='8' align='center'>Belum ada data</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="./assets/js/table2excel.js"></script>
<script src="./js/formOrder.js"></script>
<script src="./js/tambahOrder.js"></script>
<script src="./js/exportExcel.js"></script>
<?php include "./layout/footer.php" ?>