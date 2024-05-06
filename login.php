<?php include_once "./layout/header.php" ?>

<?php 
    session_start();
    include_once "./php/config.php";
    if (isset($_SESSION['unique_id'])) {
        header("location: /");
    }
?>

<div class="mt-5">
    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            <div class="alert alert-danger d-none" role="alert">
                
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card rounded shadow">
                <div class="card-body">
                    <h5 class="text-center">Login</h5>
                    <form action="" method="post" class="mt-4" autocomplete="off" id="form_login">
                        <div class="mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username">
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Masuk</button>
                            <button type="button" class="btn btn-primary loading d-none">Loading...</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./js/login.js"></script>
<?php include_once "./layout/footer.php" ?>