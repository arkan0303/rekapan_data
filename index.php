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
                <h4>Daftar Produk</h4>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-md-12">
            <div class="alert d-none" role="alert" id="status_message">
                
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-end gap-3 mb-4">
                <a href="tambah-produk.php" class="btn btn-secondary">Tambah Produk</a>
                <a class="btn btn-secondary">Tambah Order</a>
            </div>
            <table class="table table-hover table-light">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col" class="text-center">Nama</th>
                        <th scope="col" class="text-center">Gambar</th>
                        <th scope="col" class="text-center">Harga</th>
                        <th scope="col" class="text-center">Ppn</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = mysqli_query($conn, "SELECT id, nama_produk, gambar, harga, ppn FROM products ORDER BY id DESC")
                    ?>
                    <?php if (mysqli_num_rows($query) < 1) : ?>
                        <tr>
                            <td colspan="6" class="text-center fw-bold">Tidak ada data.</td>
                        </tr>
                    <?php else : ?>
                        <?php $i = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="text-center align-middle"><?= $i++; ?></td>
                                <td class="text-center align-middle fw-bold"><?= $row['nama_produk']; ?></td>
                                <td class="text-center"><img src="<?= './php/'. $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>" width="100" height="100" style="object-fit:contain;"></td>
                                <td class="text-center align-middle money_format"><?= $row['harga']; ?></td>
                                <td class="text-center align-middle"><?= $row['ppn']; ?>%</td>
                                <td class="text-center align-middle"><button data-id="<?= $row['id']; ?>" class="btn btn-outline-danger btn-sm rounded" onclick="deleteProduct(this)">Delete</button></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="./js/listProduk.js"></script>
<script src="./js/hapusProduk.js"></script>
<?php include "./layout/footer.php"; ?>