<?php
$cssFiles = ["./css/dropdownSearch.css"]
    ?>
<?php include "./layout/header.php"; ?>

<?php
session_start();

include_once "./php/config.php";

$unique_id = $_SESSION['unique_id'];
if (!isset($unique_id)) {
    header("location: login.php");
}
?>

<div class="container my-5">
    <div class="row mb-5">
        <div class="col">
            <div class="d-flex">
                <h4><a href="/" class="text-decoration-none text-dark">Daftar Produk ></a></h4>
                <h4><a href="daftar-penjualan.php" class="text-decoration-none text-dark">Daftar Penjualan ></a></h4>
                <h4>Tambah Data Penjualan</h4>
            </div>
        </div>
    </div>

    <form action="" method="POST" autocomplete="off" id="form_penjualan">
        <div class="row justify-content-start mb-3">
            <div class="col-md-4">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <div class="alert d-none" role="alert" id="status_message"></div>
                        <h3 class="text-center">Form Tambah Penjualan</h3>
                        <div>
                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli <span
                                        class="text-danger">*</span></label>
                                <div class="custom-dropdown-search">
                                    <div class="custom-input-container">
                                        <input type="text" id="customSearchInput" placeholder="Cari/Pilih..."
                                            class="custom-form-control" />
                                        <button id="customSearchBtn" class="custom-search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button id="customClearBtn" class="custom-clear-btn" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="customDropdownMenu" class="custom-dropdown-menu"></div>
                                </div>

                                <input type="hidden" id="customer_id">

                            </div>
                            <div class="mb-3">
                                <label for="tanggal_order" class="form-label">Tanggal Order <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_order" id="tanggal_order"
                                    value="<?= date('Y-m-d'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <?php
                                $query = mysqli_query($conn, "SELECT id, nama_produk, harga FROM products ORDER BY id DESC");
                                ?>
                                <label for="product_select" class="form-label">Produk <span
                                        class="text-danger">*</span></label>
                                <select name="product_select" id="product_select" class="form-control">
                                    <option value="" selected disabled>Pilih Produk</option>
                                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                        <option value="<?= $row['id']; ?>" data-harga="<?= $row['harga']; ?>">
                                            <?= $row['nama_produk']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" id="tambah_produk">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <table class="table table-light table-hover table-bordered" id="product_table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Harga (+PPN 11%)</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center fw-bold">Tidak ada data.</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-start">Total Harga : <span
                                            id="total_harga_produk">0</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"
    referrerpolicy="no-referrer"></script>
<script src="./js/formPenjualan.js"></script>
<script src="./js/dropdownSearch.js"></script>
<?php include_once "./layout/footer.php" ?>