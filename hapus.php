<?php
require 'koneksi.php';

// Hanya boleh diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('❌ Gunakan tombol Hapus pada daftar produk.');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('❌ ID produk tidak valid.');
}

// Hapus data
$stmt = mysqli_prepare($koneksi, 'DELETE FROM produk WHERE id_produk = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header('Location: index.php');
exit;
?>