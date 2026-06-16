<?php
session_start();

// Hapus semua data session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke halaman login setelah berhasil logout
header("Location: login.php");
exit;