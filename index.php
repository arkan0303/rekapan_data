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
                    <form method="POST" class="mt-4">
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
                            <button type="submit" class="btn btn-primary btn-md">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-12" style="overflow-x: scroll;">
            <button class="btn btn-success mb-3" id="export">Export Excel</button>
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
                        $query = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
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