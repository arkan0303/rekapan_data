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

    <?php if(!isset($_GET['id'])) : ?>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h3 class="text-center">Id tidak ditemukan</h3>
            </div>
        </div>
    <?php else : ?>
        <div class="row justify-content-start mb03">
            <div class="col-md-4">
                <div class="card rounded shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Detail Penjualan</h3>

                        <?php 
                            $id = $_GET['id'];
                            $queryOrder = mysqli_query($conn, "SELECT id, nama_pembeli, tanggal_order, total_harga FROM orders WHERE id = $id");

                            $order = mysqli_fetch_assoc($queryOrder);
                        ?>

                        <div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Pembeli</label>
                                <input type="text" class="form-control" readonly value="<?= $order['nama_pembeli']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Order</label>
                                <input type="text" class="form-control" readonly value="<?= date("d/m/Y", strtotime($order['tanggal_order'])); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Total Harga</label>
                                <input type="text" class="form-control" readonly value="<?= 'Rp ' . number_format($order['total_harga'], 0, ',', ','); ?>">
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
                                ")
                                ?>
                                <?php if (mysqli_num_rows($queryProducts) < 1) : ?>
                                    <tr>
                                        <td class="text-center align-middle fw-bold" colspan="5">Data tidak ditemukan.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($queryProducts)) : ?>
                                        <tr>
                                            <td class="text-center align-middle" data-column="no"><?= $no++; ?></td>
                                            <td class="text-center align-middle" data-column="nama_produk"><?= $row['nama_produk']; ?></td>
                                            <td class="text-center align-middle" data-column="harga"><?= 'Rp ' . number_format($row['harga'], 0, ',', ','); ?></td>
                                            <td class="text-center align-middle" data-column="hargaPpn"><?= 'Rp ' . number_format($row['harga'] + ($row['harga'] * 0.11), 0, ',', ','); ?></td>
                                            <td class="text-center align-middle" data-column="qty"><?= $row['qty']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    
</div>

<?php include "./layout/footer.php"; ?>