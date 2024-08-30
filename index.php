<?php include "./layout/header.php"; ?>

<?php
session_start();

include_once "./php/config.php";

$unique_id = $_SESSION['unique_id'];
if (!isset($unique_id)) {
    header("location: login.php");
}

function buildQueryString($paramKey, $paramValue)
{
    $queryString = '?';

    if (isset($_GET[$paramKey])) {
        foreach ($_GET as $key => $value) {
            if ($value !== '') {
                $v = $paramKey === $key ? $paramValue : $value;
                $queryString .= urlencode($key) . '=' . urlencode($v) . '&';
            }
        }
    } else {
        foreach ($_GET as $key => $value) {
            if ($value !== '') {
                $queryString .= urlencode($key) . '=' . urlencode($value) . '&';
            }
        }
        $queryString .= urlencode($paramKey) . '=' . urlencode($paramValue);
    }



    return rtrim($queryString, '&');
}
?>

<div class="container my-5">
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
            <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                <form method="GET" class="d-flex gap-1">
                    <input type="text" name="q" class="form-control" placeholder="Cari Produk"
                        value="<?= $_GET['q'] ?? ''; ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <div>
                <?php if (isset($_SESSION['unique_id'])): ?>
    <a href="logout.php" class="btn btn-danger">Logout</a>
<?php endif; ?>
                    <a href="daftar-penjualan.php" class="btn btn-secondary">Daftar Penjualan</a>
                    <a href="tambah-produk.php" class="btn btn-secondary">Tambah Produk</a>
                </div>
            </div>
            <table class="table table-hover table-light">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col" class="text-center">Nama</th>
                        <th scope="col" class="text-center">Gambar</th>
                        <th scope="col" class="text-center">Harga</th>
                        <th scope="col" class="text-center">Harga + PPN 11%</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit = 10;
                    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                    $offset = ($currentPage - 1) * $limit;

                    $searchQuery = $_GET['q'] ?? '';
                    $searchQuery = strtolower($searchQuery);

                    $sql = "SELECT id, nama_produk, gambar, harga, ppn FROM products ";
                    if (!empty($searchQuery))
                        $sql .= "WHERE LOWER(nama_produk) LIKE '%$searchQuery%' ";
                    $sql .= "ORDER BY id DESC LIMIT $limit OFFSET $offset";

                    $query = mysqli_query($conn, $sql);

                    $totalSql = "SELECT COUNT(*) AS total FROM products ";
                    if (!empty($searchQuery))
                        $totalSql .= "WHERE LOWER(nama_produk) LIKE '%$searchQuery%'";
                    $totalQuery = mysqli_query($conn, $totalSql);
                    $totalRow = mysqli_fetch_assoc($totalQuery);
                    $totalRecords = $totalRow['total'];
                    $totalPages = $totalRecords > 0 ? ceil($totalRecords / $limit) : 0;

                    ?>
                    <?php if (mysqli_num_rows($query) < 1): ?>
                        <tr>
                            <td colspan="6" class="text-center fw-bold">Tidak ada data...</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no++; ?></td>
                                <td class="text-center align-middle fw-bold"><?= $row['nama_produk']; ?></td>
                                <td class="text-center"><img src="<?= './php/' . $row['gambar']; ?>"
                                        alt="<?= $row['nama_produk']; ?>" width="100" height="100" style="object-fit:contain;">
                                </td>
                                <td class="text-center align-middle money_format"><?= $row['harga']; ?></td>
                                <td class="text-center align-middle money_format"><?= $row['harga'] + ($row['harga'] * 0.11); ?>
                                </td>
                                <td class="text-center align-middle"><a class="btn btn-outline-dark btn-sm rounded"
                                        href="produk.php?id=<?= $row['id']; ?>">Detail</a>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6><?= $totalRecords; ?> Data Ditemukan</h6>
                </div>
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="...">
                        <ul class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildQueryString('page', $currentPage - 1) ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $currentPage == $i ? 'active' : ''; ?>"><a class="page-link"
                                        href="<?= buildQueryString("page", $i) ?>"><?= $i; ?></a></li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= buildQueryString('page', $currentPage + 1) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="./js/listProduk.js"></script>
<?php include "./layout/footer.php"; ?>