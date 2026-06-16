<?php
//memulai sesion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//redirect jika udah login
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../Config/koneksi.php'; 

    //validasi input
    $nama     = trim($_POST['nama'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $password === '') {
        $error = 'Nama dan password wajib diisi.';
    } else {
        //query database dg prepare statment
        $stmt = mysqli_prepare($koneksi, "SELECT id_user, nama, password FROM tabel_user WHERE nama = ?");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $nama);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password dan sesion
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']  = $user['id_user'];
                $_SESSION['nama']     = $user['nama'];
                
                header('Location: ../index.php');
                exit;
            } else {
                $error = 'Nama atau password salah.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Terjadi kesalahan pada sistem database.';
        }
    }
}
?>