<?php
//sesion jika sudah login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../Config/koneksi.php';
    //ambil input dari formnya
    $nama       = trim($_POST['nama'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    //validasi input
    if (empty($nama) || empty($email) || empty($password) || empty($konfirmasi)) {
        $error = 'Semua field wajib diisi.';
    } elseif (strlen($nama) < 4) {
        $error = 'Nama minimal 4 karakter.';
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        //cek duplikasi naama dan atau email
        $cek = mysqli_prepare($koneksi, "SELECT id_user FROM tabel_user WHERE nama = ? OR email = ?");
        mysqli_stmt_bind_param($cek, 'ss', $nama, $email);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $error = 'Nama atau Email sudah digunakan, silakan pilih yang lain.';
        } else {
            //pasword hash
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            //Proses Masukkan data ke database (3 kolom = 3 tanda tanya = 'sss')
            $stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_user (nama, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                    alert('Akun berhasil dibuat! Silakan login.');
                    window.location.href = 'login.php';
                </script>";
                exit;
            } else {
                $error = 'Gagal menyimpan data. Coba lagi.';
            }
            //tutup statment
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($cek);
    }
}
?>