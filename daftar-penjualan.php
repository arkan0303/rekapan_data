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
                <h4>Daftar Penjualan</h4>
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
                <a href="tambah-penjualan.php" class="btn btn-secondary">Tambah Data Penjualan</a>
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
                        $query = mysqli_query(
                            $conn,
                            "SELECT 
                                orders.id AS id,
                                orders.nama_pembeli AS nama_pembeli,
                                orders.alamat_pengiriman AS alamat,
                                orders.tangggal_order AS tanggal_order,
                                products.nama_produk AS nama_produk,
                                SUM(order_products.quantity) AS quantity,
                                orders.total_harga AS total_harga
                            FROM 
                                orders
                            INNER JOIN 
                                order_products ON orders.id = order_products.order_id
                            INNER JOIN 
                                products ON order_products.product_id = products.id
                            GROUP BY 
                                orders.id, products.nama_produk
                            ORDER BY 
                                orders.id;
                        "
                        );
                    ?>
                    <?php if (mysqli_num_rows($query) < 1) : ?>
                        <tr>
                            <td colspan="6" class="text-center fw-bold">Tidak ada data.</td>
                        </tr>
                    <?php else : ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js" referrerpolicy="no-referrer"></script>
<script src="./js/formProduct.js"></script>
<?php include_once "./layout/footer.php" ?>