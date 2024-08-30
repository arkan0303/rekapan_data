<?php include_once "./layout/header.php" ?>

<?php
session_start();
include_once "./php/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek apakah username sudah digunakan
    $sql_check = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='alert alert-danger'>Username sudah digunakan, silakan pilih yang lain.</div>";
    } else {
        // Menyimpan data pengguna ke database tanpa meng-hash password
        $sql_insert = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
        if (mysqli_query($conn, $sql_insert)) {
            header("Location: login.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Terjadi kesalahan saat mendaftar, silakan coba lagi.</div>";
        }
    }
}
?>

<!-- Include HTML Form di sini -->


<div class="mt-5">
    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            <div class="alert alert-danger d-none" role="alert"></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card rounded shadow">
                <div class="card-body">
                    <h5 class="text-center">Register</h5>
                    <form action="register.php" method="post" class="mt-4" autocomplete="off" id="form_register">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "./layout/footer.php" ?>
