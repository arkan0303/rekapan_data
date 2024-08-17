<?php $cssFiles = ["./css/dropdownSearch.css"] ?>
<?php include "./layout/header.php"; ?>

<?php
session_start();
include_once "./php/config.php";

$unique_id = $_SESSION['unique_id'];
if (!isset($unique_id)) {
    header("location: login.php");
}


?>

<div class="container mt-5">
    <div class="row mb-5">
        <div class="col">
            <div class="d-flex">
                <h4><a href="/" class="text-decoration-none text-dark">Daftar Produk ></a></h4>
                <h4><a href="/daftar-penjualan.php" class="text-decoration-none text-dark">Daftar Penjualan ></a></h4>
                <h4>Detail Penjualan</h4>
            </div>
        </div>
    </div>

    <?php if (!isset($_GET['id'])): ?>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h3 class="text-center">Id tidak ditemukan</h3>
            </div>
        </div>
    <?php else: ?>
        <div class="row justify-content-start mb-3">
            <div class="col-md-4">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Detail Penjualan</h3>

                        <?php
                        $id = $_GET['id'];
                        $queryOrder = mysqli_query($conn, "SELECT o.id, o.customer_id, c.nama AS nama_pembeli, o.tanggal_order, o.total_harga FROM orders o JOIN customers c ON o.customer_id = c.id WHERE o.id = $id");

                        $order = mysqli_fetch_assoc($queryOrder);
                        ?>

                        <div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Pembeli</label>
                                <div class="custom-dropdown-search">
                                    <div class="custom-input-container">
                                        <input type="text" id="customSearchInput" placeholder="Cari/Pilih..."
                                            class="custom-form-control" readonly value="<?= $order['nama_pembeli']; ?>" />
                                        <button id="customSearchBtn" class="custom-search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button id="customClearBtn" class="custom-clear-btn" style="display: block;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="customDropdownMenu" class="custom-dropdown-menu"></div>
                                </div>
                                <input type="hidden" id="customer_id">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Order</label>
                                <input type="date" class="form-control"
                                    value="<?= date('Y-m-d', strtotime($order['tanggal_order'])); ?>">
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
                            <?php
                            $userQuery = mysqli_query($conn, "SELECT name, username FROM users WHERE unique_id = $unique_id");
                            $user = mysqli_fetch_assoc($userQuery);
                            ?>
                            <input type="hidden" id="auth_name" value="<?= $user['name']; ?>">
                            <input type="hidden" id="auth_username" value="<?= $user['username']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <table class="table table-light table-hover table-bordered" id="table_detail_penjualan">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Harga + PPN 11%</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $queryProducts = mysqli_query(
                                    $conn,
                                    "SELECT
                                            op.id AS order_product_id,
                                            op.order_id,
                                            p.id,
                                            p.nama_produk,
                                            p.harga,
                                            op.qty
                                        FROM
                                            order_products AS op
                                        JOIN
                                            products AS p ON op.product_id = p.id
                                        WHERE
                                            op.order_id = $id
                                "
                                )
                                    ?>
                                <?php if (mysqli_num_rows($queryProducts) < 1): ?>
                                    <tr>
                                        <td class="text-center align-middle fw-bold" colspan="5">Data tidak ditemukan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($queryProducts)): ?>
                                        <tr>
                                            <td class="text-center align-middle" data-column="no"><?= $no++; ?></td>
                                            <td class="text-center align-middle" data-column="nama_produk">
                                                <?= $row['nama_produk']; ?>
                                            </td>
                                            <td class="text-center align-middle" data-column="harga">
                                                <?= 'Rp ' . number_format($row['harga'], 0, ',', ','); ?>
                                            </td>
                                            <td class="text-center align-middle" data-column="hargaPpn">
                                                <?= 'Rp ' . number_format($row['harga'] + ($row['harga'] * 0.11), 0, ',', ','); ?>
                                            </td>
                                            <td class="text-center align-middle" data-column="qty"><?= $row['qty']; ?></td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-outline-danger btn-sm">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-start">Total Harga : <span
                                            id="total_harga_produk"><?= 'Rp ' . number_format($order['total_harga'], 0, ',', ','); ?></span>
                                    </td>
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
                        <button type="button" class="btn btn-primary">Ubah</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#hapusModal">Hapus</button>

                        <div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="hapusModalLabel">Apa Anda yakin?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Data yang sudah dihapus tidak dapat kembali
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-danger">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>



</div>

<script src="./js/dropdownSearch.js"></script>
<script src="./js/formPenjualan.js"></script>
<?php include "./layout/footer.php"; ?>