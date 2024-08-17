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

function buildSqlQuery($sql)
{
    $searchNama = $_GET['nama'] ?? '';
    $searchNama = strtolower($searchNama);

    $searchTanggalDari = $_GET['from'] ?? '';
    $searchTanggalSampai = $_GET['to'] ?? '';

    if (!empty($searchNama) || !empty($searchTanggalDari) || !empty($searchTanggalSampai)) {
        $sql .= " WHERE ";
    }

    if (!empty($searchNama)) {
        $sql .= " LOWER(c.nama) LIKE '%$searchNama%'";
    }

    if (!empty($searchTanggalDari) && !empty($searchTanggalSampai)) {
        if (!empty($searchNama)) {
            $sql .= " AND";
        }
        $sql .= " o.tanggal_order BETWEEN '$searchTanggalDari' AND '$searchTanggalSampai'";
    } elseif (!empty($searchTanggalDari)) {
        if (!empty($searchNama)) {
            $sql .= " AND";
        }
        $sql .= " o.tanggal_order >= '$searchTanggalDari'";
    } elseif (!empty($searchTanggalSampai)) {
        if (!empty($searchNama)) {
            $sql .= " AND";
        }
        $sql .= " o.tanggal_order <= '$searchTanggalSampai'";
    }

    return $sql;
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
            <div class="card rounded shadow">
                <div class="card-body">
                    <h3 class="text-center">Filter</h3>
                    <form method="GET">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Pembeli</label>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        placeholder="Cari Nama Pembeli" value="<?= $_GET['nama'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="from" class="form-label">Tanggal Dari</label>
                                    <input type="date" class="form-control" name="from" id="from"
                                        value="<?= $_GET['from'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="to" class="form-label">Tanggal Sampai</label>
                                    <input type="date" class="form-control" name="to" id="to"
                                        value="<?= $_GET['to'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-start gap-1 align-items-center mb-1">
                        <a href="penjualan.php" class="btn btn-secondary">Tambah Data Penjualan</a>
                        <button type="button" class="btn btn-success" id="export_excel">Export Excel</button>
                    </div>
                    <table class="table table-hover table-light" id="table_penjualan">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Tanggal Order</th>
                                <th scope="col" class="text-center">Nama Pembeli</th>
                                <th scope="col" class="text-center">Total Harga</th>
                                <th scope="col" class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = 10;
                            $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                            $offset = ($currentPage - 1) * $limit;

                            $sql = "SELECT 
                                            o.id,
                                            o.tanggal_order,
                                            o.total_harga,
                                            o.customer_id,
                                            c.nama AS nama_pembeli
                                        FROM 
                                            orders o
                                        JOIN
                                            customers c ON o.customer_id = c.id\n";
                            $sql = buildSqlQuery($sql);
                            $sql .= " ORDER BY o.tanggal_order DESC LIMIT $limit OFFSET $offset";
                            $query = mysqli_query($conn, $sql);

                            $totalSql = "SELECT COUNT(*) AS total FROM orders o JOIN customers c ON o.customer_id = c.id ";
                            $totalSql = buildSqlQuery($totalSql);
                            $totalQuery = mysqli_query($conn, $totalSql);
                            $totalRow = mysqli_fetch_assoc($totalQuery);
                            $totalRecords = $totalRow['total'];
                            $totalPages = $totalRecords > 0 ? ceil($totalRecords / $limit) : 0;
                            ?>
                            <?php if (mysqli_num_rows($query) < 1): ?>
                                <tr>
                                    <td colspan="6" class="text-center fw-bold">Tidak ada data.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = $offset + 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td class="text-center align-middle" data-column="no"><?= $no++; ?></td>
                                        <td class="text-center align-middle" data-column="tanggal_order">
                                            <?= date("d/m/Y", strtotime($row['tanggal_order'])); ?>
                                        </td>
                                        <td class="text-center align-middle" data-column="nama_pembeli">
                                            <?= $row['nama_pembeli']; ?>
                                        </td>
                                        <td class="text-center align-middle money_format" data-column="total_harga">
                                            <?= $row['total_harga']; ?>
                                        </td>
                                        <td class="text-center align-middle"><a href="/penjualan.php?id=<?= $row['id']; ?>"
                                                class="btn btn-outline-dark btn-sm">Detail</a></td>
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
                                            <a class="page-link"
                                                href="<?= buildQueryString('page', $currentPage - 1) ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $currentPage == $i ? 'active' : ''; ?>"><a class="page-link"
                                                href="<?= buildQueryString("page", $i) ?>"><?= $i; ?></a></li>
                                    <?php endfor; ?>
                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="<?= buildQueryString('page', $currentPage + 1) ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/xlsx.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"
    referrerpolicy="no-referrer"></script>
<script src="./js/listPenjualan.js"></script>
<?php include_once "./layout/footer.php" ?>