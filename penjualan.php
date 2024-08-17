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

$hasId = isset($_GET['id']) && !empty($_GET['id']);
?>

<div class="container my-5">
    <div class="row mb-5">
        <div class="col">
            <div class="d-flex">
                <h4><a href="/" class="text-decoration-none text-dark">Daftar Produk ></a></h4>
                <h4><a href="daftar-penjualan.php" class="text-decoration-none text-dark">Daftar Penjualan ></a></h4>
                <?php if ($hasId): ?>
                    <h4>Detail Penjualan</h4>
                <?php else: ?>
                    <h4>Tambah Data Penjualan</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($hasId): ?>
        <?php $orderId = $_GET['id'] ?>
        <?php $oldOrderQuery = mysqli_query($conn, "SELECT o.id, o.customer_id, c.nama AS nama_pembeli, o.tanggal_order, o.total_harga FROM orders o JOIN customers c ON o.customer_id = c.id WHERE o.id = $orderId") ?>
        <?php if (mysqli_num_rows($oldOrderQuery) < 1): ?>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h3 class="text-center">Id tidak ditemukan</h3>
                </div>
            </div>
        <?php else: ?>
            <?php $order = mysqli_fetch_assoc($oldOrderQuery) ?>
        <?php endif; ?>
    <?php endif; ?>
    <form action="" method="POST" autocomplete="off" id="form_penjualan">
        <div class="row justify-content-start mb-3">
            <div class="col-md-4">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <div class="alert d-none" role="alert" id="status_message"></div>
                        <h3 class="text-center">Form Penjualan</h3>
                        <div>
                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli <span
                                        class="text-danger">*</span></label>
                                <div class="custom-dropdown-search">
                                    <div class="custom-input-container">
                                        <input type="text" id="customSearchInput" placeholder="Cari/Pilih..."
                                            class="custom-form-control"
                                            value="<?= $hasId ? $order['nama_pembeli'] : ""; ?>" />
                                        <button id="customSearchBtn" class="custom-search-btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button id="customClearBtn" class="custom-clear-btn"
                                            style="display: <?= $hasId ? "block" : "none"; ?>;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="customDropdownMenu" class="custom-dropdown-menu"></div>
                                </div>

                                <input type="hidden" id="customer_id"
                                    value="<?= $hasId ? $order['customer_id'] : ""; ?>">

                            </div>
                            <div class="mb-3">
                                <label for="tanggal_order" class="form-label">Tanggal Order <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_order" id="tanggal_order"
                                    value="<?= $hasId ? date('Y-m-d', strtotime($order['tanggal_order'])) : date('Y-m-d'); ?>"
                                    required>
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
                                <?php if ($hasId): ?>
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
                                            op.order_id = $orderId
                                "
                                    )
                                        ?>
                                    <?php if (mysqli_num_rows($queryProducts) < 1): ?>
                                        <tr>
                                            <td class="text-center align-middle fw-bold" colspan="5">Tidak ada data.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; ?>
                                        <?php while ($row = mysqli_fetch_assoc($queryProducts)): ?>
                                            <tr>
                                                <td class="text-center align-middle" data-column="no"><?= $no++; ?></td>
                                                <td class="text-center align-middle" data-column="nama_produk">
                                                    <input type="text" class="form-control" value="<?= $row['nama_produk']; ?>"
                                                        name="nama_produk" readonly>
                                                    <input type="hidden" name="id_produk" value="<?= $row['id']; ?>">
                                                </td>
                                                <td class="text-center align-middle" data-column="hargaPpn"
                                                    data-harga="<?= $row['harga'] + ($row['harga'] * 0.11); ?>"
                                                    data-price-column="true">
                                                    <input type="text" class="form-control input_detail_harga"
                                                        value="<?= 'Rp ' . number_format($row['harga'] + ($row['harga'] * 0.11), 2, ',', ','); ?>"
                                                        readonly>
                                                </td>
                                                <td class="text-center align-middle" data-column="qty" data-qty="true">
                                                    <input type="number" name="qty_produk" class="form-control input-qty"
                                                        value="<?= $row['qty']; ?>">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button class="btn btn-outline-danger btn-sm btn-delete">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center fw-bold">Tidak ada data.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-start">Total Harga : <span
                                            id="total_harga_produk"><?= $hasId ? 'Rp ' . number_format($order['total_harga'], 0, ',', ',') : "0"; ?></span>
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
                        <button type="submit" class="btn btn-primary">
                            <?= $hasId ? "Ubah" : "Simpan" ?>
                        </button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"
    referrerpolicy="no-referrer"></script>
<script>
    const params = new URLSearchParams(window.location.search);

    let submitUrl = "";
    let orderId = "";

    if (params.has("id") && params.get("id") !== "") {
        submitUrl = "php/ubahOrder.php";
        orderId = params.get("id");
    } else {
        submitUrl = "php/simpanOrder.php";
    }
</script>
<script src="./js/formPenjualan.js" defer></script>
<script src="./js/dropdownSearch.js"></script>
<?php include_once "./layout/footer.php" ?>