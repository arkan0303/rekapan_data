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
                <h4>Tambah Produk</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card rounded shadow">
                <div class="card-body">
                    <div class="alert d-none" role="alert" id="status_message"></div>
                    <h3 class="text-center">Form Tambah Produk</h3>
                    <form action="" method="POST" id="form_product" autocomplete="off">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar (max 3mb) <span class="text-danger" required>*</span></label>
                            <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
                            <img src="" alt="" class="d-none rounded" id="preview" style="width: 200px; height:200px; object-fit:contain;">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="text" name="harga" id="harga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js" referrerpolicy="no-referrer"></script>
<script src="./js/formProduct.js"></script>
<?php include_once "./layout/footer.php" ?>