<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "store_computer";
$port = 3307;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>