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
                <?php if ($hasId): ?>
                    <h4>Detail Produk</h4>
                <?php else: ?>
                    <h4>Tambah Produk</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card rounded shadow">
                <div class="card-body">
                    <div class="alert d-none" role="alert" id="status_message"></div>
                    <h3 class="text-center">Form Produk</h3>
                    <?php if ($hasId): ?>
                        <?php $productId = $_GET['id'] ?>
                        <?php $oldProductQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = $productId") ?>
                        <?php if (mysqli_num_rows($oldProductQuery) < 1): ?>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <h3 class="text-center">Id tidak ditemukan</h3>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php $product = mysqli_fetch_assoc($oldProductQuery) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <form action="" method="POST" id="form_product" autocomplete="off">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required
                                value="<?= $hasId ? $product['nama_produk'] : ""; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="form-control"><?= $hasId ? $product['deskripsi'] : ""; ?></textarea>
                        </div>
                        <?php if ($hasId): ?>
                            <div class="mb-3">
                                <img src="<?= './php/' . $product['gambar']; ?>" alt="<?= $product['nama_produk']; ?>"
                                    width="100" height="100" style="object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar (max 3mb) <span class="text-danger"
                                    required>*</span></label>
                            <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                            <img src="" alt="" class="d-none rounded" id="preview" hp
                                style="width: 200px; height:200px; object-fit:contain;">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="text" name="harga" id="harga" class="form-control" required
                                value="<?= $hasId ? $product['harga'] : ""; ?>">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary"><?= $hasId ? "Ubah" : "Simpan"; ?></button>
                            <?php if ($hasId): ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusProdukModal">Hapus</button>
                                <div class="modal fade" id="hapusProdukModal" tabindex="-1"
                                    aria-labelledby="hapusProdukModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="hapusProdukModalLabel">Konfirmasi Hapus
                                                    Data
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Anda akan menhapus data <strong><?= $product['nama_produk']; ?></strong>.
                                                Data
                                                yang
                                                sudah dihapus tidak dapat dikembalikan
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="button" data-bs-dismiss="modal" class="btn btn-danger"
                                                    onclick="deleteProduct(this)"
                                                    data-id="<?= $product['id']; ?>">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <button type="reset" class="btn btn-danger">Reset</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"
    referrerpolicy="no-referrer"></script>
<script>
    let submitUrl = "";
    let productId = "";

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize inputmask with Rupiah format
        Inputmask('numeric', {
            radixPoint: '.',
            groupSeparator: ',',
            digits: 2,
            autoGroup: true,
            prefix: '',
            rightAlign: false,
        }).mask(document.getElementById('harga'));
    });
    const params = new URLSearchParams(window.location.search);    

    let hasId = params.has("id") && params.get("id") !== ""

    if (hasId) {
        submitUrl = "php/ubahProduk.php";
        productId = params.get("id");
    } else {
        submitUrl = "php/tambahProduk.php";
    }
</script>
<script src="./js/hapusProduk.js"></script>
<script src="./js/formProduct.js" defer></script>
<?php include_once "./layout/footer.php" ?>