<?php
require_once '../Config/koneksi.php';
require_once 'proses/register_proses.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daftar Akun - Manajemen Stok</title>

    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-sm d-none d-sm-block"></div>
                    
                    <div class="col-sm">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Buat Akun Baru</h1>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <form class="user" method="POST" action="register.php">
                                
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                        name="nama"
                                        placeholder="Nama Lengkap"
                                        value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                                        required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user"
                                        name="email"
                                        placeholder="Alamat Email (Contoh: nama@email.com)"
                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                        required>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            name="password"
                                            placeholder="Password (min. 6 karakter)"
                                            required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name="konfirmasi_password"
                                            placeholder="Ulangi Password"
                                            required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Daftar Akun
                                </button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Sudah punya akun? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>
</html>